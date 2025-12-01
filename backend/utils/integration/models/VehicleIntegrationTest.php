<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../../models/VehicleRegisterModel.php';

class VehicleIntegrationTest extends TestCase
{
    private $pdo;
    private $vehicle;
    private $testUserParkId;
    
    protected function setUp(): void
    {
        $this->pdo = getTestPDO();
        $this->vehicle = new Vehicle($this->pdo);
        
        // Crear usuario de parqueadero de prueba
        $stmt = $this->pdo->prepare("INSERT INTO tb_userpark (tipoUserUpark, tipoDocumentoUpark, numeroDocumentoUpark, nombresUpark, apellidosUpark, edificioUpark) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute(['Estudiante', 'CC', 'TESTPARK001', 'Test', 'Vehicle Owner', 'Edificio Test']);
        $this->testUserParkId = $this->pdo->lastInsertId();
    }
    
    protected function tearDown(): void
    {
        // Limpiar vehículos de prueba
        $this->pdo->exec("DELETE FROM tb_vehiculos WHERE placaVeh LIKE 'TEST%'");
        $this->pdo->exec("DELETE FROM tb_userpark WHERE numeroDocumentoUpark = 'TESTPARK001'");
        $this->pdo = null;
    }
    
    public function testCreateVehicleInsertsIntoDatabase()
    {
        $this->vehicle->id_userPark = $this->testUserParkId;
        $this->vehicle->placa = 'TEST123';
        $this->vehicle->tarjeta_propiedad = 'TP001';
        $this->vehicle->tipo = 'Automóvil';
        $this->vehicle->modelo = '2024';
        $this->vehicle->color = 'Azul';
        
        $result = $this->vehicle->create();
        $this->assertTrue($result);
        
        // Verificar en BD
        $stmt = $this->pdo->prepare("SELECT * FROM tb_vehiculos WHERE placaVeh = ?");
        $stmt->execute(['TEST123']);
        $vehicleData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->assertNotNull($vehicleData);
        $this->assertEquals('TEST123', $vehicleData['placaVeh']);
        $this->assertEquals('Automóvil', $vehicleData['tipoVeh']);
    }
    
    public function testCreateVehicleWithDuplicatePlacaFails()
    {
        // Crear primer vehículo
        $this->vehicle->id_userPark = $this->testUserParkId;
        $this->vehicle->placa = 'TEST456';
        $this->vehicle->tarjeta_propiedad = 'TP002';
        $this->vehicle->tipo = 'Moto';
        $this->vehicle->modelo = '2023';
        $this->vehicle->color = 'Rojo';
        $this->vehicle->create();
        
        // Intentar crear segundo con misma placa
        $vehicle2 = new Vehicle($this->pdo);
        $vehicle2->id_userPark = $this->testUserParkId;
        $vehicle2->placa = 'TEST456';
        $vehicle2->tarjeta_propiedad = 'TP003';
        $vehicle2->tipo = 'Auto';
        $vehicle2->modelo = '2024';
        $vehicle2->color = 'Negro';
        
        $result = $vehicle2->create();
        $this->assertFalse($result);
    }
    
    public function testReadVehicleWithRelationship()
    {
        // Crear vehículo
        $this->vehicle->id_userPark = $this->testUserParkId;
        $this->vehicle->placa = 'TEST789';
        $this->vehicle->tarjeta_propiedad = 'TP004';
        $this->vehicle->tipo = 'Camioneta';
        $this->vehicle->modelo = '2022';
        $this->vehicle->color = 'Blanco';
        $this->vehicle->create();
        
        // Leer desde BD
        $stmt = $this->vehicle->read();
        $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $this->assertGreaterThan(0, count($vehicles));
        
        // Buscar nuestro vehículo de prueba
        $found = false;
        foreach ($vehicles as $v) {
            if ($v['placaVeh'] == 'TEST789') {
                $found = true;
                $this->assertEquals('Test', $v['nombresUpark']);
                $this->assertEquals('Vehicle Owner', $v['apellidosUpark']);
                break;
            }
        }
        $this->assertTrue($found, 'Test vehicle should be found');
    }
    
    public function testUpdateVehicle()
    {
        // Crear vehículo
        $this->vehicle->id_userPark = $this->testUserParkId;
        $this->vehicle->placa = 'TESTUPD';
        $this->vehicle->tarjeta_propiedad = 'TP005';
        $this->vehicle->tipo = 'Moto';
        $this->vehicle->modelo = '2023';
        $this->vehicle->color = 'Verde';
        $this->vehicle->create();
        
        // Obtener ID
        $stmt = $this->pdo->prepare("SELECT id_vehiculo FROM tb_vehiculos WHERE placaVeh = ?");
        $stmt->execute(['TESTUPD']);
        $vehicleId = $stmt->fetch(PDO::FETCH_ASSOC)['id_vehiculo'];
        
        // Actualizar
        $this->vehicle->id_vehiculo = $vehicleId;
        $this->vehicle->color = 'Amarillo';
        $result = $this->vehicle->update();
        
        $this->assertTrue($result);
        
        // Verificar cambio
        $stmt = $this->pdo->prepare("SELECT colorVeh FROM tb_vehiculos WHERE id_vehiculo = ?");
        $stmt->execute([$vehicleId]);
        $color = $stmt->fetch(PDO::FETCH_ASSOC)['colorVeh'];
        
        $this->assertEquals('Amarillo', $color);
    }
}
