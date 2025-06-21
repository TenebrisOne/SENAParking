<?php
require_once '../config/conexion.php';
require_once '../models/ActividadModel.php';

session_start();
$usuario = $_SESSION['usuario']; // Asegúrate de tener el usuario en sesión

$actividadModel = new ActividadModel($conn);

// Obtener la acción desde la URL o formulario
$accion = $_GET['accion'] ?? $_POST['accion'] ?? '';

switch ($accion) {
    case 'consulta_individual':
        $actividadModel->registrarActividad($usuario['id_userSys'], 'Consulta de reportes individuales');
        include '../views/reporte_individual.php';
        break;

    case 'consulta_general':
        $actividadModel->registrarActividad($usuario['id_userSys'], 'Consulta de reportes generales');
        include '../views/reporte_general.php';
        break;

    case 'crear_vehiculo':
        $actividadModel->registrarActividad($usuario['id_userSys'], 'Registro de vehiculo');
        include '../views/crear_vehiculo.php';
        break;

    case 'crear_userSys':
        $actividadModel->registrarActividad($usuario['id_userSys'], 'Registro de usuario del sistema');
        include './../views/crear_userSys.php';
        break;

    case 'crear_userPark':
        $actividadModel->registrarActividad($usuario['id_userSys'], 'Registro de un usuario del parqueadero');
        include '../views/crear_userPark.php';
        break;

    case 'editar_userSys':
        $actividadModel->registrarActividad($usuario['id_userSys'], 'Edicion de un usuario del sistema');
        include '../views/editar_userSys.php';
        break;

    case 'editar_vehiculo':
        $actividadModel->registrarActividad($usuario['id_userSys'], 'Edicion de un vehiculo');
        include '../views/editar_vehiculo.php';
        break;

    default:
        echo "Acción no válida.";
        break;
}
?>
