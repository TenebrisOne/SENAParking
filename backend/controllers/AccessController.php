<?php

session_start();

// ✅ 1. Verificar que el usuario esté autenticado
if (!isset($_SESSION['id_userSys'])) {
    echo '<script type="text/javascript">';
    echo 'alert("Error: Usuario no autenticado. Inicia sesión.");';
    echo 'window.location.href = "../../frontend/views/login.php";'; // Ajusta la ruta si es necesario
    echo '</script>';
    exit();
}

$id_userSys = $_SESSION['id_userSys']; // ✅ Tomamos el ID del usuario autenticado

// ✅ 2. Mostrar errores solo durante desarrollo (quítalo en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ✅ 3. Cargar conexiones y modelos
include_once '../config/conexion.php';       // Crea la conexión $conn (MySQLi)
include_once '../models/Access.php';         // Modelo para registrar accesos
include_once '../models/ActividadModel.php'; // Modelo para registrar actividades

// ✅ 4. Instanciar modelos
$access = new Access($conn);
$actividadModel = new ActividadModel($conn);

// ✅ 5. Obtener datos del formulario (POST)
$id_vehiculo = isset($_POST['id_vehiculo']) ? $_POST['id_vehiculo'] : null;
$tipo_accion = isset($_POST['tipoAccionAcc']) ? $_POST['tipoAccionAcc'] : null;
$placa = isset($_POST['placa']) ? $_POST['placa'] : ''; // Asegúrate de que venga la placa si la usas

// ✅ 6. Validar que los datos requeridos estén presentes
if (!empty($id_vehiculo) && !empty($tipo_accion)) {

    // Asignar datos al objeto Access
    $access->id_vehiculo = $id_vehiculo;
    $access->tipo_accion = $tipo_accion;
    $access->fecha_hora = date('Y-m-d H:i:s');
    $access->id_userSys = $id_userSys;
    $access->espacio_asignado = rand(1, 200); // Puedes cambiar esto si se asigna de otra forma

    // Intentar crear el registro de acceso
    if ($access->create()) {

        // Mensaje de éxito
        $message = "Acceso registrado exitosamente para el vehículo ID: " . $id_vehiculo .
                ". Tipo: " . $tipo_accion .
                ". Espacio asignado: " . $access->espacio_asignado .
                ". Fecha: " . $access->fecha_hora;

        // Registrar actividad en el historial
        $actividadModel->registrarActividad($id_userSys, 'Otorgó acceso al vehículo ' . $placa);

        // Redirigir con mensaje
        echo '<script type="text/javascript">';
        echo 'alert("' . $message . '");';
        echo 'window.location.href="../../frontend/views/crud_vehiculos.php";';
        echo '</script>';
        exit();

    } else {
        // Error al guardar el acceso
        echo '<script type="text/javascript">';
        echo 'alert("Error: No se pudo registrar el acceso.");';
        echo 'window.history.back();';
        echo '</script>';
        exit();
    }

} else {
    // Datos incompletos
    echo '<script type="text/javascript">';
    echo 'alert("Error: Datos incompletos para registrar el acceso.");';
    echo 'window.history.back();';
    echo '</script>';
    exit();
}
