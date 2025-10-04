<?php

session_start();

$_SESSION['id_userSys'] = $usuario['id_userSys'];

// Mostrar errores (quitar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexiones y modelos
include_once '../config/conexion.php'; // Crea $conn (MySQLi)
include_once '../models/Access.php';
include_once '../models/ActividadModel.php';

// Crear instancias de los modelos con mysqli
$access = new Access($conn);
$actividadModel = new ActividadModel($conn);

// --- INICIO DE LA MODIFICACIÓN PARA id_userSys ---

// Obtener id_userSys desde la base de datos
$id_userSys = null;
$query_user = "SELECT id_userSys FROM tb_usersys LIMIT 1";
$result_user = $conn->query($query_user);

if ($result_user && $result_user->num_rows > 0) {
    $row_user = $result_user->fetch_assoc();
    $id_userSys = $row_user['id_userSys'];
    $_SESSION['id_userSys'] = $id_userSys;
} else {
    echo '<script type="text/javascript">';
    echo 'alert("Error: No se encontró ningún usuario en tb_usersys.");';
    echo 'window.history.back();';
    echo '</script>';
    exit();
}

// --- FIN DE LA MODIFICACIÓN PARA id_userSys ---

// Se asume que los datos vienen de un formulario POST o de una solicitud AJAX con FormData
$id_vehiculo = isset($_POST['id_vehiculo']) ? $_POST['id_vehiculo'] : null;
$tipo_accion = isset($_POST['tipo_accion']) ? $_POST['tipo_accion'] : null;


if (!empty($id_vehiculo) && !empty($tipo_accion)) {
    $access->id_vehiculo = $id_vehiculo;
    $access->tipo_accion = $tipo_accion;
    $access->fecha_hora = date('Y-m-d H:i:s');
    $access->id_userSys = $id_userSys; // Asignamos el id_userSys obtenido
    $access->espacio_asignado = rand(1, 200); // Esto puede seguir siendo aleatorio si no es una FK

    if ($access->create()) {
        $message = "Acceso registrado exitosamente para el vehículo ID: " . $id_vehiculo . ". Tipo: " . $tipo_accion . ". Espacio asignado: " . $access->espacio_asignado . ". Fecha: " . $access->fecha_hora;
        $actividadModel->registrarActividad($_SESSION['id_userSys'], 'Otorgó acceso al vehículo ' . $placa);

        echo '<script type="text/javascript">';
        echo 'alert("' . $message . '");';
        echo 'window.location.href="../../frontend/views/crud_vehiculos.php";';
        echo '</script>';
        exit();
    } else {
        echo '<script type="text/javascript">';
        echo 'alert("Error: No se pudo registrar el acceso.");';
        echo 'window.history.back();';
        echo '</script>';
        exit();
    }
} else {
    echo '<script type="text/javascript">';
    echo 'alert("Error: Datos incompletos para registrar el acceso.");';
    echo 'window.history.back();';
    echo '</script>';
    exit();
}
