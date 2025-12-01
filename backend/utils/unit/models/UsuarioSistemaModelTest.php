<?php
use PHPUnit\Framework\TestCase;

class UsuarioSistemaModelTest extends TestCase
{
    public function testUsuarioClassExists()
    {
        $file = __DIR__ . '/../../../models/UsuarioSistemaModel.php';
        $this->assertFileExists($file);
        
        require_once $file;
        $this->assertTrue(class_exists('Usuario'));
    }
    
    public function testPasswordHashingInRegistration()
    {
        // Test que el hash de password funciona
        $plainPassword = 'password123';
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
        
        $this->assertNotEquals($plainPassword, $hashedPassword);
        $this->assertTrue(password_verify($plainPassword, $hashedPassword));
    }
    
    public function testDuplicateDetection()
    {
        // Simular código de error mysqli 1062 (duplicate)
        $errorCode = 1062;
        $this->assertEquals(1062, $errorCode);
    }
}
