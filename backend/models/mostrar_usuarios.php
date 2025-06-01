<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "senaparking_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consultar usuarios de la base de datos
$sql = "SELECT id_userSys, username, id_rol FROM tb_usersys";
$result = $conn->query($sql);

$usuarios = [];

while ($row = $result->fetch_assoc()) {
    $usuarios[] = $row;
}

// Convertir los datos en JSON para que JavaScript pueda usarlos
echo json_encode($usuarios);

$conn->close();
?>
