<?php
use PHPUnit\Framework\TestCase;

class AccessTest extends TestCase
{
    public function testAccessClassExists()
    {
        $file = __DIR__ . '/../../../models/Access.php';
        $this->assertFileExists($file);
        
        require_once $file;
        $this->assertTrue(class_exists('Access'));
        $this->assertTrue(class_exists('GetPlaca'));
    }
    
    public function testAccessHasRequiredProperties()
    {
        require_once __DIR__ . '/../../../models/Access.php';
        
        $mockConn = $this->createMock(mysqli::class);
        $access = new Access($mockConn);
        
        $this->assertObjectHasProperty('id_vehiculo', $access);
        $this->assertObjectHasProperty('id_userSys', $access);
        $this->assertObjectHasProperty('tipo_accion', $access);
    }
}
