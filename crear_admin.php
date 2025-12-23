<?php
/**
 * Script para crear un usuario administrador de prueba
 * ELIMINAR ESTE ARCHIVO DESPUÉS DE USARLO
 */

require_once('backend/config/conexion.php');

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Datos del usuario administrador
$correo = "admin@senaparking.com";
$password = "Admin123"; // La contraseña en texto plano
$passwordHash = password_hash($password, PASSWORD_DEFAULT); // Hash de la contraseña
$nombres = "Administrador";
$apellidos = "Sistema";
$rol = "admin";
$estado = "activo";

// Verificar si el usuario ya existe
$checkSql = "SELECT id_userSys FROM tb_userSys WHERE correoUsys = ?";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("s", $correo);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    // Si existe, actualizar la contraseña
    $updateSql = "UPDATE tb_userSys SET passwordUsys = ?, estadoUsys = ? WHERE correoUsys = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("sss", $passwordHash, $estado, $correo);
    
    if ($updateStmt->execute()) {
        echo "<h2 style='color: green;'>✅ Usuario actualizado exitosamente!</h2>";
        echo "<p><strong>Correo:</strong> $correo</p>";
        echo "<p><strong>Nueva contraseña:</strong> $password</p>";
        echo "<p><strong>Estado:</strong> $estado</p>";
    } else {
        echo "<h2 style='color: red;'>❌ Error al actualizar: " . $updateStmt->error . "</h2>";
    }
    $updateStmt->close();
} else {
    // Si no existe, crear el usuario
    $insertSql = "INSERT INTO tb_userSys (correoUsys, passwordUsys, nombresUsys, apellidosUsys, rolUsys, estadoUsys) 
                  VALUES (?, ?, ?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertSql);
    
    if ($insertStmt === false) {
        // Si la tabla tiene campos diferentes, mostrar la estructura
        echo "<h2 style='color: orange;'>⚠️ Error al preparar la consulta</h2>";
        echo "<p>Error: " . $conn->error . "</p>";
        echo "<h3>Estructura de la tabla tb_userSys:</h3>";
        
        $descResult = $conn->query("DESCRIBE tb_userSys");
        if ($descResult) {
            echo "<table border='1' cellpadding='10'>";
            echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th></tr>";
            while ($row = $descResult->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['Field'] . "</td>";
                echo "<td>" . $row['Type'] . "</td>";
                echo "<td>" . $row['Null'] . "</td>";
                echo "<td>" . $row['Key'] . "</td>";
                echo "<td>" . $row['Default'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: red;'>No se pudo obtener la estructura de la tabla: " . $conn->error . "</p>";
        }
    } else {
        $insertStmt->bind_param("ssssss", $correo, $passwordHash, $nombres, $apellidos, $rol, $estado);
        
        if ($insertStmt->execute()) {
            echo "<h2 style='color: green;'>✅ Usuario administrador creado exitosamente!</h2>";
            echo "<p><strong>Correo:</strong> $correo</p>";
            echo "<p><strong>Contraseña:</strong> $password</p>";
            echo "<p><strong>Rol:</strong> $rol</p>";
        } else {
            echo "<h2 style='color: red;'>❌ Error al crear usuario: " . $insertStmt->error . "</h2>";
        }
        $insertStmt->close();
    }
}

$checkStmt->close();
$conn->close();
?>

<hr>
<p style="color: red; font-weight: bold;">⚠️ IMPORTANTE: Elimina este archivo después de crear el usuario!</p>
<p><a href="login.php">Ir al Login</a></p>
