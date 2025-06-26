<?php
// backend/controllers/UserDetailReportController.php

header('Content-Type: application/json'); // Indicar que la respuesta es JSON

include_once '../config/conexion.php';
include_once '../models/ReportesUserPark.php';
include_once '../models/ReportAccess.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$database = new DataBase();
$db = $database->getConnection();

$userPark = new UserPark($db);
$access = new Access($db);

$userId = isset($_GET['id']) ? intval($_GET['id']) : 0;

$response_data = [
    "success" => false,
    "user" => null,
    "accessStats" => ['ingresos' => 0, 'salidas' => 0],
    "userAccesses" => []
];

if ($userId > 0) {
    $userPark->id_userPark = $userId;
    if ($userPark->readOne()) {
        $response_data['user'] = [
            "id_userPark" => $userPark->id_userPark,
            "tipo_user" => $userPark->tipo_user,
            "tipo_documento" => $userPark->tipo_documento,
            "numero_documento" => $userPark->numero_documento,
            "nombres_park" => $userPark->nombres_park,
            "apellidos_park" => $userPark->apellidos_park,
            "edificio" => $userPark->edificio,
            "numero_contacto" => $userPark->numero_contacto
        ];

        $response_data['accessStats'] = $access->getUserAccessStats($userId);
        
        $stmtAccesses = $access->getUserAccesses($userId);
        $userAccesses = [];
        while ($rowAccess = $stmtAccesses->fetch(PDO::FETCH_ASSOC)) {
            $userAccesses[] = $rowAccess;
        }
        $response_data['userAccesses'] = $userAccesses;
        $response_data['success'] = true;

    } else {
        http_response_code(404); // Not Found
        $response_data['message'] = "Usuario no encontrado.";
    }
} else {
    http_response_code(400); // Bad Request
    $response_data['message'] = "ID de usuario no proporcionado o inválido.";
}

echo json_encode($response_data);
?>