<?php
use PHPUnit\Framework\TestCase;

class LoginControllerTest extends TestCase
{
    public function testLoginControllerExists()
    {
        $file = __DIR__ . '/../../../controllers/LoginController.php';
        $this->assertFileExists($file);
    }
    
    public function testLoginControllerHasDependencies()
    {
        $file = __DIR__ . '/../../../controllers/LoginController.php';
        $content = file_get_contents($file);
        
        $this->assertStringContainsString('LoginModel', $content);
        $this->assertStringContainsString('conexion', $content);
    }
    
    public function testLoginStates()
    {
        $file = __DIR__ . '/../../../controllers/LoginController.php';
        $content = file_get_contents($file);
        
        $this->assertStringContainsString('activo', $content);
        $this->assertStringContainsString('inactivo', $content);
    }
}
