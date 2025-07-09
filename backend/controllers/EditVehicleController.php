<?php
// Habilitar errores para depuración (eliminar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../config/conexion.php'; 
include_once '../models/VehicleRegisterModel.php';

$database = new Database();
$db = $database->getConnection();

$vehicle = new Vehicle($db);

// --- Lógica para manejar solicitudes GET (para cargar datos del vehículo) ---
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $vehicle->id_vehiculo = isset($_GET['id']) ? $_GET['id'] : die(); // Obtener el ID del vehículo de la URL

    if ($vehicle->readOne()) {
        // Preparar los datos del vehículo para la respuesta JSON
        $vehicle_arr = array(
            "id_vehiculo" => $vehicle->id_vehiculo,
            "id_userPark" => $vehicle->id_userPark,
            "placa" => $vehicle->placa,
            "tipo" => $vehicle->tipo,
            "modelo" => $vehicle->modelo,
            "color" => $vehicle->color,
            "propietario_nombres" => $vehicle->nombres_park,
            "propietario_apellidos" => $vehicle->apellidos_park
        );

        http_response_code(200);
        echo json_encode(array("success" => true, "data" => $vehicle_arr));
    } else {
        http_response_code(404);
        echo json_encode(array("success" => false, "message" => "Vehículo no encontrado."));
    }
    exit(); // Detener la ejecución después de responder a la solicitud GET
}

// --- Lógica para manejar solicitudes POST (para actualizar datos del vehículo) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario (incluido el ID oculto)
    $vehicle->id_vehiculo = isset($_POST['id_vehiculo']) ? $_POST['id_vehiculo'] : '';
    $vehicle->placa = isset($_POST['placa']) ? $_POST['placa'] : '';
    $vehicle->tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
    $vehicle->id_userPark = isset($_POST['propietario']) ? $_POST['propietario'] : '';
    $vehicle->color = isset($_POST['color']) ? $_POST['color'] : '';
    $vehicle->modelo = isset($_POST['modelo']) ? $_POST['modelo'] : '';

    // Validación básica del lado del servidor
    if (
        !empty($vehicle->id_vehiculo) &&
        !empty($vehicle->placa) &&
        !empty($vehicle->tipo) &&
        !empty($vehicle->id_userPark) &&
        !empty($vehicle->color) &&
        !empty($vehicle->modelo)
    ) {
        if ($vehicle->update()) {
            echo '<script type="text/javascript">';
            echo 'alert("Vehículo actualizado con éxito.");';
            echo 'window.location.href="../../frontend/views/crud_vehiculos.html";'; // Redirige de vuelta a la lista
            echo '</script>';
            exit(); 
        } else {
            echo '<script type="text/javascript">';
            echo 'alert("Error al actualizar el vehículo. La placa podría ya existir o hay un problema con la base de datos.");';
            echo 'window.location.href="../../frontend/views/crud_vehiculos.html";'; // Redirige de vuelta a la lista
            echo '</script>';
            exit(); 
        }
    } else {
        echo '<script type="text/javascript">';
        echo 'alert("Error: Por favor, complete todos los campos obligatorios para actualizar.");';
        echo 'window.location.href="../../frontend/views/crud_vehiculos.html";'; // Redirige de vuelta a la lista
        echo '</script>';
        exit(); 
    }
}
?>