<?php
/**
 * Generador de Reporte de Regresión
 * Compara resultados actuales con baseline y detecta regresiones
 */

if ($argc < 2) {
    die("Uso: php generate-report.php <report_directory>\n");
}

$reportDir = $argv[1];
$baselineDir = __DIR__ . '/baselines/current/';

echo "Generando reporte de regresión...\n";

// Verificar que existe baseline
if (!file_exists($baselineDir . 'metadata.json')) {
    echo "❌ No se encontró baseline. Ejecuta establish-baseline.bat primero.\n";
    exit(1);
}

// Cargar metadata de baseline
$baselineMetadata = json_decode(file_get_contents($baselineDir . 'metadata.json'), true);

// Comparar resultados
$comparison = [
    'unit' => compareResults(
        $baselineDir . 'unit-baseline.xml',
        $reportDir . '/unit-results.xml'
    ),
    'integration' => compareResults(
        $baselineDir . 'integration-baseline.xml',
        $reportDir . '/integration-results.xml'
    ),
    'e2e' => compareResults(
        $baselineDir . 'e2e-baseline.xml',
        $reportDir . '/e2e-results.xml'
    )
];

// Generar reporte HTML
generateHtmlReport($reportDir, $baselineMetadata, $comparison);

// Generar resumen en consola
generateConsoleSummary($comparison);

function compareResults($baselineFile, $currentFile) {
    $result = [
        'baseline_exists' => file_exists($baselineFile),
        'current_exists' => file_exists($currentFile),
        'baseline_tests' => [],
        'current_tests' => [],
        'passed_baseline' => 0,
        'passed_current' => 0,
        'failed_baseline' => 0,
        'failed_current' => 0,
        'regressions' => [],
        'improvements' => [],
        'new_tests' => [],
        'removed_tests' => []
    ];
    
    if (!$result['baseline_exists'] || !$result['current_exists']) {
        return $result;
    }
    
    // Parsear XMLs
    $baselineXml = @simplexml_load_file($baselineFile);
    $currentXml = @simplexml_load_file($currentFile);
    
    if (!$baselineXml || !$currentXml) {
        return $result;
    }
    
    // Extraer tests de baseline
    foreach ($baselineXml->xpath('//testcase') as $test) {
        $name = (string)$test['name'];
        $class = (string)$test['class'];
        $key = $class . '::' . $name;
        
        $hasFailure = count($test->xpath('.//failure')) > 0;
        $hasError = count($test->xpath('.//error')) > 0;
        
        $result['baseline_tests'][$key] = !($hasFailure || $hasError);
        
        if ($hasFailure || $hasError) {
            $result['failed_baseline']++;
        } else {
            $result['passed_baseline']++;
        }
    }
    
    // Extraer tests actuales
    foreach ($currentXml->xpath('//testcase') as $test) {
        $name = (string)$test['name'];
        $class = (string)$test['class'];
        $key = $class . '::' . $name;
        
        $hasFailure = count($test->xpath('.//failure')) > 0;
        $hasError = count($test->xpath('.//error')) > 0;
        
        $result['current_tests'][$key] = !($hasFailure || $hasError);
        
        if ($hasFailure || $hasError) {
            $result['failed_current']++;
        } else {
            $result['passed_current']++;
        }
    }
    
    // Detectar regresiones e mejoras
    foreach ($result['baseline_tests'] as $testKey => $baselinePassed) {
        if (!isset($result['current_tests'][$testKey])) {
            $result['removed_tests'][] = $testKey;
            continue;
        }
        
        $currentPassed = $result['current_tests'][$testKey];
        
        if ($baselinePassed && !$currentPassed) {
            $result['regressions'][] = $testKey;
        } elseif (!$baselinePassed && $currentPassed) {
            $result['improvements'][] = $testKey;
        }
    }
    
    // Detectar nuevos tests
    foreach ($result['current_tests'] as $testKey => $currentPassed) {
        if (!isset($result['baseline_tests'][$testKey])) {
            $result['new_tests'][] = $testKey;
        }
    }
    
    return $result;
}

