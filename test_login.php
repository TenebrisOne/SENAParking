<?php
/**
 * Script de prueba para simular el login exactamente como lo hace el LoginController
 * ELIMINAR DESPUÉS DE USAR
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('backend/config/conexion.php');

echo "<h1>Test de Login Directo</h1>";

// Verificar conexión
if ($conn->connect_error) {
    die("<p style='color: red;'>Error de conexión: " . $conn->connect_error . "</p>");
}
echo "<p style='color: green;'>✅ Conexión OK</p>";

// Datos de prueba
$correo = "admin@senaparking.com";
$password = "Admin123";

echo "<h2>Probando con:</h2>";
echo "<p>Correo: $correo</p>";
echo "<p>Password: $password</p>";

// Simular exactamente lo que hace el LoginModel
echo "<h2>Ejecutando consulta...</h2>";
$sql = "SELECT * FROM tb_usersys WHERE correoUsys = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

echo "<p>Número de filas encontradas: " . $result->num_rows . "</p>";

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
    echo "<p style='color: green;'>✅ Usuario encontrado:</p>";
    echo "<pre>";
    // Ocultar el hash por seguridad
    $usuario_display = $usuario;
    $usuario_display['passwordUsys'] = substr($usuario['passwordUsys'], 0, 20) . "...";
    print_r($usuario_display);
    echo "</pre>";
    
    echo "<h3>Verificando contraseña...</h3>";
    echo "<p>Password ingresado: '$password'</p>";
    echo "<p>Hash almacenado: " . substr($usuario['passwordUsys'], 0, 30) . "...</p>";
    
    if (password_verify($password, $usuario["passwordUsys"])) {
        echo "<p style='color: green; font-size: 24px;'>✅ ¡CONTRASEÑA CORRECTA!</p>";
        
        if ($usuario['estadoUsys'] === 'activo') {
            echo "<p style='color: green; font-size: 24px;'>✅ Usuario ACTIVO - Login debería funcionar!</p>";
            echo "<p>Rol: " . $usuario['rolUsys'] . "</p>";
            
            // Simular lo que devolvería el controlador
            echo "<h3>Respuesta que daría el LoginController:</h3>";
            echo "<pre>" . $usuario['rolUsys'] . "</pre>";
        } else {
            echo "<p style='color: orange;'>⚠️ Usuario inactivo (estado: " . $usuario['estadoUsys'] . ")</p>";
        }
    } else {
        echo "<p style='color: red; font-size: 18px;'>❌ CONTRASEÑA INCORRECTA</p>";
        echo "<h4>Depuración adicional:</h4>";
        
        // Crear un nuevo hash y comparar
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        echo "<p>Nuevo hash generado para '$password':</p>";
        echo "<pre>$newHash</pre>";
        
        // Probar si el hash almacenado es válido
        $hashInfo = password_get_info($usuario['passwordUsys']);
        echo "<p>Información del hash almacenado:</p>";
        echo "<pre>" . print_r($hashInfo, true) . "</pre>";
    }
} else {
    echo "<p style='color: red; font-size: 18px;'>❌ USUARIO NO ENCONTRADO</p>";
    
    // Listar todos los usuarios
    echo "<h3>Usuarios existentes en tb_usersys:</h3>";
    $allUsers = $conn->query("SELECT id_userSys, correoUsys, rolUsys, estadoUsys FROM tb_usersys");
    if ($allUsers && $allUsers->num_rows > 0) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Correo</th><th>Rol</th><th>Estado</th></tr>";
        while ($u = $allUsers->fetch_assoc()) {
            echo "<tr><td>{$u['id_userSys']}</td><td>{$u['correoUsys']}</td><td>{$u['rolUsys']}</td><td>{$u['estadoUsys']}</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: orange;'>⚠️ No hay usuarios en la tabla</p>";
    }
}

$stmt->close();
$conn->close();
?>

<hr>
<p style="color: red; font-weight: bold;">⚠️ ELIMINA ESTE ARCHIVO</p>
<p><a href="login.php">Ir al Login</a></p>
