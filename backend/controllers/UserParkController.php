<?php
// Habilitar errores para depuración (eliminar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json; charset=UTF-8");

// ¡CORRECCIÓN AQUÍ! Cambiado de database.php a conexion.php
include_once '../config/conexion.php'; 
include_once '../models/UserParkModel.php';

$database = new Database();
$db = $database->getConnection();

$userPark = new UserPark($db);

$stmt = $userPark->readAll();
$num = $stmt->rowCount();

if ($num > 0) {
    $user_parks_arr = array();
    $user_parks_arr["data"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $user_park_item = array(
            "id_userPark" => $id_userPark,
            "nombres_park" => $nombres_park,
            "apellidos_park" => $apellidos_park,
            "nombre_completo" => $nombres_park . ' ' . $apellidos_park
        );
        array_push($user_parks_arr["data"], $user_park_item);
    }

    http_response_code(200);
    echo json_encode(array("success" => true, "data" => $user_parks_arr["data"]));
} else {
    http_response_code(404);
    echo json_encode(array("success" => false, "message" => "No se encontraron propietarios de parqueo."));
}
?>