<?php
require_once('../config/conexion.php');
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
            $actividadModel->registrarActividad($_SESSION['id_userSys'], 'Inicio de sesion');
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
