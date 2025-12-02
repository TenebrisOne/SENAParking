<?php
/**
 * Generador de Resumen de Pruebas de Rendimiento
 * Lee los reportes de Apache Bench y genera un resumen consolidado
 */

$reportDir = __DIR__ . '/reports/';
$summaryFile = $reportDir . 'summary.html';

// Función para parsear un reporte de Apache Bench
function parseAbReport($filePath) {
    if (!file_exists($filePath)) {
        return null;
    }
    
    $content = file_get_contents($filePath);
    
    // Convertir UTF-16LE a UTF-8
    $content = mb_convert_encoding($content, 'UTF-8', 'UTF-16LE');
    
    $data = [];
    
    // Extraer métricas clave
    if (preg_match('/Requests per second:\s+([\d.]+)/', $content, $matches)) {
        $data['rps'] = $matches[1];
    }
    if (preg_match('/Time per request:\s+([\d.]+).*\(mean\)/', $content, $matches)) {
        $data['mean_time'] = $matches[1];
    }
    if (preg_match('/Failed requests:\s+(\d+)/', $content, $matches)) {
        $data['failed'] = $matches[1];
    }
    if (preg_match('/Total transferred:\s+(\d+)/', $content, $matches)) {
        $data['transferred'] = $matches[1];
    }
    if (preg_match('/Concurrency Level:\s+(\d+)/', $content, $matches)) {
        $data['concurrency'] = $matches[1];
    }
    if (preg_match('/Complete requests:\s+(\d+)/', $content, $matches)) {
        $data['total_requests'] = $matches[1];
    }
    
    return $data;
}

// Definir los tests a analizar
$tests = [
    'Login' => [
        'low' => 'login_low.txt',
        'medium' => 'login_medium.txt',
        'high' => 'login_high.txt',
        'stress' => 'login_stress.txt'
    ],
    'Vehicle Registration' => [
        'low' => 'vehicle_low.txt',
        'medium' => 'vehicle_medium.txt',
        'high' => 'vehicle_high.txt',
        'stress' => 'vehicle_stress.txt'
    ]
];

// Generar HTML
$html = '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen de Pruebas de Rendimiento - SENAParking</title>
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }
        h2 {
            color: #34495e;
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #3498db;
            color: white;
            font-weight: bold;
        }
        tr:hover {
            background: #f8f9fa;
        }
        .metric {
            font-weight: bold;
        }
        .good {
            color: #27ae60;
        }
        .warning {
            color: #f39c12;
        }
        .bad {
            color: #e74c3c;
        }
        .timestamp {
            color: #7f8c8d;
            font-size: 0.9em;
        }
        .summary-box {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #3498db;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <h1>📊 Resumen de Pruebas de Rendimiento - SENAParking</h1>
    <p class="timestamp">Generado: ' . date('Y-m-d H:i:s') . '</p>
    
    <div class="summary-box">
        <h3>Leyenda de Niveles de Carga</h3>
        <ul>
            <li><strong>Baja:</strong> 100 requests, 10 concurrentes</li>
            <li><strong>Media:</strong> 500 requests, 50 concurrentes</li>
            <li><strong>Alta:</strong> 1000 requests, 100 concurrentes</li>
            <li><strong>Estrés:</strong> 2000 requests, 200 concurrentes</li>
        </ul>
    </div>';

foreach ($tests as $endpoint => $scenarios) {
    $html .= "<h2>$endpoint</h2>";
    $html .= '<table>
        <tr>
            <th>Escenario</th>
            <th>Requests/seg</th>
            <th>Tiempo Medio (ms)</th>
            <th>Total Requests</th>
            <th>Fallidos</th>
            <th>Estado</th>
        </tr>';
    
    foreach ($scenarios as $level => $file) {
        $data = parseAbReport($reportDir . $file);
        
        if ($data) {
            $status = 'good';
            $statusText = '✅ OK';
            
            if ($data['failed'] > 0) {
                $status = 'warning';
                $statusText = '⚠️ Advertencia';
            }
            if ($data['failed'] > ($data['total_requests'] * 0.05)) {
                $status = 'bad';
                $statusText = '❌ Crítico';
            }
            
            $html .= '<tr>
                <td class="metric">' . ucfirst($level) . '</td>
                <td>' . number_format($data['rps'], 2) . '</td>
                <td>' . number_format($data['mean_time'], 2) . '</td>
                <td>' . number_format($data['total_requests']) . '</td>
                <td class="' . $status . '">' . $data['failed'] . '</td>
                <td class="' . $status . '">' . $statusText . '</td>
            </tr>';
        } else {
            $html .= '<tr>
                <td class="metric">' . ucfirst($level) . '</td>
                <td colspan="5" class="warning">No ejecutado</td>
            </tr>';
        }
    }
    
    $html .= '</table>';
}

$html .= '
    <div class="summary-box">
        <h3>Recomendaciones</h3>
        <ul>
            <li>Si hay requests fallidos, revisa los logs de PHP y MySQL</li>
            <li>Tiempo de respuesta > 1000ms indica posibles cuellos de botella</li>
            <li>Considera implementar caché para mejorar RPS</li>
            <li>Optimiza queries SQL si el tiempo de respuesta es alto</li>
        </ul>
    </div>
</body>
</html>';

// Guardar el resumen
file_put_contents($summaryFile, $html);

echo "✅ Resumen generado exitosamente!\n";
echo "📄 Archivo: $summaryFile\n";
echo "\nAbriendo en navegador...\n";

// Abrir en navegador
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    exec("start $summaryFile");
}
