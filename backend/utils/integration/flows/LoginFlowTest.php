<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../../models/LoginModel.php';
require_once __DIR__ . '/../../../models/ActividadModel.php';

/**
 * Test de flujo completo de login
 */
class LoginFlowTest extends TestCase
{
    private $conn;
    private $testUserId;
    
    protected function setUp(): void
    {
        $this->conn = getTestConnection();
        
        // Crear usuario de prueba
        $hashedPass = password_hash('flowtest123', PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO tb_usersys (nombresUsys, apellidosUsys, tipoDocumentoUsys, numeroDocumentoUsys, rolUsys, correoUsys, usernameUsys, passwordUsys, estadoUsys) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $nombres = 'Flow'; $apellidos = 'Test'; $tipoDoc = 'CC'; $numDoc = 'TESTFLOW001';
        $rol = 'guardia'; $correo = 'flow@test.com'; $username = 'flowtest'; $estado = 'activo';
        $stmt->bind_param("sssssssss", $nombres, $apellidos, $tipoDoc, $numDoc, $rol, $correo, $username, $hashedPass, $estado);
        $stmt->execute();
        $this->testUserId = $this->conn->insert_id;
    }
    
    protected function tearDown(): void
    {
        $this->conn->query("DELETE FROM tb_actividades WHERE id_userSys = {$this->testUserId}");
        $this->conn->query("DELETE FROM tb_usersys WHERE id_userSys = {$this->testUserId}");
        $this->conn->close();
    }
    
    public function testCompleteLoginFlowWithActivityLog()
    {
// 1. Validar login
        $loginModel = new login($this->conn);
        $result = $loginModel->validar_login('flow@test.com', 'flowtest123');
        
        $this->assertEquals('activo', $result);
        $this->assertEquals('flow@test.com', $_SESSION['correo']);
        
        // 2. Registrar actividad de login
        $actividadModel = new ActividadModel($this->conn);
        $actividadModel->registrarActividad($this->testUserId, 'Inicio de sesión');
        
        // 3. Verificar que se registró la actividad
        $stmt = $this->conn->prepare("SELECT * FROM tb_actividades WHERE id_userSys = ? AND accionActi = ?");
        $accion = 'Inicio de sesión';
        $stmt->bind_param("is", $this->testUserId, $accion);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $this->assertEquals(1, $result->num_rows);
        $actividad = $result->fetch_assoc();
        $this->assertEquals('Inicio de sesión', $actividad['accionActi']);
    }
}
