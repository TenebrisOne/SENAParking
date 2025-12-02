<?php
/**
 * Script de Preparación de Datos para Pruebas de Rendimiento
 * Crea usuarios y datos necesarios en la base de datos de prueba
 */

// Configuración de base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "senaparking_test";

echo "========================================\n";
echo "Preparando Datos para Pruebas de Carga\n";
echo "========================================\n\n";

// Conectar a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error . "\n");
}

echo "✅ Conectado a la base de datos: $dbname\n\n";

// 1. Crear usuario de prueba para login
echo "[1/3] Creando usuario de prueba para login...\n";

$hashedPassword = password_hash('Test123456', PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO tb_usersys (nombresUsys, apellidosUsys, tipoDocumentoUsys, numeroDocumentoUsys, rolUsys, correoUsys, usernameUsys, passwordUsys, estadoUsys) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) 
                        ON DUPLICATE KEY UPDATE passwordUsys = ?");

$nombres = 'Test';
$apellidos = 'User';
$tipoDoc = 'CC';
$numDoc = 'PERF001';
$rol = 'guardia';
$correo = 'testuser@sena.edu.co';
$username = 'testuser';
$estado = 'activo';

$stmt->bind_param("ssssssssss", $nombres, $apellidos, $tipoDoc, $numDoc, $rol, $correo, $username, $hashedPassword, $estado, $hashedPassword);

if ($stmt->execute()) {
    echo "   ✅ Usuario de prueba creado: $correo / Test123456\n";
} else {
    echo "   ⚠️  Usuario ya existe o error: " . $stmt->error . "\n";
}

// 2. Crear usuario de parqueadero para vehículos
echo "\n[2/3] Creando usuario de parqueadero...\n";

$stmt = $conn->prepare("INSERT INTO tb_userpark (tipoUserUpark, tipoDocumentoUpark, numeroDocumentoUpark, nombresUpark, apellidosUpark, edificioUpark) 
                        VALUES (?, ?, ?, ?, ?, ?) 
                        ON DUPLICATE KEY UPDATE nombresUpark = ?");

$tipo = 'Estudiante';
$tipoDoc = 'CC';
$numDoc = 'PERFPARK001';
$nombre = 'Performance';
$apellido = 'Test';
$edificio = 'Edificio A';

$stmt->bind_param("sssssss", $tipo, $tipoDoc, $numDoc, $nombre, $apellido, $edificio, $nombre);

if ($stmt->execute()) {
    echo "   ✅ Usuario de parqueadero creado\n";
    
    // Obtener ID del usuario creado
    $result = $conn->query("SELECT id_userPark FROM tb_userpark WHERE numeroDocumentoUpark = 'PERFPARK001'");
    if ($row = $result->fetch_assoc()) {
        $userParkId = $row['id_userPark'];
        echo "   📝 ID de usuario parqueadero: $userParkId\n";
        
        // Actualizar payload de vehículo con el ID correcto
        $vehiclePayload = "propietario=$userParkId&placa=ABC123&tarjeta_propiedad=TP123456&tipo=Automóvil&modelo=2024&color=Negro";
        file_put_contents(__DIR__ . '/payloads/register-vehicle.txt', $vehiclePayload);
        echo "   ✅ Payload de vehículo actualizado con ID: $userParkId\n";
    }
} else {
    echo "   ⚠️  Usuario ya existe o error: " . $stmt->error . "\n";
}

// 3. Limpiar registros antiguos de pruebas
echo "\n[3/3] Limpiando registros antiguos de pruebas...\n";

$conn->query("DELETE FROM tb_accesos WHERE id_vehiculo IN (SELECT id_vehiculo FROM tb_vehiculos WHERE placaVeh LIKE 'PERF%')");
$conn->query("DELETE FROM tb_vehiculos WHERE placaVeh LIKE 'PERF%'");
$conn->query("DELETE FROM tb_actividades WHERE accionActi LIKE '%LoadTest%'");

echo "   ✅ Registros antiguos limpiados\n";

$conn->close();

echo "\n========================================\n";
echo "✅ Preparación completada!\n";
echo "========================================\n\n";
echo "Datos de prueba:\n";
echo "  - Usuario Login: testuser@sena.edu.co / Test123456\n";
echo "  - Usuario Parqueadero: Performance Test (ID: $userParkId)\n";
echo "\nYa puedes ejecutar las pruebas de carga.\n";
