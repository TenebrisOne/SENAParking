<?php
echo "El archivo PHP se está ejecutando"; 
// Conexión a la base de datos
$servername = "localhost";
$username = "root"; // Cambia esto si es necesario
$password = ""; // Cambia esto si es necesario
$dbname = "senaparking_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibir datos del formulario
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$tipdoc = $_POST['tipdoc'];
$documento = $_POST['documento'];
$rol = $_POST['rol'];
$correo = $_POST['correo'];
$numero_contacto = $_POST['numero'];
$nombre_usuario = $_POST['usuario'];
$contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // Encriptar contraseña

// Insertar datos en la base de datos
$stmt = $conn->prepare("INSERT INTO tb_userSys (id_rol, tipo_documento, numero_documento, nombres_sys, apellidos_sys, numero_contacto, username, correo, password) Values (?,?,?,?,?,?,?,?)");
$stmt->bind_param("ssssssss", $rol, $tipdoc, $documento, $nombre, $apellido, $numero_contacto, $nombre_usuario, $correo, $contrasena);
$stmt->execute();
$result = $stmt->get_result();

if ($conn->query($sql) === TRUE) {
    echo "Registro exitoso";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
