<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../../models/Access.php';

class AccessIntegrationTest extends TestCase
{
    private $conn;
    private $access;
    private $testVehicleId;
    private $testUserSysId;
    
    protected function setUp(): void
    {
        $this->conn = getTestConnection();
        $this->access = new Access($this->conn);
        
        // Crear usuario de parqueadero
        $stmt = $this->conn->prepare("INSERT INTO tb_userpark (tipoUserUpark, tipoDocumentoUpark, numeroDocumentoUpark, nombresUpark, apellidosUpark) VALUES (?, ?, ?, ?, ?)");
        $tipo = 'Estudiante'; $tipoDoc = 'CC'; $numDoc = 'TESTACC001'; $nombre = 'Access'; $apellido = 'Test';
        $stmt->bind_param("sssss", $tipo, $tipoDoc, $numDoc, $nombre, $apellido);
        $stmt->execute();
        $userParkId = $this->conn->insert_id;
        
        // Crear vehículo
        $stmt = $this->conn->prepare("INSERT INTO tb_vehiculos (id_userPark, placaVeh, tipoVeh, modeloVeh, colorVeh) VALUES (?, ?, ?, ?, ?)");
        $placa = 'TESTACC'; $tipo = 'Auto'; $modelo = '2024'; $color = 'Azul';
        $stmt->bind_param("issss", $userParkId, $placa, $tipo, $modelo, $color);
        $stmt->execute();
        $this->testVehicleId = $this->conn->insert_id;
        
        // Crear usuario sistema
        $hashedPass = password_hash('test123', PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO tb_usersys (nombresUsys, apellidosUsys, tipoDocumentoUsys, numeroDocumentoUsys, rolUsys, correoUsys, usernameUsys, passwordUsys) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $nombres = 'Guard'; $apellidos = 'Test'; $tipoDoc = 'CC'; $numDoc = 'TESTGUARD';
        $rol = 'guardia'; $correo = 'guard@test.com'; $username = 'guardtest';
        $stmt->bind_param("ssssssss", $nombres, $apellidos, $tipoDoc, $numDoc, $rol, $correo, $username, $hashedPass);
        $stmt->execute();
        $this->testUserSysId = $this->conn->insert_id;
    }
    
    protected function tearDown(): void
    {
        $this->conn->query("DELETE FROM tb_accesos WHERE id_vehiculo = {$this->testVehicleId}");
        $this->conn->query("DELETE FROM tb_vehiculos WHERE id_vehiculo = {$this->testVehicleId}");
        $this->conn->query("DELETE FROM tb_userpark WHERE numeroDocumentoUpark = 'TESTACC001'");
        $this->conn->query("DELETE FROM tb_usersys WHERE id_userSys = {$this->testUserSysId}");
        $this->conn->close();
    }
    
    public function testCreateAccessInsertsIntoDatabase()
    {
        $this->access->id_vehiculo = $this->testVehicleId;
        $this->access->id_userSys = $this->testUserSysId;
        $this->access->tipo_accion = 'ingreso';
        $this->access->fecha_hora = date('Y-m-d H:i:s');
        $this->access->espacio_asignado = 15;
        
        $result = $this->access->create();
        $this->assertTrue($result);
        
        // Verificar en BD
        $stmt = $this->conn->prepare("SELECT * FROM tb_accesos WHERE id_vehiculo = ? AND id_userSys = ?");
        $stmt->bind_param("ii", $this->testVehicleId, $this->testUserSysId);
        $stmt->execute();
        $resultSet = $stmt->get_result();
        
        $this->assertEquals(1, $resultSet->num_rows);
        $accessData = $resultSet->fetch_assoc();
        $this->assertEquals('ingreso', $accessData['tipoAccionAcc']);
        $this->assertEquals(15, $accessData['espacioAsignadoAcc']);
    }
    
    public function testMultipleAccessRecordsForSameVehicle()
    {
        // Registrar ingreso
        $this->access->id_vehiculo = $this->testVehicleId;
        $this->access->id_userSys = $this->testUserSysId;
        $this->access->tipo_accion = 'ingreso';
        $this->access->fecha_hora = date('Y-m-d H:i:s');
        $this->access->espacio_asignado = 20;
                $this->access->create();
        
        sleep(1); // Pequeña pausa para diferente timestamp
        
        // Registrar salida
        $access2 = new Access($this->conn);
        $access2->id_vehiculo = $this->testVehicleId;
        $access2->id_userSys = $this->testUserSysId;
        $access2->tipo_accion = 'salida';
        $access2->fecha_hora = date('Y-m-d H:i:s');
        $access2->espacio_asignado = 20;
        $access2->create();
        
        // Verificar ambos registros
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM tb_accesos WHERE id_vehiculo = ?");
        $stmt->bind_param("i", $this->testVehicleId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        $this->assertEquals(2, $result['total']);
    }
}
