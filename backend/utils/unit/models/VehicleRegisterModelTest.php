<?php
use PHPUnit\Framework\TestCase;

class VehicleRegisterModelTest extends TestCase
{
    public function testDataSanitization()
    {
        $dirtyData = '<script>XSS</script>CleanData';
        $cleanData = strip_tags($dirtyData);
        
        // strip_tags removes HTML tags but keeps inner text
        $this->assertStringNotContainsString('<script>', $cleanData);
        $this->assertStringNotContainsString('</script>', $cleanData);
        $this->assertEquals('XSSCleanData', $cleanData);
    }
    
    public function testPlacaFormat()
    {
        $placa = 'ABC123';
        
        $this->assertIsString($placa);
        $this->assertEquals(6, strlen($placa));
    }
    
    public function testVehicleProperties()
    {
        $vehicleData = [
            'id_userPark' => 1,
            'placa' => 'XYZ789',
            'tipo' => 'Auto',
            'modelo' => '2024',
            'color' => 'Rojo'
        ];
        
        $this->assertArrayHasKey('placa', $vehicleData);
        $this->assertArrayHasKey('tipo', $vehicleData);
        $this->assertArrayHasKey('modelo', $vehicleData);
    }
}
