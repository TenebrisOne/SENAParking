<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../../models/LoginModel.php';

class LoginIntegrationTest extends TestCase
{
    private $conn;
    private $login;
    private $testUserId;
    
    protected function setUp(): void
    {
        $this->conn = getTestConnection();
        $this->login = new login($this->conn);
        
        // Crear usuario de prueba
        $hashedPassword = password_hash('testpass123', PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO tb_usersys (nombresUsys, apellidosUsys, tipoDocumentoUsys, numeroDocumentoUsys, rolUsys, correoUsys, usernameUsys, passwordUsys, estadoUsys) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $nombres = 'Login'; $apellidos = 'Test'; $tipoDoc = 'CC'; $numDoc = 'TESTLOGIN001';
        $rol = 'admin'; $correo = 'logintest@test.com'; $username = 'logintest';
        $estado = 'activo';
        $stmt->bind_param("sssssssss", $nombres, $apellidos, $tipoDoc, $numDoc, $rol, $correo, $username, $hashedPassword, $estado);
        $stmt->execute();
        $this->testUserId = $this->conn->insert_id;
    }
    
    protected function tearDown(): void
    {
        // Limpiar usuario de prueba
        $stmt = $this->conn->prepare("DELETE FROM tb_usersys WHERE id_userSys = ?");
        $stmt->bind_param("i", $this->testUserId);
        $stmt->execute();
        $this->conn->close();
    }
    
    public function testLoginWithValidCredentialsReturnsActivo()
    {
        $result = $this->login->validar_login('logintest@test.com', 'testpass123');
        $this->assertEquals('activo', $result);
        
        // Verificar que se creó la sesión
        $this->assertEquals('logintest@test.com', $_SESSION['correo']);
        $this->assertEquals('Login', $_SESSION['nombre']);
        $this->assertEquals('admin', $_SESSION['rol']);
    }
    
    public function testLoginWithWrongPasswordReturnsErrorcontra()
    {
        $result = $this->login->validar_login('logintest@test.com', 'wrongpassword');
        $this->assertEquals('errocontra', $result);
    }
    
    public function testLoginWithNonExistentUserReturnsNousuario()
    {
        $result = $this->login->validar_login('nonexistent@test.com', 'anypassword');
        $this->assertEquals('Nousuario', $result);
    }
    
    public function testLoginWithInactiveUserReturnsInactivo()
    {
        // Cambiar usuario a inactivo
        $stmt = $this->conn->prepare("UPDATE tb_usersys SET estadoUsys = ? WHERE id_userSys = ?");
        $estado = 'inactivo';
        $stmt->bind_param("si", $estado, $this->testUserId);
        $stmt->execute();
        
        $result = $this->login->validar_login('logintest@test.com', 'testpass123');
        $this->assertEquals('inactivo', $result);
    }
}
