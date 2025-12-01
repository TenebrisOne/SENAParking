<?php
use PHPUnit\Framework\TestCase;

class ActividadModelTest extends TestCase
{
    public function testActividadModelClassExists()
    {
        $file = __DIR__ . '/../../../models/ActividadModel.php';
        $this->assertFileExists($file);
        
        require_once $file;
        $this->assertTrue(class_exists('ActividadModel'));
    }
    
    public function testActivityDataStructure()
    {
        $activityData = [
            'id_userSys' => 1,
            'accion' => 'Inicio de sesión'
        ];
        
        $this->assertIsInt($activityData['id_userSys']);
        $this->assertIsString($activityData['accion']);
    }
}
