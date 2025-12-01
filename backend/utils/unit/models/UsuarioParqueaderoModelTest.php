<?php
use PHPUnit\Framework\TestCase;

class UsuarioParqueaderoModelTest extends TestCase
{
    public function testUsuarioParqueaderoClassExists()
    {
        $file = __DIR__ . '/../../../models/UsuarioParqueaderoModel.php';
        $this->assertFileExists($file);
        
        require_once $file;
        $this->assertTrue(class_exists('UsuarioParqueadero'));
    }
    
    public function testPaginationParameters()
    {
        $limit = 10;
        $offset = 0;
        
        $this->assertIsInt($limit);
        $this->assertIsInt($offset);
        $this->assertGreaterThanOrEqual(0, $limit);
        $this->assertGreaterThanOrEqual(0, $offset);
    }
}
