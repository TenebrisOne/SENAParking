<?php
use PHPUnit\Framework\TestCase;

/**
 * E2E Test: Vehicle Registration and Access Flow
 * @runTestsInSeparateProcesses
 */
class VehicleAccessE2ETest extends TestCase
{
    private $conn;
    private $testUserParkId;
    private $testVehicleId;
    private $testUserSysId;
    
    protected function setUp(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->conn = getE2EConnection();
        
        // Create test parking user
        $stmt = $this->conn->prepare("INSERT INTO tb_userpark (tipoUserUpark, tipoDocumentoUpark, numeroDocumentoUpark, nombresUpark, apellidosUpark, edificioUpark) VALUES (?, ?, ?, ?, ?, ?)");
        $tipo = 'Estudiante'; $tipoDoc = 'CC'; $numDoc = 'E2EVEH001';
        $nombre = 'Vehicle'; $apellido = 'Owner'; $edificio = 'E2E Building';
        $stmt->bind_param("ssssss", $tipo, $tipoDoc, $numDoc, $nombre, $apellido, $edificio);
        $stmt->execute();
        
        // Fetch ID by document
        $stmtId = $this->conn->prepare("SELECT id_userPark FROM tb_userpark WHERE numeroDocumentoUpark = ?");
        $stmtId->bind_param("s", $numDoc);
        $stmtId->execute();
        $resId = $stmtId->get_result();
        $rowId = $resId->fetch_assoc();
        $this->testUserParkId = $rowId['id_userPark'];
        
        // Create test system user (guard)
        $hashedPass = password_hash('guard123', PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO tb_usersys (nombresUsys, apellidosUsys, tipoDocumentoUsys, numeroDocumentoUsys, rolUsys, correoUsys, usernameUsys, passwordUsys) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $nombres = 'Guard'; $apellidos = 'E2E'; $tipoDoc = 'CC'; $numDoc = 'E2EGUARD';
        $rol = 'guardia'; $correo = 'guarde2e@test.com'; $username = 'guarde2e';
        $stmt->bind_param("ssssssss", $nombres, $apellidos, $tipoDoc, $numDoc, $rol, $correo, $username, $hashedPass);
        $stmt->execute();
        
        // Fetch ID by email
        $stmtId = $this->conn->prepare("SELECT id_userSys FROM tb_usersys WHERE correoUsys = ?");
        $stmtId->bind_param("s", $correo);
        $stmtId->execute();
        $resId = $stmtId->get_result();
        $rowId = $resId->fetch_assoc();
        $this->testUserSysId = $rowId['id_userSys'];
    }
    
    protected function tearDown(): void
    {
        if ($this->testVehicleId) {
            $this->conn->query("DELETE FROM tb_accesos WHERE id_vehiculo = {$this->testVehicleId}");
            $this->conn->query("DELETE FROM tb_vehiculos WHERE id_vehiculo = {$this->testVehicleId}");
        }
        $this->conn->query("DELETE FROM tb_userpark WHERE id_userPark = {$this->testUserParkId}");
        $this->conn->query("DELETE FROM tb_usersys WHERE id_userSys = {$this->testUserSysId}");
        $this->conn->close();
    }
    
    public function testCompleteVehicleRegistrationAndAccessFlow()
    {
        // Set session for activity logging
        $_SESSION['id_userSys'] = $this->testUserSysId;

        // Step 1: Register vehicle
        $vehicleData = [
            'propietario' => $this->testUserParkId,
            'placa' => 'E2E123',
            'tarjeta_propiedad' => 'TPE2E001',
            'tipo' => 'Automóvil',
            'modelo' => '2024',
            'color' => 'Negro'
        ];
        
        $response = simulatePostRequest(
            realpath(__DIR__ . '/../../controllers/VehicleRegisterController.php'),
            $vehicleData
        );
        
        // Verify vehicle was registered
        $stmt = $this->conn->prepare("SELECT * FROM tb_vehiculos WHERE placaVeh = ?");
        $placa = 'E2E123';
        $stmt->bind_param("s", $placa);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $this->assertEquals(1, $result->num_rows);
        $vehicle = $result->fetch_assoc();
        $this->testVehicleId = $vehicle['id_vehiculo'];
        
        // Step 2: Register entrance (access)
        $_SESSION['id_userSys'] = $this->testUserSysId;
        
        $accessData = [
            'id_vehiculo' => $this->testVehicleId,
            'tipo_accion' => 'ingreso',
            'espacio_asignado' => 25
        ];
        
        $response = simulatePostRequest(
            realpath(__DIR__ . '/../../controllers/AccessController.php'),
            $accessData
        );
        
        // Verify access was registered
        $stmt = $this->conn->prepare("SELECT * FROM tb_accesos WHERE id_vehiculo = ? AND tipoAccionAcc = ?");
        $tipo = 'ingreso';
        $stmt->bind_param("is", $this->testVehicleId, $tipo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            echo "\nAccess Response: " . $response . "\n";
        }
        $this->assertEquals(1, $result->num_rows, "Access not registered");
        $accessRecord = $result->fetch_assoc();
        $this->assertGreaterThan(0, $accessRecord['espacioAsignadoAcc']);
        $this->assertEquals($this->testUserSysId, $accessRecord['id_userSys']);
        $this->assertEquals($this->testVehicleId, $accessRecord['id_vehiculo']);
        
        // Step 3: Register exit
        sleep(1); // Small delay for different timestamp
        
        $exitData = [
            'id_vehiculo' => $this->testVehicleId,
            'tipo_accion' => 'salida',
            'espacio_asignado' => 25
        ];
        
        simulatePostRequest(
            realpath(__DIR__ . '/../../controllers/AccessController.php'),
            $exitData
        );
        
        // Verify both records exist
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM tb_accesos WHERE id_vehiculo = ?");
        $stmt->bind_param("i", $this->testVehicleId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        $this->assertEquals(2, $result['total']);
        
        $_SESSION = [];
    }
    
    public function testVehicleRegistrationWithDuplicatePlacaFails()
    {
        // Set session for activity logging
        $_SESSION['id_userSys'] = $this->testUserSysId;

        // Register first vehicle
        $vehicleData = [
            'propietario' => $this->testUserParkId,
            'placa' => 'E2EDUP',
            'tipo' => 'Moto',
            'modelo' => '2023',
            'color' => 'Rojo',
            'tarjeta_propiedad' => 'TPDUP001'
        ];
        
        simulatePostRequest(
            realpath(__DIR__ . '/../../controllers/VehicleRegisterController.php'),
            $vehicleData
        );
        
        // Get vehicle ID for cleanup
        $stmt = $this->conn->prepare("SELECT id_vehiculo FROM tb_vehiculos WHERE placaVeh = ?");
        $placa = 'E2EDUP';
        $stmt->bind_param("s", $placa);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $this->testVehicleId = $result['id_vehiculo'];
        
        // Try to register duplicate
        $response = simulatePostRequest(
            realpath(__DIR__ . '/../../controllers/VehicleRegisterController.php'),
            $vehicleData
        );
        
        // Verify only one vehicle exists
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM tb_vehiculos WHERE placaVeh = ?");
        $stmt->bind_param("s", $placa);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        $this->assertEquals(1, $result['total']);
    }
}
