<?php
header("Content-Type: application/json"); // Asegurar respuesta en formato JSON

// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "senaparking_db";

// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Error de conexión: " . $conn->connect_error]));
}

// Verificar si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si los datos llegaron correctamente
    if (!isset($_POST['id_userSys']) || empty($_POST['id_userSys'])) {
        echo json_encode(["status" => "error", "message" => "ID de usuario no proporcionado"]);
        exit();
    }

    // Capturar los datos del formulario
    $id_userSys = $_POST['id_userSys'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $documento = $_POST['documento'];
    $rol = $_POST['rol'];
    $correo = $_POST['correo'];
    $numero_contacto = $_POST['numero'];
    $nombre_usuario = $_POST['usuario'];

    // Debug: Imprimir los datos recibidos para verificar que llegan correctamente
    error_log("Datos recibidos en editar_usuario.php: ID = " . $id_userSys . ", Nombre = " . $nombre);

    // Preparar la consulta SQL
    $stmt = $conn->prepare("UPDATE tb_usersys SET nombres_sys=?, apellidos_sys=?, numero_documento=?, id_rol=?, correo=?, numero_contacto=?, username=? WHERE id_userSys=?");

    // Validar tipos de datos antes de bind_param
    if (!is_numeric($id_userSys) || !is_numeric($rol)) {
        echo json_encode(["status" => "error", "message" => "Error: Datos inválidos en el formulario"]);
        exit();
    }

    // Enlazar parámetros y ejecutar la consulta
    $stmt->bind_param("sssssssi", $nombre, $apellido, $documento, $rol, $correo, $numero_contacto, $nombre_usuario, $id_userSys);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Usuario actualizado correctamente"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error SQL: " . $stmt->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
}

$conn->close();
?>
