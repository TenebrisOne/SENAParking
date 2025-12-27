<?php
/**
 * Script de diagnóstico para verificar usuarios en la base de datos
 * ELIMINAR DESPUÉS DE USAR
 */

require_once('backend/config/conexion.php');

echo "<h1>Diagnóstico de Base de Datos - SENAParking</h1>";

// Verificar conexión
if ($conn->connect_error) {
    die("<p style='color: red;'>Error de conexión: " . $conn->connect_error . "</p>");
}

echo "<p style='color: green;'>✅ Conexión exitosa a la base de datos</p>";

// Mostrar estructura de la tabla
echo "<h2>Estructura de la tabla tb_usersys:</h2>";
$descResult = $conn->query("DESCRIBE tb_usersys");
if ($descResult) {
    echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
    echo "<tr style='background: #333; color: white;'><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while ($row = $descResult->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Default'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>Error al obtener estructura: " . $conn->error . "</p>";
}

// Mostrar usuarios existentes
echo "<h2>Usuarios en la tabla tb_usersys:</h2>";
$usersResult = $conn->query("SELECT id_userSys, correoUsys, nombresUsys, rolUsys, estadoUsys FROM tb_usersys");
if ($usersResult) {
    if ($usersResult->num_rows > 0) {
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
        echo "<tr style='background: #333; color: white;'><th>ID</th><th>Correo</th><th>Nombre</th><th>Rol</th><th>Estado</th></tr>";
        while ($row = $usersResult->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id_userSys']) . "</td>";
            echo "<td>" . htmlspecialchars($row['correoUsys']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nombresUsys']) . "</td>";
            echo "<td>" . htmlspecialchars($row['rolUsys']) . "</td>";
            echo "<td>" . htmlspecialchars($row['estadoUsys']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: orange;'>⚠️ No hay usuarios en la tabla</p>";
    }
} else {
    echo "<p style='color: red;'>Error al consultar usuarios: " . $conn->error . "</p>";
}

// Buscar específicamente el admin
echo "<h2>Búsqueda específica del admin:</h2>";
$adminResult = $conn->query("SELECT * FROM tb_usersys WHERE correoUsys = 'admin@senaparking.com'");
if ($adminResult) {
    if ($adminResult->num_rows > 0) {
        $admin = $adminResult->fetch_assoc();
        echo "<p style='color: green;'>✅ Usuario admin encontrado:</p>";
        echo "<pre>" . print_r($admin, true) . "</pre>";
        
        // Verificar password
        echo "<h3>Verificación de contraseña:</h3>";
        $testPassword = "Admin123";
        if (password_verify($testPassword, $admin['passwordUsys'])) {
            echo "<p style='color: green;'>✅ La contraseña 'Admin123' es correcta</p>";
        } else {
            echo "<p style='color: red;'>❌ La contraseña 'Admin123' NO coincide con el hash almacenado</p>";
            echo "<p>Hash almacenado: " . htmlspecialchars($admin['passwordUsys']) . "</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Usuario admin@senaparking.com NO encontrado</p>";
    }
} else {
    echo "<p style='color: red;'>Error: " . $conn->error . "</p>";
}

$conn->close();
?>

<hr>
<p style="color: red; font-weight: bold;">⚠️ ELIMINA ESTE ARCHIVO DESPUÉS DE USAR</p>
<p><a href="crear_admin.php">Crear Admin</a> | <a href="login.php">Ir al Login</a></p>
