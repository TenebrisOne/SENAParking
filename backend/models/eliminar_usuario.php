<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "senaparking_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id_userSys = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM tb_usersys WHERE id_userSys = ?");
    $stmt->bind_param("s", $id_userSys);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Usuario eliminado correctamente"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al eliminar usuario"]);
    }
}

$conn->close();
?>
