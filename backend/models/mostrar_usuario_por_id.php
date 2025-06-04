<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "senaparking_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Conexión fallida"]));
}

if (isset($_GET['id'])) {
    $id_userSys = $_GET['id'];

    // Debug: Verificar qué ID está llegando
    error_log("ID recibido: " . $id_userSys);

    $stmt = $conn->prepare("SELECT * FROM tb_usersys WHERE id_userSys = ?");
    $stmt->bind_param("i", $id_userSys);
    $stmt->execute();
    
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        echo json_encode($row);
    } else {
        echo json_encode(["status" => "error", "message" => "Usuario no encontrado"]);
    }
}

$conn->close();
?>
