<?php
require_once '../lib/dompdf/autoload.inc.php'; 

use Dompdf\Dompdf;
use Dompdf\Options;

include_once '../config/conexion.php';
include_once '../models/ReportAccess.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$database = new DataBase();
$db = $database->getConnection();

$access = new Access($db);

$startDate = isset($_GET['startDate']) && $_GET['startDate'] !== '' ? $_GET['startDate'] . ' 00:00:00' : null;
$endDate = isset($_GET['endDate']) && $_GET['endDate'] !== '' ? $_GET['endDate'] . ' 23:59:59' : null;

if (!$startDate && !$endDate) {
    $endDate = date('Y-m-d 23:59:59'); 
    $startDate = date('Y-m-d 00:00:00', strtotime('-30 days')); 
} elseif (!$startDate) {
    $startDate = date('Y-m-d 00:00:00', strtotime($endDate . ' -30 days'));
} elseif (!$endDate) {
    $endDate = date('Y-m-d 23:59:59');
}

$startDatePDF = $startDate ? date('Y-m-d', strtotime($startDate)) : '';
$endDatePDF = $endDate ? date('Y-m-d', strtotime($endDate)) : '';

$dateRangeText = "";
if ($startDatePDF && $endDatePDF) {
    $dateRangeText = " (" . $startDatePDF . " a " . $endDatePDF . ")";
} elseif ($startDatePDF) {
    $dateRangeText = " (Desde " . $startDatePDF . ")";
} elseif ($endDatePDF) {
    $dateRangeText = " (Hasta " . $endDatePDF . ")";
}

$generalStats = $access->getGeneralAccessStats($startDate, $endDate);
$capacidad_total = $access->getParkingCapacity();
$ocupacion_actual = $access->getCurrentOccupancyCount();
$generalStats['capacidad_total'] = $capacidad_total;
$generalStats['ocupacion_actual'] = $ocupacion_actual;

$vehicleTypeStatsRaw = $access->getVehicleTypeAccessStats($startDate, $endDate);
$vehicleTypeStatsForCards = [
    'Motocicleta' => ['ingresos' => 0, 'salidas' => 0],
    'Bicicleta' => ['ingresos' => 0, 'salidas' => 0],
    'Automovil' => ['ingresos' => 0, 'salidas' => 0], 
];
foreach ($vehicleTypeStatsRaw as $item) {
    $vehicleTypeStatsForCards[$item['tipo']] = [
        'ingresos' => $item['ingresos'],
        'salidas' => $item['salidas']
    ];
}


$dailyAccessData = $access->getDailyAccessStats($startDate, $endDate);
$hourlyAccessData = $access->getHourlyAccessStats($startDate, $endDate);

$reportTitle = "Reporte General del Parqueadero";
$ocupacion_porcentaje = ($generalStats['capacidad_total'] > 0) ?
    number_format(($generalStats['ocupacion_actual'] / $generalStats['capacidad_total']) * 100, 2) . '%' : 'N/A';

