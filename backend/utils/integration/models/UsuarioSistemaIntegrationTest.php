<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../../models/UsuarioSistemaModel.php';

class UsuarioSistemaIntegrationTest extends TestCase
{
    private $conn;
    private $usuario;
    
    protected function setUp(): void
    {
        $this->conn = getTestConnection();
        $this->usuario = new Usuario($this->conn);
        
        // Limpiar tabla antes de cada test
        $this->conn->query("DELETE FROM tb_usersys WHERE numeroDocumentoUsys LIKE 'TEST%'");
    }
    
    protected function tearDown(): void
    {
        // Limpiar después del test
        $this->conn->query("DELETE FROM tb_usersys WHERE numeroDocumentoUsys LIKE 'TEST%'");
        $this->conn->close();
    }
    
    public function testRegistrarUsuarioInsertsIntoDatabase()
    {
        $result = $this->usuario->registrarUsuario(
            'Test', 'Usuario', 'CC', 'TEST001',
            'admin', 'test001@test.com', '3001234567',
            'testuser', 'password123'
        );
        
        $this->assertTrue($result);
        
        // Verificar que se insertó en BD
        $stmt = $this->conn->prepare("SELECT * FROM tb_usersys WHERE numeroDocumentoUsys = ?");
        $stmt->bind_param("s", $doc);
        $doc = 'TEST001';
        $stmt->execute();
        $queryResult = $stmt->get_result();
        
        $this->assertEquals(1, $queryResult->num_rows);
        
        $user = $queryResult->fetch_assoc();
        $this->assertEquals('Test', $user['nombresUsys']);
        $this->assertEquals('test001@test.com', $user['correoUsys']);
        $this->assertTrue(password_verify('password123', $user['passwordUsys']));
    }
    
    public function testRegistrarUsuarioDetectsDuplicateDocument()
    {
        // Insertar primer usuario
        $this->usuario->registrarUsuario(
            'First', 'User', 'CC', 'TEST002',
            'admin', 'first@test.com', '3001111111',
            'firstuser', 'pass123'
        );
        
        // Intentar insertar segundo usuario con mismo documento
        $result = $this->usuario->registrarUsuario(
            'Second', 'User', 'CC', 'TEST002',
            'admin', 'second@test.com', '3002222222',
            'seconduser', 'pass456'
        );
        
        $this->assertEquals('duplicado', $result);
    }
    
    public function testObtenerUsuariosReturnsAllUsers()
    {
        // Insertar usuarios de prueba
        $this->usuario->registrarUsuario(
            'User1', 'Test', 'CC', 'TEST003',
            'admin', 'user1@test.com', '3001111111',
            'user1', 'pass123'
        );
        
        $this->usuario->registrarUsuario(
            'User2', 'Test', 'CC', 'TEST004',
            'guardia', 'user2@test.com', '3002222222',
            'user2', 'pass456'
        );
        
        $usuarios = $this->usuario->obtenerUsuarios();
        
        $this->assertIsArray($usuarios);
        $this->assertGreaterThanOrEqual(2, count($usuarios));
    }
    
    public function testCambiarEstadoUsuarioUpdatesDatabase()
    {
        // Crear usuario
        $this->usuario->registrarUsuario(
            'Active', 'User', 'CC', 'TEST005',
            'admin', 'active@test.com', '3001111111',
            'activeuser', 'pass123'
        );
        
        // Obtener ID
        $stmt = $this->conn->prepare("SELECT id_userSys FROM tb_usersys WHERE numeroDocumentoUsys = ?");
        $stmt->bind_param("s", $doc);
        $doc = 'TEST005';
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $userId = $user['id_userSys'];
        
        // Cambiar estado
        $updateResult = $this->usuario->cambiarEstadoUsuario($userId, 'inactivo');
        $this->assertTrue($updateResult);
        
        // Verificar en BD
        $stmt = $this->conn->prepare("SELECT estadoUsys FROM tb_usersys WHERE id_userSys = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        $this->assertEquals('inactivo', $user['estadoUsys']);
    }
}
