<?php
// Habilitar errores para depuración (eliminar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ¡CORRECCIÓN AQUÍ! Cambiado de database.php a conexion.php
include_once '../config/conexion.php'; 
include_once '../models/VehicleRegisterModel.php';

$database = new Database();
$db = $database->getConnection();

$vehicle = new Vehicle($db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicle->placa = isset($_POST['placa']) ? $_POST['placa'] : '';
    $vehicle->tarjeta_propiedad = isset($_POST['tarjeta_propiedad']) ? $_POST['tarjeta_propiedad'] : '';
    $vehicle->tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
    $vehicle->id_userPark = isset($_POST['propietario']) ? $_POST['propietario'] : ''; 
    $vehicle->color = isset($_POST['color']) ? $_POST['color'] : '';
    $vehicle->modelo = isset($_POST['modelo']) ? $_POST['modelo'] : '';
    // $vehicle->observaciones = isset($_POST['observaciones']) ? $_POST['observaciones'] : ''; // ¡ELIMINADA!

    if (
        !empty($vehicle->placa) &&
        !empty($vehicle->tarjeta_propiedad) &&
        !empty($vehicle->tipo) &&
        !empty($vehicle->id_userPark) &&
        !empty($vehicle->color) &&
        !empty($vehicle->modelo)
    ) {
        if ($vehicle->create()) {
            $actividadModel->registrarActividad($usuario['id_userSys'], 'Registró un vehículo');
            echo '<script type="text/javascript">';
            echo 'alert("Vehículo registrado con éxito.");';
            echo 'window.location.href="../../frontend/views/crud_vehiculos.php";';
            echo '</script>';
            exit();
        } else {
            echo '<script type="text/javascript">';
            echo 'alert("Error al registrar el vehículo. La placa podría ya existir o hay un problema con la base de datos.");';
            echo 'window.location.href="../../frontend/views/reg_vehiculos.php";';
            echo '</script>';
            exit();
        }
    } else {
        echo '<script type="text/javascript">';
        echo 'alert("Error: Por favor, complete todos los campos obligatorios.");';
        echo 'window.location.href="../../frontend/views/reg_vehiculos.php";';
        echo '</script>';
        exit();
    }
} else {
    echo '<script type="text/javascript">';
    echo 'alert("Acceso no permitido. Este script solo acepta solicitudes POST.");';
    echo 'window.location.href="../../frontend/views/crud_vehiculos.php";';
    echo '</script>';
    exit();
}
?>