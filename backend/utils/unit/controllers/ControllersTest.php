<?php
use PHPUnit\Framework\TestCase;

class ControllersTest extends TestCase
{
    public function controllerFilesProvider()
    {
        return [
            ['AccessController.php'],
            ['VehicleController.php'],
            ['UsuarioSistemaController.php'],
            ['UsuarioParqueaderoController.php'],
            ['VehicleRegisterController.php'],
            ['MostrarDatosController.php'],
            ['ActividadController.php']
        ];
    }
    
    /**
     * @dataProvider controllerFilesProvider
     */
    public function testControllerFileExists($filename)
    {
        $file = __DIR__ . '/../../../controllers/' . $filename;
        $this->assertFileExists($file, "Controller {$filename} should exist");
    }
    
    /**
     * @dataProvider controllerFilesProvider
     */
    public function testControllerIsReadable($filename)
    {
        $file = __DIR__ . '/../../../controllers/' . $filename;
        if (file_exists($file)) {
            $this->assertIsReadable($file);
        } else {
            $this->markTestSkipped("File {$filename} not found");
        }
    }
}