$pdfHtml = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>' . $reportTitle . $dateRangeText . '</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; font-size: 10pt; }
        h1 { color: #333; text-align: center; margin-bottom: 5px; }
        .date-range { text-align: center; font-size: 10pt; color: #666; margin-bottom: 20px; }
        .metric-grid { display: table; width: 100%; border-collapse: collapse; margin-top: 20px; }
        .metric-item { display: table-cell; width: 33%; padding: 10px; border: 1px solid #ddd; text-align: center; }
        .metric-item h5 { margin: 0 0 5px 0; font-size: 11pt; color: #555; }
        .metric-item .value { font-size: 18pt; font-weight: bold; color: #333; }
        .metric-item .sub-value { font-size: 9pt; color: #777; margin-top: 5px; }
        .section-title { font-weight: bold; margin-top: 25px; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 5px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 9pt; }
        th { background-color: #f2f2f2; }
        .chart-note { text-align: center; font-size: 9pt; color: #888; margin-top: 10px; }
        /* Colores de las métricas principales para los íconos (simulados) */
        .text-success { color: #28a745; }
        .text-danger { color: #dc3545; }
        .text-info { color: #17a2b8; }
        .text-warning { color: #ffc107; }
        .text-secondary { color: #6c757d; }
    </style>
</head>
<body>
    <h1>' . $reportTitle . '</h1>
    <p class="date-range">' . $dateRangeText . '</p>

    <div class="metric-grid">
        <div class="metric-item">
            <p><span class="text-success">&#9660;</span></p>
            <h5>Vehículos Ingresados</h5>
            <div class="value">' . htmlspecialchars($generalStats['total_ingresos'] ?? 0) . '</div>
        </div>
        <div class="metric-item">
            <p><span class="text-danger">&#9650;</span></p>
            <h5>Vehículos Salidos</h5>
            <div class="value">' . htmlspecialchars($generalStats['total_salidas'] ?? 0) . '</div>
        </div>
        <div class="metric-item">
            <p><span class="text-info">&#9679;</span></p>
            <h5>Ocupación Actual</h5>
            <div class="value">' . htmlspecialchars($ocupacion_porcentaje) . '</div>
            <p class="sub-value">(' . htmlspecialchars($generalStats['ocupacion_actual'] ?? 0) . ' de ' . htmlspecialchars($generalStats['capacidad_total'] ?? 0) . ' espacios)</p>
        </div>
    </div>

    <div class="metric-grid" style="margin-top: 10px;">
        <div class="metric-item" style="width: 50%;">
            <p><span class="text-warning">&#9999;</span></p>
            <h5>Ingresos de Motos</h5>
            <div class="value">' . htmlspecialchars($vehicleTypeStatsForCards['Motocicleta']['ingresos'] ?? 0) . '</div>
        </div>
        <div class="metric-item" style="width: 50%;">
            <p><span class="text-secondary">&#9702;</span></p>
            <h5>Ingresos de Bicicletas</h5>
            <div class="value">' . htmlspecialchars($vehicleTypeStatsForCards['Bicicleta']['ingresos'] ?? 0) . '</div>
        </div>
    </div>

    <div class="section-title">Actividad Diaria (Ingresos vs. Salidas)</div>';
    if (!empty($dailyAccessData)) {
        $pdfHtml .= '
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Ingresos</th>
                        <th>Salidas</th>
                    </tr>
                </thead>
                <tbody>';
        foreach ($dailyAccessData as $data) {
            $pdfHtml .= '<tr>
                            <td>' . htmlspecialchars($data['access_date']) . '</td>
                            <td>' . htmlspecialchars($data['daily_ingresos']) . '</td>
                            <td>' . htmlspecialchars($data['daily_salidas']) . '</td>
                        </tr>';
        }
        $pdfHtml .= '</tbody></table>';
    } else {
        $pdfHtml .= '<p class="chart-note">No hay datos de actividad diaria en el rango seleccionado.</p>';
    }

$pdfHtml .= '
    <div class="section-title">Actividad por Hora del Día</div>';
    if (!empty($hourlyAccessData)) {
        $pdfHtml .= '
            <table>
                <thead>
                    <tr>
                        <th>Hora</th>
                        <th>Ingresos</th>
                        <th>Salidas</th>
                    </tr>
                </thead>
                <tbody>';
        foreach ($hourlyAccessData as $data) {
            $pdfHtml .= '<tr>
                            <td>' . htmlspecialchars($data['access_hour']) . ':00</td>
                            <td>' . htmlspecialchars($data['hourly_ingresos']) . '</td>
                            <td>' . htmlspecialchars($data['hourly_salidas']) . '</td>
                        </tr>';
        }
        $pdfHtml .= '</tbody></table>';
    } else {
        $pdfHtml .= '<p class="chart-note">No hay datos de actividad por hora en el rango seleccionado.</p>';
    }

$pdfHtml .= '
    <div class="section-title">Distribución de Accesos por Tipo de Vehículo</div>';
    if (!empty($vehicleTypeStatsRaw)) {
        $pdfHtml .= '
            <table>
                <thead>
                    <tr>
                        <th>Tipo de Vehículo</th>
                        <th>Ingresos</th>
                        <th>Salidas</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>';
        foreach ($vehicleTypeStatsRaw as $data) {
            $totalType = $data['ingresos'] + $data['salidas'];
            $pdfHtml .= '<tr>
                            <td>' . htmlspecialchars($data['tipo']) . '</td>
                            <td>' . htmlspecialchars($data['ingresos']) . '</td>
                            <td>' . htmlspecialchars($data['salidas']) . '</td>
                            <td>' . htmlspecialchars($totalType) . '</td>
                        </tr>';
        }
        $pdfHtml .= '</tbody></table>';
    } else {
        $pdfHtml .= '<p class="chart-note">No hay datos de distribución por tipo de vehículo en el rango seleccionado.</p>';
    }

$pdfHtml .= '
</body>
</html>';

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($pdfHtml);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$filename = 'reporte_general_' . date('YmdHis') . '.pdf';
$dompdf->stream($filename, ["Attachment" => true]);
exit;
?>