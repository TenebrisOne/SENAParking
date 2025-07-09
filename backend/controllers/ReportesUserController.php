<?php
// backend/controllers/UserReportController.php

// Incluir archivos de conexión y modelo
include_once '../config/conexion.php';
include_once '../models/ReportesUserPark.php';

// Habilitar la visualización de errores (desactivar en producción)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Obtener conexión a la base de datos
$database = new DataBase();
$db = $database->getConnection();

// Instanciar objeto UserPark
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
} catch (PDOException $e) {
    error_log("Error PDO en UserReportController: " . $e->getMessage());
    // Puedes manejar el error de forma más elegante en producción
    echo "<p>Error en la base de datos: " . $e->getMessage() . "</p>";
    exit(); // Detener la ejecución si hay un error crítico de BD
} catch (Exception $e) {
    error_log("Error general en UserReportController: " . $e->getMessage());
    echo "<p>Error interno del servidor: " . $e->getMessage() . "</p>";
    exit();
}

// --- Generación del HTML Dinámico ---

// 1. Cargar el contenido del archivo HTML estático
$htmlContent = file_get_contents('../../frontend/views/reporte_usuarios.html');

// 2. Preparar el contenido dinámico para inyectar
$tableRowsHtml = '';
if (!empty($users)) {
    foreach ($users as $user) {
        $tableRowsHtml .= '
            <tr>
                <td>' . htmlspecialchars($user['id_userPark']) . '</td>
                <td>' . htmlspecialchars($user['tipo_user']) . '</td>
                <td>' . htmlspecialchars($user['numero_documento']) . '</td>
                <td>' . htmlspecialchars($user['nombres_park']) . '</td>
                <td>' . htmlspecialchars($user['apellidos_park']) . '</td>
                <td>' . htmlspecialchars($user['numero_contacto'] ?? 'N/A') . '</td>
                <td class="text-center">
                    <a href="UserDetailReportController.php?id=' . htmlspecialchars($user['id_userPark']) . '" class="btn btn-info btn-sm btn-detail">
                        <i class="fas fa-info-circle me-1"></i>Ver Detalles
                    </a>
                </td>
            </tr>
        ';
    }
    // Asegurarse de ocultar el mensaje de no resultados si hay usuarios
    $htmlContent = str_replace('<div class="no-results d-none" id="noResults">', '<div class="no-results d-none" id="noResults" style="display:none;">', $htmlContent);

} else {
    $tableRowsHtml = '<tr><td colspan="7" class="text-center py-4"><div class="no-results"><i class="fas fa-users-slash fa-3x mb-3"></i><h4>No se encontraron usuarios</h4><p>Intenta con otros términos de búsqueda o verifica los filtros.</p></div></td></tr>';
    // Asegurarse de mostrar el mensaje de no resultados si no hay usuarios
    $htmlContent = str_replace('<div class="no-results d-none" id="noResults">', '<div class="no-results" id="noResults" style="display:block;">', $htmlContent);
}

$paginationHtml = '';
// Botón "Anterior"


// Números de página
for ($i = 1; $i <= $totalPages; $i++) {
    $paginationHtml .= '<li class="page-item ' . ($i == $page ? 'active' : '') . '">
        <a class="page-link" href="UserReportController.php?page=' . $i . (!empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '') . '">' . $i . '</a>
    </li>';
}

// Botón "Siguiente"



// Inyectar el contenido en el HTML
$htmlContent = str_replace('<tbody id="usersTableBody">', '<tbody id="usersTableBody">' . $tableRowsHtml, $htmlContent);
$htmlContent = str_replace('<ul class="pagination justify-content-center" id="paginationControls">', '<ul class="pagination justify-content-center" id="paginationControls">' . $paginationHtml, $htmlContent);

// Inyectar el término de búsqueda en el input
$htmlContent = str_replace('name="search" value=""', 'name="search" value="' . htmlspecialchars($searchTerm) . '"', $htmlContent);
// Mostrar u ocultar el botón de limpiar búsqueda (solo si hay un término)
if (!empty($searchTerm)) {
    $htmlContent = str_replace('<button type="button" class="clear-search" id="clearSearch" onclick="clearSearch()" style="display: none;">', '<button type="button" class="clear-search" id="clearSearch" onclick="clearSearch()" style="display: block;">', $htmlContent);
}


// Enviar el HTML al navegador
echo $htmlContent;
?>