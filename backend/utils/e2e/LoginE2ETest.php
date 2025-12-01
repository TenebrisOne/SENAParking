<?php
use PHPUnit\Framework\TestCase;

/**
 * E2E Test: Login Flow Complete
 * Tests the entire login process from HTTP request to session creation
 * @runTestsInSeparateProcesses
 */
class LoginE2ETest extends TestCase
{
    private $conn;
    private $testUserId;
    
    protected function setUp(): void
    {
        $this->conn = getE2EConnection();
        
        // Create test user
        $hashedPass = password_hash('e2etest123', PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO tb_usersys (nombresUsys, apellidosUsys, tipoDocumentoUsys, numeroDocumentoUsys, rolUsys, correoUsys, usernameUsys, passwordUsys, estadoUsys) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $nombres = 'E2E'; $apellidos = 'Login'; $tipoDoc = 'CC'; $numDoc = 'E2ELOGIN001';
        $rol = 'guardia'; $correo = 'e2elogin@test.com'; $username = 'e2elogin'; $estado = 'activo';
        $stmt->bind_param("sssssssss", $nombres, $apellidos, $tipoDoc, $numDoc, $rol, $correo, $username, $hashedPass, $estado);
        $stmt->execute();
        $stmt->execute();
        
        // Fetch ID by email to be safe
        $stmtId = $this->conn->prepare("SELECT id_userSys FROM tb_usersys WHERE correoUsys = ?");
        $stmtId->bind_param("s", $correo);
        $stmtId->execute();
        $resId = $stmtId->get_result();
        $rowId = $resId->fetch_assoc();
        $this->testUserId = $rowId['id_userSys'];
    }
    
    protected function tearDown(): void
    {
        // Clean up
        $this->conn->query("DELETE FROM tb_actividades WHERE id_userSys = {$this->testUserId}");
        $this->conn->query("DELETE FROM tb_usersys WHERE id_userSys = {$this->testUserId}");
        $this->conn->close();
        
        // Clear session
        $_SESSION = [];
    }
    
    public function testLoginFlowWithValidCredentials()
    {
        // Simulate POST request to login controller
        $postData = [
            'correo' => 'e2elogin@test.com',
            'password' => 'e2etest123'
        ];
        
        $response = simulatePostRequest(
            __DIR__ . '/../../controllers/LoginController.php',
            $postData
        );
        
        // Verify response (should return role)
        $this->assertEquals('guardia', trim($response));
        
        // Verify session was created
        $this->assertArrayHasKey('correo', $_SESSION);
        $this->assertEquals('e2elogin@test.com', $_SESSION['correo']);
        $this->assertEquals('E2E', $_SESSION['nombre']);
        $this->assertEquals('guardia', $_SESSION['rol']);
        
        // echo "DEBUG: Session ID User: " . $_SESSION['id_userSys'] . "\n";
        // echo "DEBUG: Test User ID: " . $this->testUserId . "\n";
        
        // Verify activity was logged in database
        $stmt = $this->conn->prepare("SELECT * FROM tb_actividades WHERE id_userSys = ? AND accionActi LIKE ?");
        $accion = 'Inicio%';
        $stmt->bind_param("is", $this->testUserId, $accion);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            echo "\nLogin Response: " . $response . "\n";
        }
        $this->assertGreaterThanOrEqual(1, $result->num_rows, "Activity log not found");
    }
    
    public function testLoginFlowWithInvalidPassword()
    {
        $postData = [
            'correo' => 'e2elogin@test.com',
            'password' => 'wrongpassword'
        ];
        
        $response = simulatePostRequest(
            __DIR__ . '/../../controllers/LoginController.php',
            $postData
        );
        
        $this->assertStringContainsString('Contraseña incorrecta', $response);
        $this->assertArrayNotHasKey('correo', $_SESSION);
    }
    
    public function testLoginFlowWithNonExistentUser()
    {
        $postData = [
            'correo' => 'noexiste@test.com',
            'password' => 'anypassword'
        ];
        
        $response = simulatePostRequest(
            __DIR__ . '/../../controllers/LoginController.php',
            $postData
        );
        
        $this->assertStringContainsString('Usuario no encontrado', $response);
    }
}