function generateHtmlReport($reportDir, $baselineMetadata, $comparison) {
    $html = '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Regresión - SENAParking</title>
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }
        .summary-box {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .regression {
            border-left: 4px solid #e74c3c;
        }
        .improvement {
            border-left: 4px solid #27ae60;
        }
        .neutral {
            border-left: 4px solid #3498db;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin: 20px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #34495e;
            color: white;
        }
        .pass {
            color: #27ae60;
            font-weight: bold;
        }
        .fail {
            color: #e74c3c;
            font-weight: bold;
        }
        .new {
            color: #3498db;
            font-weight: bold;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.9em;
            font-weight: bold;
        }
        .badge-success {
            background: #27ae60;
            color: white;
        }
        .badge-danger {
            background: #e74c3c;
            color: white;
        }
        .badge-warning {
            background: #f39c12;
            color: white;
        }
        .badge-info {
            background: #3498db;
            color: white;
        }
    </style>
</head>
<body>
    <h1>📊 Reporte de Regresión - SENAParking</h1>
    <p><strong>Generado:</strong> ' . date('Y-m-d H:i:s') . '</p>
    
    <div class="summary-box neutral">
        <h2>📌 Información de Baseline</h2>
        <p><strong>Creada:</strong> ' . $baselineMetadata['timestamp'] . '</p>
        <p><strong>Total tests:</strong> ' . $baselineMetadata['total_tests'] . '</p>';
    
    if (!empty($baselineMetadata['git_commit_short'])) {
        $html .= '<p><strong>Git commit:</strong> ' . $baselineMetadata['git_commit_short'] . '</p>';
    }
    
    $html .= '</div>';
    
    // Resumen general
    $totalRegressions = count($comparison['unit']['regressions']) + 
                       count($comparison['integration']['regressions']) + 
                       count($comparison['e2e']['regressions']);
    
    $totalImprovements = count($comparison['unit']['improvements']) + 
                        count($comparison['integration']['improvements']) + 
                        count($comparison['e2e']['improvements']);
    
    $totalNew = count($comparison['unit']['new_tests']) + 
               count($comparison['integration']['new_tests']) + 
               count($comparison['e2e']['new_tests']);
    
    $boxClass = $totalRegressions > 0 ? 'regression' : ($totalImprovements > 0 ? 'improvement' : 'neutral');
    
    $html .= '<div class="summary-box ' . $boxClass . '">
        <h2>📈 Resumen General</h2>
        <table>
            <tr>
                <th>Métrica</th>
                <th>Valor</th>
            </tr>
            <tr>
                <td>🔴 Regresiones Detectadas</td>
                <td class="fail">' . $totalRegressions . '</td>
            </tr>
            <tr>
                <td>🟢 Mejoras</td>
                <td class="pass">' . $totalImprovements . '</td>
            </tr>
            <tr>
                <td>🆕 Nuevos Tests</td>
                <td class="new">' . $totalNew . '</td>
            </tr>
        </table>
    </div>';
    
    // Detalles por suite
    foreach (['unit' => 'Unitarias', 'integration' => 'Integración', 'e2e' => 'E2E'] as $key => $label) {
        $data = $comparison[$key];
        
        $html .= '<h2>📋 Pruebas ' . $label . '</h2>';
        $html .= '<table>
            <tr>
                <th>Métrica</th>
                <th>Baseline</th>
                <th>Actual</th>
                <th>Cambio</th>
            </tr>
            <tr>
                <td>Tests Pasados</td>
                <td>' . $data['passed_baseline'] . '</td>
                <td>' . $data['passed_current'] . '</td>
                <td>' . ($data['passed_current'] - $data['passed_baseline']) . '</td>
            </tr>
            <tr>
                <td>Tests Fallidos</td>
                <td>' . $data['failed_baseline'] . '</td>
                <td>' . $data['failed_current'] . '</td>
                <td>' . ($data['failed_current'] - $data['failed_baseline']) . '</td>
            </tr>
        </table>';
        
        if (count($data['regressions']) > 0) {
            $html .= '<h3 class="fail">❌ Regresiones (' . count($data['regressions']) . ')</h3><ul>';
            foreach ($data['regressions'] as $test) {
                $html .= '<li>' . htmlspecialchars($test) . '</li>';
            }
            $html .= '</ul>';
        }
        
        if (count($data['improvements']) > 0) {
            $html .= '<h3 class="pass">✅ Mejoras (' . count($data['improvements']) . ')</h3><ul>';
            foreach ($data['improvements'] as $test) {
                $html .= '<li>' . htmlspecialchars($test) . '</li>';
            }
            $html .= '</ul>';
        }
        
        if (count($data['new_tests']) > 0) {
            $html .= '<h3 class="new">🆕 Nuevos Tests (' . count($data['new_tests']) . ')</h3><ul>';
            foreach ($data['new_tests'] as $test) {
                $html .= '<li>' . htmlspecialchars($test) . '</li>';
            }
            $html .= '</ul>';
        }
    }
    
    $html .= '</body></html>';
    
    file_put_contents($reportDir . '/regression-report.html', $html);
    echo "✅ Reporte HTML generado: $reportDir/regression-report.html\n";
}

function generateConsoleSummary($comparison) {
    echo "\n========================================\n";
    echo "RESUMEN DE REGRESIÓN\n";
    echo "========================================\n\n";
    
    $totalRegressions = count($comparison['unit']['regressions']) + 
                       count($comparison['integration']['regressions']) + 
                       count($comparison['e2e']['regressions']);
    
    if ($totalRegressions > 0) {
        echo "❌ REGRESIONES DETECTADAS: $totalRegressions\n\n";
        
        foreach (['unit' => 'Unitarias', 'integration' => 'Integración', 'e2e' => 'E2E'] as $key => $label) {
            if (count($comparison[$key]['regressions']) > 0) {
                echo "$label:\n";
                foreach ($comparison[$key]['regressions'] as $test) {
                    echo "  - $test\n";
                }
                echo "\n";
            }
        }
    } else {
        echo "✅ NO SE DETECTARON REGRESIONES\n\n";
    }
}
