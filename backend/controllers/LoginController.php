<?php
// Asegurar que la sesión esté iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Capturar errores
error_reporting(E_ALL);
ini_set('display_errors', 0); // No mostrar errores en la respuesta

require_once('../config/conexion.php');

// Verificar conexión a BD
if ($conn->connect_error) {
    echo "Error de conexión a la base de datos";
    exit;
}

require_once('../models/ActividadModel.php');
require_once('../models/LoginModel.php');
$actividadModel = new ActividadModel($conn);


$loginModel = new login($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['correo'])) {
    $registro = $loginModel->validar_login(
        $_POST['correo'],
        $_POST['password']
    );

    switch ($registro) {
        case "activo":
            $actividadModel->registrarActividad($_SESSION['id_userSys'], 'Inicio de sesión');
            echo ($_SESSION['rol']);
            exit();
        case "inactivo":
            echo ("Usuario inactivo. Contacta al administrador.");
            break;
        case "errocontra":
            echo ("Contraseña incorrecta.");
            break;
        case "Nousuario":
            echo ("Usuario no encontrado.");
            break;
        default:
            echo ("Error al iniciar sesión. Inténtalo de nuevo.");
            break;
    }
    exit;
}
