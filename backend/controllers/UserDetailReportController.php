<?php
// backend/controllers/UserDetailReportController.php

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

// --- Generación del HTML Dinámico ---
$htmlContent = file_get_contents('../../frontend/views/reporte_usuario_detalle.php');
$dynamicContent = '';

if (!empty($user)) {
    $dynamicContent .= '
        <div class="card-custom">
            <h5 class="user-detail-header"><i class="fas fa-id-card me-2 text-primary"></i>Datos del Usuario</h5>
            <div class="user-info row">
                <p class="col-md-6"><strong>Nombres:</strong> ' . htmlspecialchars($user['nombres_park']) . '</p>
                <p class="col-md-6"><strong>Apellidos:</strong> ' . htmlspecialchars($user['apellidos_park']) . '</p>
                <p class="col-md-6"><strong>Tipo de Usuario:</strong> ' . htmlspecialchars($user['tipo_user']) . '</p>
                <p class="col-md-6"><strong>Documento:</strong> ' . htmlspecialchars($user['tipo_documento'] . ' ' . $user['numero_documento']) . '</p>
                <p class="col-md-6"><strong>Edificio:</strong> ' . htmlspecialchars($user['edificio'] ?? 'N/A') . '</p>
                <p class="col-md-6"><strong>Contacto:</strong> ' . htmlspecialchars($user['numero_contacto'] ?? 'N/A') . '</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card-custom">
                    <h5 class="user-detail-header"><i class="fas fa-chart-pie me-2 text-success"></i>Estadísticas de Acceso</h5>
                    <div class="chart-container">
                        <canvas id="accessChart"></canvas>
                    </div>
                    <div class="text-center mt-3">
                        <p><strong>Ingresos:</strong> ' . htmlspecialchars($accessStats['ingresos']) . '</p>
                        <p><strong>Salidas:</strong> ' . htmlspecialchars($accessStats['salidas']) . '</p>
                        <p><strong>Total Movimientos:</strong> ' . htmlspecialchars($accessStats['ingresos'] + $accessStats['salidas']) . '</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card-custom">
                    <h5 class="user-detail-header"><i class="fas fa-list-alt me-2 text-info"></i>Registros de Acceso</h5>
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover table-striped table-sm">
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
            $dynamicContent .= '
                                <tr>
                                    <td>' . htmlspecialchars($accessItem['fecha_hora']) . '</td>
                                    <td>' . htmlspecialchars($accessItem['tipo_accion']) . '</td>
                                    <td>' . htmlspecialchars($accessItem['placa']) . '</td>
                                    <td>' . htmlspecialchars($accessItem['espacio_asignado'] ?? 'N/A') . '</td>
                                </tr>';
        }
    } else {
        $dynamicContent .= '
                                <tr>
                                    <td colspan="4" class="text-center py-3">
                                        <div class="no-records">
                                            <i class="fas fa-car-alt fa-2x mb-2"></i>
                                            <p>No se encontraron registros de acceso para este usuario.</p>
                                        </div>
                                    </td>
                                </tr>';
    }

    $dynamicContent .= '
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const ingresos = ' . json_encode($accessStats['ingresos'] ?? 0) . ';
                const salidas = ' . json_encode($accessStats['salidas'] ?? 0) . ';

                if (ingresos + salidas > 0) {
                    const ctx = document.getElementById("accessChart").getContext("2d");
                    new Chart(ctx, {
                        type: "pie",
                        data: {
                            labels: ["Ingresos", "Salidas"],
                            datasets: [{
                                data: [ingresos, salidas],
                                backgroundColor: ["#4CAF50", "#FFC107"],
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                title: {
                                    display: true,
                                    text: "Distribución de Ingresos vs. Salidas"
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.label || "";
                                            if (label) {
                                                label += ": ";
                                            }
                                            if (context.parsed !== null) {
                                                label += context.parsed + " (" + ((context.parsed / (ingresos + salidas)) * 100).toFixed(2) + "%)";
                                            }
                                            return label;
                                        }
                                    }
                                }
                            }
                        }
                    });
                } else {
                    const chartContainer = document.getElementById("accessChart").closest(".chart-container");
                    if (chartContainer) {
                        chartContainer.innerHTML = \'<div class="text-center py-4"><i class="fas fa-chart-pie fa-3x mb-2 text-muted"></i><p>No hay datos de acceso para graficar.</p></div>\';
                    }
                }
            });
        </script>
    ';
} else {
    $dynamicContent .= '
        <div class="card-custom text-center py-5">
            <i class="fas fa-exclamation-triangle fa-3x mb-3 text-warning"></i>
            <h4>Usuario no encontrado</h4>
            <p>El ID de usuario especificado no es válido o no existe.</p>
            <button class="btn btn-primary mt-3" onclick="goBack()">Volver a la lista de usuarios</button>
        </div>
    ';
}

$htmlContent = str_replace('<div id="userDetailsContent">', '<div id="userDetailsContent">' . $dynamicContent, $htmlContent);

echo $htmlContent;
?>