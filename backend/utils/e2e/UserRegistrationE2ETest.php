<?php
use PHPUnit\Framework\TestCase;

/**
 * E2E Test: User Registration Complete Flow
 * @runTestsInSeparateProcesses
 */
class UserRegistrationE2ETest extends TestCase
{
    private $conn;
    private $createdUserIds = [];
    private $adminUserId;

    protected function setUp(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->conn = getE2EConnection();
        
        // Create admin user for session
        $hashedPass = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO tb_usersys (nombresUsys, apellidosUsys, tipoDocumentoUsys, numeroDocumentoUsys, rolUsys, correoUsys, usernameUsys, passwordUsys, estadoUsys) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $nombres = 'Admin'; $apellidos = 'Tester'; $tipoDoc = 'CC'; $numDoc = 'ADMIN001';
        $rol = 'admin'; $correo = 'admin@test.com'; $username = 'admintest'; $estado = 'activo';
        $stmt->bind_param("sssssssss", $nombres, $apellidos, $tipoDoc, $numDoc, $rol, $correo, $username, $hashedPass, $estado);
        $stmt->execute();
        $stmt->execute();
        
        // Fetch ID by email
        $stmtId = $this->conn->prepare("SELECT id_userSys FROM tb_usersys WHERE correoUsys = ?");
        $stmtId->bind_param("s", $correo);
        $stmtId->execute();
        $resId = $stmtId->get_result();
        $rowId = $resId->fetch_assoc();
        $this->adminUserId = $rowId['id_userSys'];
        
        // Set session
        $_SESSION['id_userSys'] = $this->adminUserId;
        $_SESSION['rol'] = 'admin';
    }
    
    protected function tearDown(): void
    {
        // Clean up created users
        foreach ($this->createdUserIds as $id) {
            $this->conn->query("DELETE FROM tb_usersys WHERE id_userSys = {$id}");
        }
        
        // Clean up admin
        if ($this->adminUserId) {
            $this->conn->query("DELETE FROM tb_usersys WHERE id_userSys = {$this->adminUserId}");
        }
        
        $this->conn->close();
        $_SESSION = [];
    }
    
    public function testCompleteUserSystemRegistration()
    {
        // Simulate user system registration
        $postData = [
            'nombre' => 'New',
            'apellido' => 'User',
            'tipdoc' => 'CC',
            'documento' => 'E2EUSER001',
            'rol' => 'admin',
            'correo' => 'newuser@test.com',
            'numero' => '3001234567',
            'usuario' => 'newuser',
            'contrasena' => 'newpass123'
        ];
        
        $response = simulatePostRequest(
            __DIR__ . '/../../controllers/UsuarioSistemaController.php',
            $postData
        );
        
        // Verify user was created in database
        $stmt = $this->conn->prepare("SELECT * FROM tb_usersys WHERE numeroDocumentoUsys = ?");
        $doc = 'E2EUSER001';
        $stmt->bind_param("s", $doc);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            echo "\nRegistration Response: " . $response . "\n";
        }
        $this->assertEquals(1, $result->num_rows, "User not created in DB");
        
        $user = $result->fetch_assoc();
        $this->assertEquals('New', $user['nombresUsys']);
        $this->assertEquals('newuser@test.com', $user['correoUsys']);
        $this->assertEquals('activo', $user['estadoUsys']);
        $this->assertTrue(password_verify('newpass123', $user['passwordUsys']));
        
        $this->createdUserIds[] = $user['id_userSys'];
    }
    
    public function testUserParkRegistration()
    {
        // Simulate parking user registration
        $postData = [
            'tipo_usuario' => 'Estudiante',
            'tipdoc' => 'CC',
            'documento' => 'E2EPARK001',
            'nombre' => 'Student',
            'apellido' => 'Park',
            'edificio' => 'Edificio E2E',
            'numero' => '3009876543'
        ];
        
        $response = simulatePostRequest(
            __DIR__ . '/../../controllers/UsuarioParqueaderoController.php',
            $postData
        );
        
        // Verify in database
        $stmt = $this->conn->prepare("SELECT * FROM tb_userpark WHERE numeroDocumentoUpark = ?");
        $doc = 'E2EPARK001';
        $stmt->bind_param("s", $doc);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $this->assertEquals(1, $result->num_rows);
        
        $user = $result->fetch_assoc();
        $this->assertEquals('Student', $user['nombresUpark']);
        $this->assertEquals('Edificio E2E', $user['edificioUpark']);
        
        // Clean up
        $this->conn->query("DELETE FROM tb_userpark WHERE id_userPark = {$user['id_userPark']}");
    }
}
