<?php
// backend/controllers/GeneralReportController.php

header('Content-Type: application/json'); // Indicar que la respuesta es JSON

include_once '../config/conexion.php';
include_once '../models/ReportAccess.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$database = new DataBase();
$db = $database->getConnection();

$access = new Access($db);

$startDate = isset($_GET['startDate']) && $_GET['startDate'] !== '' ? $_GET['startDate'] . ' 00:00:00' : null;
$endDate = isset($_GET['endDate']) && $_GET['endDate'] !== '' ? $_GET['endDate'] . ' 23:59:59' : null;

// Si no se especifican fechas, usar un rango por defecto (ej. últimos 30 días o todo el historial)
if (!$startDate && !$endDate) {
    $endDate = date('Y-m-d 23:59:59'); // Hasta hoy
    $startDate = date('Y-m-d 00:00:00', strtotime('-30 days')); // Desde hace 30 días
} elseif (!$startDate) {
    $startDate = date('Y-m-d 00:00:00', strtotime($endDate . ' -30 days'));
} elseif (!$endDate) {
    $endDate = date('Y-m-d 23:59:59');
}

$response_data = [
    "success" => false,
    "generalStats" => [],
    "vehicleTypeStats" => [
        'Automovil' => ['ingresos' => 0, 'salidas' => 0], // Asegurar existencia
        'Motocicleta' => ['ingresos' => 0, 'salidas' => 0],
        'Bicicleta' => ['ingresos' => 0, 'salidas' => 0]
    ],
    "vehicleTypeStatsRaw" => [], // Para la gráfica, sin los ceros por defecto
    "dailyAccessData" => [],
    "hourlyAccessData" => []
];

try {
    // 1. Métricas Generales (Ingresos, Salidas, Ocupación)
    $response_data['generalStats'] = $access->getGeneralAccessStats($startDate, $endDate);
    //$response_data['generalStats']['capacidad_total'] = $access->getParkingCapacity();
    //$response_data['generalStats']['ocupacion_actual'] = $access->getCurrentOccupancyCount();

    // 2. Estadísticas por Tipo de Vehículo
    $vehicleTypeStatsRaw = $access->getVehicleTypeAccessStats($startDate, $endDate);
    $response_data['vehicleTypeStatsRaw'] = $vehicleTypeStatsRaw; // Guarda los datos tal cual vienen
    
    foreach ($vehicleTypeStatsRaw as $item) {
        $response_data['vehicleTypeStats'][$item['tipoVeh']] = [
            'ingresos' => $item['ingresos'],
            'salidas' => $item['salidas']
        ];
    }
    
    // 3. Actividad Diaria
    $response_data['dailyAccessData'] = $access->getDailyAccessStats($startDate, $endDate);

    // 4. Actividad por Hora
    $response_data['hourlyAccessData'] = $access->getHourlyAccessStats($startDate, $endDate);

    $response_data['success'] = true;

} catch (PDOException $e) {
    error_log("Error PDO en GeneralReportController: " . $e->getMessage());
    http_response_code(500);
    $response_data['message'] = "Error en la base de datos: " . $e->getMessage();
} catch (Exception $e) {
    error_log("Error general en GeneralReportController: " . $e->getMessage());
    http_response_code(500);
    $response_data['message'] = "Error interno del servidor: " . $e->getMessage();
}

echo json_encode($response_data);
?>