<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json; charset=UTF-8");

include_once '../config/conexion.php';
include_once '../models/Vehicle.php';

$database = new Database();
$db = $database->getConnection();

$vehicle = new Vehicle($db);

$search_term = isset($_GET['search']) ? $_GET['search'] : '';

$stmt = $vehicle->read($search_term);
$num = $stmt->rowCount();

if ($num > 0) {
    $vehicles_arr = array();
    $vehicles_arr["data"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $vehicle_item = array(
            "id_vehiculo" => $id_vehiculo,
            "id_userPark" => $id_userPark,
            "placa" => $placa,
            "tipo" => $tipo,
            "modelo" => $modelo,
            "color" => $color,
            "propietario_nombre_completo" => $nombres_park . ' ' . $apellidos_park,
        );
        array_push($vehicles_arr["data"], $vehicle_item);
    }

    http_response_code(200);
    echo json_encode(array("success" => true, "data" => $vehicles_arr["data"]));
} else {
    http_response_code(404);
    echo json_encode(array("success" => false, "message" => "No se encontraron vehículos."));
}
?>