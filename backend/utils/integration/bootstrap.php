<?php
// Bootstrap for integration tests
require_once __DIR__ . '/../../../vendor/autoload.php';
date_default_timezone_set('America/Bogota');

// Test database configuration
define('TEST_DB_HOST', 'localhost');
define('TEST_DB_NAME', 'senaparking_test');
define('TEST_DB_USER', 'root');
define('TEST_DB_PASS', '');

// Create reports directory
$reportsDir = __DIR__ . '/reports';
if (!is_dir($reportsDir)) {
    mkdir($reportsDir, 0777, true);
}

// Helper function to get test database connection
function getTestConnection() {
    $conn = new mysqli(TEST_DB_HOST, TEST_DB_USER, TEST_DB_PASS, TEST_DB_NAME);
    if ($conn->connect_error) {
        die("Test DB Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Helper function to get PDO test connection
function getTestPDO() {
    try {
        $pdo = new PDO(
            "mysql:host=" . TEST_DB_HOST . ";dbname=" . TEST_DB_NAME,
            TEST_DB_USER,
            TEST_DB_PASS
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die("Test PDO Connection failed: " . $e->getMessage());
    }
}

// Clean database before tests (optional)
function cleanTestDatabase() {
    $conn = getTestConnection();
    $conn->query("SET FOREIGN_KEY_CHECKS = 0");
    $conn->query("TRUNCATE TABLE tb_actividades");
    $conn->query("TRUNCATE TABLE tb_accesos");
    $conn->query("TRUNCATE TABLE tb_vehiculos");
    $conn->query("TRUNCATE TABLE tb_userpark");
    $conn->query("TRUNCATE TABLE tb_usersys");
    $conn->query("SET FOREIGN_KEY_CHECKS = 1");
    $conn->close();
}
