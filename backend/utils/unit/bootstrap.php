<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
date_default_timezone_set('America/Bogota');

// Crear directorio de reportes
$reportsDir = __DIR__ . '/reports';
if (!is_dir($reportsDir)) {
    mkdir($reportsDir, 0777, true);
}
