<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST["correo"];
    $password = $_POST["password"];

    $conn = new mysqli("localhost", "root", "", "senaparking_db");

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();
        // Verifica la contraseña con password_verify
        if (password_verify($password, $usuario['password'])) {
            $_SESSION['correo'] = $correo;
            header("Location: /frontend/views/dashboard_admin.php");
            exit();
        } else {
            header("Location: login.html?error=1"); // contraseña incorrecta
            exit();
        }
    } else {
        header("Location: login.html?error=1"); // usuario no encontrado
        exit();
    }

    $conn->close();
}
?>
