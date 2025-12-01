<?php
// Bootstrap for End-to-End tests
require_once __DIR__ . '/../../../vendor/autoload.php';
date_default_timezone_set('America/Bogota');

// Test database configuration
define('E2E_DB_HOST', 'localhost');
define('E2E_DB_NAME', 'senaparking_test');
define('E2E_DB_USER', 'root');
define('E2E_DB_PASS', '');

// Define constant for conexion.php to pick up
define('TEST_DB_NAME', E2E_DB_NAME);

// Create reports directory
$reportsDir = __DIR__ . '/reports';
if (!is_dir($reportsDir)) {
    mkdir($reportsDir, 0777, true);
}

// Helper to simulate POST request
function simulatePostRequest($controllerPath, $postData) {
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_POST = $postData;
    
    // Save current directory
    $cwd = getcwd();
    
    // Change to controller directory so relative includes work
    chdir(dirname($controllerPath));
    
    // Inject $conn if not defined (handles require_once skipping in multi-step tests)
    if (!isset($conn)) {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = defined('TEST_DB_NAME') ? TEST_DB_NAME : "senaparking_db";
        $conn = new mysqli($servername, $username, $password, $dbname);
    }
    
    try {
        ob_start();
        // echo "DEBUG: CWD is " . getcwd() . "\n";
        // echo "DEBUG: Including " . basename($controllerPath) . "\n";
        include basename($controllerPath);
        $output = ob_get_clean();
    } finally {
        // Restore directory always
        chdir($cwd);
    }
    
    // Clean POST and SERVER after test
    $_POST = [];
    $_SERVER['REQUEST_METHOD'] = 'GET';
    
    return $output;
}

// Helper to get connection
function getE2EConnection() {
    $conn = new mysqli(E2E_DB_HOST, E2E_DB_USER, E2E_DB_PASS, E2E_DB_NAME);
    if ($conn->connect_error) {
        die("E2E DB Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Clean up function
function cleanE2EDatabase() {
    $conn = getE2EConnection();
    $conn->query("SET FOREIGN_KEY_CHECKS = 0");
    $conn->query("DELETE FROM tb_actividades WHERE id_userSys > 100");
    $conn->query("DELETE FROM tb_accesos WHERE id_acceso > 100");
    $conn->query("DELETE FROM tb_vehiculos WHERE id_vehiculo > 100");
    $conn->query("DELETE FROM tb_userpark WHERE id_userPark > 100");
    $conn->query("DELETE FROM tb_usersys WHERE id_userSys > 100");
    $conn->query("SET FOREIGN_KEY_CHECKS = 1");
    $conn->close();
}
