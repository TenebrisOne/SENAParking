<?php
session_start();
require_once './backend/config/conexion.php';

// Conexión a la base de datos
require_once __DIR__ . '/./backend/models/ActividadModel.php';
$actividadModel = new ActividadModel($conn);

if (isset($_SESSION['correo'])) {
    // Registrar actividad de logout
    $correo = $_SESSION['correo'];

    // Buscar ID del usuario por correo
    $stmt = $conn->prepare("SELECT id_userSys FROM tb_userSys WHERE correoUsys = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        $actividadModel->registrarActividad($usuario['id_userSys'], 'Cierre de sesión');
    }
}

// Destruir sesión
session_unset();
session_destroy();

// Redirigir al login
header("Location: index.php");
exit();
?>