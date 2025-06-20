<?php
// backend/controllers/UserDetailReportPDF.php

// Cargar Dompdf (ajusta la ruta según tu instalación: Composer o manual)
// Si usas Composer:
require_once '../../vendor/autoload.php';
// Si instalaste manualmente en backend/lib/dompdf:
// require_once '../../backend/lib/dompdf/autoload.inc.php'; // Ajusta la ruta si es necesario

use Dompdf\Dompdf;
use Dompdf\Options;

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

$user = null;
$accessStats = ['ingresos' => 0, 'salidas' => 0];
$userAccesses = [];

if ($userId > 0) {
    $userPark->id_userPark = $userId;
    if ($userPark->readOne()) {
        $user = [
            "id_userPark" => $userPark->id_userPark,
            "tipo_user" => $userPark->tipo_user,
            "tipo_documento" => $userPark->tipo_documento,
            "numero_documento" => $userPark->numero_documento,
            "nombres_park" => $userPark->nombres_park,
            "apellidos_park" => $userPark->apellidos_park,
            "edificio" => $userPark->edificio,
            "numero_contacto" => $userPark->numero_contacto
        ];

        $accessStats = $access->getUserAccessStats($userId);
        $stmtAccesses = $access->getUserAccesses($userId);
        while ($rowAccess = $stmtAccesses->fetch(PDO::FETCH_ASSOC)) {
            $userAccesses[] = $rowAccess;
        }
    }
}

// Generación del HTML para el PDF
$reportTitle = "Detalle de Usuario";
if (!empty($user)) {
    $reportTitle .= ": " . htmlspecialchars($user['nombres_park'] . ' ' . $user['apellidos_park']);
}

$pdfHtml = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>' . $reportTitle . '</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; font-size: 10pt; }
        h1 { color: #333; text-align: center; margin-bottom: 20px; }
        .section-title { font-weight: bold; margin-top: 20px; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 5px; color: #555; }
        .user-info p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
        .stats-box { border: 1px solid #ccc; padding: 10px; margin-top: 15px; background-color: #f9f9f9; }
        .stats-box p { margin: 5px 0; }
    </style>
</head>
<body>
    <h1>' . $reportTitle . '</h1>';

if (!empty($user)) {
    $pdfHtml .= '
    <div class="section-title">Datos del Usuario</div>
    <div class="user-info">
        <p><strong>Nombres:</strong> ' . htmlspecialchars($user['nombres_park']) . '</p>
        <p><strong>Apellidos:</strong> ' . htmlspecialchars($user['apellidos_park']) . '</p>
        <p><strong>Tipo de Usuario:</strong> ' . htmlspecialchars($user['tipo_user']) . '</p>
        <p><strong>Documento:</strong> ' . htmlspecialchars($user['tipo_documento'] . ' ' . $user['numero_documento']) . '</p>
        <p><strong>Edificio:</strong> ' . htmlspecialchars($user['edificio'] ?? 'N/A') . '</p>
        <p><strong>Contacto:</strong> ' . htmlspecialchars($user['numero_contacto'] ?? 'N/A') . '</p>
    </div>

    <div class="section-title">Estadísticas de Acceso</div>
    <div class="stats-box text-center">
        <p><strong>Ingresos:</strong> ' . htmlspecialchars($accessStats['ingresos']) . '</p>
        <p><strong>Salidas:</strong> ' . htmlspecialchars($accessStats['salidas']) . '</p>
        <p><strong>Total Movimientos:</strong> ' . htmlspecialchars($accessStats['ingresos'] + $accessStats['salidas']) . '</p>
    </div>

    <div class="section-title">Registros de Acceso</div>
    <table>
        <thead>
            <tr>
                <th>Fecha/Hora</th>
                <th>Acción</th>
                <th>Placa</th>
                <th>Espacio</th>
            </tr>
        </thead>
        <tbody>';

    if (!empty($userAccesses)) {
        foreach ($userAccesses as $accessItem) {
            $pdfHtml .= '
            <tr>
                <td>' . htmlspecialchars($accessItem['fecha_hora']) . '</td>
                <td>' . htmlspecialchars($accessItem['tipo_accion']) . '</td>
                <td>' . htmlspecialchars($accessItem['placa']) . '</td>
                <td>' . htmlspecialchars($accessItem['espacio_asignado'] ?? 'N/A') . '</td>
            </tr>';
        }
    } else {
        $pdfHtml .= '
            <tr>
                <td colspan="4" class="text-center">No se encontraron registros de acceso para este usuario.</td>
            </tr>';
    }

    $pdfHtml .= '
        </tbody>
    </table>';
} else {
    $pdfHtml .= '
    <div class="text-center" style="padding: 50px;">
        <h2>Usuario no encontrado</h2>
        <p>El ID de usuario especificado no es válido o no existe.</p>
    </div>';
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

$filename = 'reporte_usuario_' . (!empty($user['numero_documento']) ? $user['numero_documento'] : 'desconocido') . '_' . date('YmdHis') . '.pdf';
$dompdf->stream($filename, ["Attachment" => true]);
exit;
?>