<?php

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "senaparking_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibir datos del formulario
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$tipo_documento = $_POST['tipdoc'];
$documento = $_POST['documento'];
$rol = $_POST['rol'];
$correo = $_POST['correo'];
$numero_contacto = $_POST['numero'];
$nombre_usuario = $_POST['usuario'];
$contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

// Insertar datos en la base de datos
$stmt = $conn->prepare("INSERT INTO tb_usersys (id_rol, tipo_documento, numero_documento, nombres_sys, apellidos_sys, numero_contacto, username, correo, password) VALUES (?,?,?,?,?,?,?,?,?)");
$stmt->bind_param("sssssssss", $rol, $tipo_documento, $documento, $nombre, $apellido, $numero_contacto, $nombre_usuario, $correo, $contrasena);

if ($stmt->execute()) {
    echo "<script>
        alert('Usuario registrado exitosamente');
        window.location.href = '/SENAParking/frontend/views/dashboard_admin.html'; // Redirige de vuelta al formulario
    </script>";
} else {
    echo "<script>
        alert('Error al registrar el usuario');
        window.history.back(); // Retorna a la página anterior
    </script>";
}

$conn->close();
?>