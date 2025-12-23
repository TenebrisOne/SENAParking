<?php
// Test file to check LoginController errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing LoginController path...\n";
echo "Current directory: " . getcwd() . "\n";
echo "Script directory: " . __DIR__ . "\n";

// Try to include conexion.php
$conexion_path = __DIR__ . '/../config/conexion.php';
echo "Conexion path: $conexion_path\n";
echo "File exists: " . (file_exists($conexion_path) ? 'YES' : 'NO') . "\n";

if (file_exists($conexion_path)) {
    require_once($conexion_path);
    echo "Connection included successfully\n";
    echo "Connection status: " . ($conn->connect_error ? "ERROR: " . $conn->connect_error : "OK") . "\n";
} else {
    echo "ERROR: conexion.php not found!\n";
}
?>
