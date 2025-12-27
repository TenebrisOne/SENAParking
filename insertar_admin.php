<?php
/**
 * Script simplificado para insertar admin
 * ELIMINAR INMEDIATAMENTE DESPUÉS DE USAR
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('backend/config/conexion.php');

echo "<h1>Inserción Directa de Usuario Admin</h1>";

// Verificar conexión
if ($conn->connect_error) {
    die("<p style='color: red;'>Error de conexión: " . $conn->connect_error . "</p>");
}

echo "<p style='color: green;'>✅ Conexión OK</p>";

// Primero obtener la estructura exacta de la tabla
echo "<h2>Estructura de la tabla:</h2>";
$descResult = $conn->query("DESCRIBE tb_usersys");
$campos = [];
if ($descResult) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $descResult->fetch_assoc()) {
        $campos[] = $row;
        echo "<tr>";
        echo "<td><strong>" . $row['Field'] . "</strong></td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
        echo "<td>" . ($row['Extra'] ?? '') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Datos del admin
$correo = "admin@senaparking.com";
$password = "Admin123";
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

echo "<h2>Intentando insertar usuario...</h2>";
echo "<p>Hash generado: " . htmlspecialchars($passwordHash) . "</p>";

// Intentar inserción directa con SQL raw
$sql = "INSERT INTO tb_usersys (correoUsys, passwordUsys, nombresUsys, apellidosUsys, rolUsys, estadoUsys) 
        VALUES ('$correo', '$passwordHash', 'Administrador', 'Sistema', 'admin', 'activo')";

echo "<p>SQL a ejecutar:</p>";
echo "<pre>" . htmlspecialchars($sql) . "</pre>";

if ($conn->query($sql) === TRUE) {
    echo "<h2 style='color: green;'>✅ Usuario creado exitosamente!</h2>";
    echo "<p><strong>Email:</strong> admin@senaparking.com</p>";
    echo "<p><strong>Password:</strong> Admin123</p>";
} else {
    echo "<h2 style='color: red;'>❌ Error: " . $conn->error . "</h2>";
    
    // Si hay error de duplicado, intentar actualizar
    if (strpos($conn->error, 'Duplicate') !== false) {
        echo "<p>El usuario ya existe, actualizando contraseña...</p>";
        $updateSql = "UPDATE tb_usersys SET passwordUsys = '$passwordHash', estadoUsys = 'activo' WHERE correoUsys = '$correo'";
        if ($conn->query($updateSql) === TRUE) {
            echo "<h2 style='color: green;'>✅ Contraseña actualizada!</h2>";
        } else {
            echo "<h2 style='color: red;'>❌ Error al actualizar: " . $conn->error . "</h2>";
        }
    }
}

// Verificar que se insertó
echo "<h2>Verificación final:</h2>";
$verifyResult = $conn->query("SELECT * FROM tb_usersys WHERE correoUsys = '$correo'");
if ($verifyResult && $verifyResult->num_rows > 0) {
    $user = $verifyResult->fetch_assoc();
    echo "<p style='color: green;'>✅ Usuario encontrado en la base de datos:</p>";
    echo "<pre>" . print_r($user, true) . "</pre>";
    
    // Verificar la contraseña
    if (password_verify($password, $user['passwordUsys'])) {
        echo "<p style='color: green; font-size: 20px;'>✅ ¡Contraseña verificada correctamente!</p>";
    } else {
        echo "<p style='color: red;'>❌ Error de verificación de contraseña</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Usuario NO encontrado después de la inserción</p>";
}

$conn->close();
?>

<hr>
<p style="color: red; font-weight: bold; font-size: 18px;">⚠️ ELIMINA ESTE ARCHIVO AHORA: insertar_admin.php</p>
<p><a href="login.php" style="font-size: 20px;">Ir al Login</a></p>
