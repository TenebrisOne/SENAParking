<?php
// backend/controllers/UserReportController.php

header('Content-Type: application/json'); // Indicar que la respuesta es JSON

// Incluir archivos de conexión y modelo
include_once '../config/conexion.php';
include_once '../models/ReportesUserPark.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$database = new DataBase();
$db = $database->getConnection();

$userPark = new UserPark($db);

// Obtener parámetros de paginación y búsqueda desde la URL (GET)
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$usersPerPage = 10; // Número de usuarios por página
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Calcular offset
$offset = ($page - 1) * $usersPerPage;

$users = [];
$total_rows = 0;
$totalPages = 0;

try {
    $stmt = $userPark->readAll($usersPerPage, $offset, $searchTerm);
    $num = $stmt->rowCount();

    $total_rows = $userPark->countAll($searchTerm);
    $totalPages = ceil($total_rows / $usersPerPage);

    if ($num > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $row;
        }
    }

    echo json_encode([
        "success" => true,
        "users" => $users,
        "currentPage" => $page,
        "totalPages" => $totalPages,
        "totalUsers" => $total_rows,
        "searchTerm" => $searchTerm
    ]);

} catch (PDOException $e) {
    error_log("Error PDO en UserReportController: " . $e->getMessage());
    http_response_code(500); // Internal Server Error
    echo json_encode(["success" => false, "message" => "Error en la base de datos: " . $e->getMessage()]);
} catch (Exception $e) {
    error_log("Error general en UserReportController: " . $e->getMessage());
    http_response_code(500); // Internal Server Error
    echo json_encode(["success" => false, "message" => "Error interno del servidor: " . $e->getMessage()]);
}
?>