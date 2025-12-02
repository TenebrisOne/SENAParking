<?php
/**
 * Mostrar Resumen de Pruebas en Consola
 */

$reportDir = __DIR__ . '/reports/';

function parseAbReport($filePath) {
    if (!file_exists($filePath)) {
        return null;
    }
    
    // Leer archivo
    $content = @file_get_contents($filePath);
    if ($content === false) {
        return null;
    }
    
    // Convertir UTF-16LE a UTF-8
    $content = mb_convert_encoding($content, 'UTF-8', 'UTF-16LE');
    
    $data = [];
    
    // Buscar métricas
    if (preg_match('/Requests\s+per\s+second:\s+([\d.]+)/i', $content, $matches)) {
        $data['rps'] = $matches[1];
    }
    if (preg_match('/Time\s+per\s+request:\s+([\d.]+).*\(mean\)/i', $content, $matches)) {
        $data['mean_time'] = $matches[1];
    }
    if (preg_match('/Failed\s+requests:\s+(\d+)/i', $content, $matches)) {
        $data['failed'] = $matches[1];
    }
    if (preg_match('/Complete\s+requests:\s+(\d+)/i', $content, $matches)) {
        $data['total_requests'] = $matches[1];
    }
    
    return count($data) > 0 ? $data : null;
}

echo "========================================\n";
echo "📊 RESUMEN DE PRUEBAS DE RENDIMIENTO\n";
echo "========================================\n\n";

$tests = [
    'Login' => [
        'Baja' => 'login_low.txt',
        'Media' => 'login_medium.txt',
        'Alta' => 'login_high.txt',
        'Estrés' => 'login_stress.txt'
    ],
    'Vehicle Registration' => [
        'Baja' => 'vehicle_low.txt',
        'Media' => 'vehicle_medium.txt',
        'Alta' => 'vehicle_high.txt',
        'Estrés' => 'vehicle_stress.txt'
    ]
];

foreach ($tests as $endpoint => $scenarios) {
    echo "🎯 $endpoint\n";
    echo str_repeat("-", 70) . "\n";
    printf("%-12s %15s %18s %15s %12s\n", "Escenario", "Req/seg", "Tiempo (ms)", "Total Req", "Fallidos");
    echo str_repeat("-", 70) . "\n";
    
    foreach ($scenarios as $level => $file) {
        $data = parseAbReport($reportDir . $file);
        
        if ($data) {
            $status = $data['failed'] == 0 ? '✅' : '❌';
            printf("%-12s %15s %18s %15s %12s %s\n", 
                $level,
                number_format($data['rps'], 2),
                number_format($data['mean_time'], 2),
                number_format($data['total_requests']),
                $data['failed'],
                $status
            );
        } else {
            printf("%-12s ⚠️  No ejecutado o error al leer\n", $level);
        }
    }
    echo "\n";
}

echo "========================================\n";
echo "✅ Resumen completado\n";
echo "========================================\n";
echo "\nPara ver el reporte HTML detallado:\n";
echo "  start backend\\utils\\performance\\reports\\summary.html\n\n";
