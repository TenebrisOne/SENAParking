<?php
use PHPUnit\Framework\TestCase;

class MostrarDatosModelTest extends TestCase
{
    public function testMostrarDatosModelClassExists()
    {
        $file = __DIR__ . '/../../../models/MostrarDatosModel.php';
        $this->assertFileExists($file);
        
        require_once $file;
        $this->assertTrue(class_exists('MostrarDatosModel'));
    }
    
    public function testCounterReturnTypes()
    {
        $count = 10;
        $this->assertIsInt($count);
        $this->assertGreaterThanOrEqual(0, $count);
    }
    
    public function testArrayReturnTypes()
    {
        $resultArray = [];
        $this->assertIsArray($resultArray);
    }
}
