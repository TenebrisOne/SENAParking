<?php
// backend/controllers/GeneralReportController.php

include_once '../config/conexion.php';
include_once '../models/ReportAccess.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$database = new DataBase();
$db = $database->getConnection();

$access = new Access($db);

$startDate = isset($_GET['startDate']) && $_GET['startDate'] !== '' ? $_GET['startDate'] . ' 00:00:00' : null;
$endDate = isset($_GET['endDate']) && $_GET['endDate'] !== '' ? $_GET['endDate'] . ' 23:59:59' : null;

// Si no se especifican fechas, usar un rango por defecto (ej. últimos 30 días o todo el historial)
if (!$startDate && !$endDate) {
    $endDate = date('Y-m-d 23:59:59'); // Hasta hoy
    $startDate = date('Y-m-d 00:00:00', strtotime('-30 days')); // Desde hace 30 días
} elseif (!$startDate) { // Si solo hay endDate, startDate es 30 días antes
    $startDate = date('Y-m-d 00:00:00', strtotime($endDate . ' -30 days'));
} elseif (!$endDate) { // Si solo hay startDate, endDate es hoy
    $endDate = date('Y-m-d 23:59:59');
}

// Preparar fechas para el valor del input type="date"
$startDateInput = $startDate ? date('Y-m-d', strtotime($startDate)) : '';
$endDateInput = $endDate ? date('Y-m-d', strtotime($endDate)) : '';

// 1. Métricas Generales (Ingresos, Salidas, Ocupación)
$generalStats = $access->getGeneralAccessStats($startDate, $endDate);
$capacidad_total = $access->getParkingCapacity();
$ocupacion_actual = $access->getCurrentOccupancyCount();

// Añadir capacidad y ocupación a las estadísticas generales
$generalStats['capacidad_total'] = $capacidad_total;
$generalStats['ocupacion_actual'] = $ocupacion_actual;


// 2. Estadísticas por Tipo de Vehículo
$vehicleTypeStatsRaw = $access->getVehicleTypeAccessStats($startDate, $endDate);
$vehicleTypeStats = [];
foreach ($vehicleTypeStatsRaw as $item) {
    $vehicleTypeStats[$item['tipo']] = [
        'ingresos' => $item['ingresos'],
        'salidas' => $item['salidas']
    ];
}
// Asegurarse de que 'Motocicleta' y 'Bicicleta' siempre existan para las tarjetas
if (!isset($vehicleTypeStats['Motocicleta'])) $vehicleTypeStats['Motocicleta'] = ['ingresos' => 0, 'salidas' => 0];
if (!isset($vehicleTypeStats['Bicicleta'])) $vehicleTypeStats['Bicicleta'] = ['ingresos' => 0, 'salidas' => 0];


// 3. Actividad Diaria
$dailyAccessData = $access->getDailyAccessStats($startDate, $endDate);

// 4. Actividad por Hora
$hourlyAccessData = $access->getHourlyAccessStats($startDate, $endDate);

// --- Generación del HTML Dinámico ---
$htmlContent = file_get_contents('../../frontend/views/reportes_generales.php');
$dynamicContent = '';

// Inyectar valores de fecha en el formulario de filtro
$htmlContent = str_replace('name="startDate" value=""', 'name="startDate" value="' . htmlspecialchars($startDateInput) . '"', $htmlContent);
$htmlContent = str_replace('name="endDate" value=""', 'name="endDate" value="' . htmlspecialchars($endDateInput) . '"', $htmlContent);


// Bloque de métricas globales
$ocupacion_porcentaje = ($generalStats['capacidad_total'] > 0) ?
    number_format(($generalStats['ocupacion_actual'] / $generalStats['capacidad_total']) * 100, 2) . '%' : 'N/A';

$dynamicContent .= '
    <div class="row">
        <div class="col-md-4">
            <div class="card-metric">
                <i class="fas fa-arrow-alt-circle-down fa-2x text-success mb-2"></i>
                <h5>Vehículos Ingresados</h5>
                <div class="value">' . htmlspecialchars($generalStats['total_ingresos'] ?? 0) . '</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-metric">
                <i class="fas fa-arrow-alt-circle-up fa-2x text-danger mb-2"></i>
                <h5>Vehículos Salidos</h5>
                <div class="value">' . htmlspecialchars($generalStats['total_salidas'] ?? 0) . '</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-metric">
                <i class="fas fa-chart-pie fa-2x text-info mb-2"></i>
                <h5>Ocupación Actual</h5>
                <div class="value">' . htmlspecialchars($ocupacion_porcentaje) . '</div>
                <p class="mt-2 text-muted" style="font-size: 0.8rem;">(' . htmlspecialchars($generalStats['ocupacion_actual'] ?? 0) . ' de ' . htmlspecialchars($generalStats['capacidad_total'] ?? 0) . ' espacios ocupados)</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card-metric">
                <i class="fas fa-motorcycle fa-2x text-warning mb-2"></i>
                <h5>Ingresos de Motos</h5>
                <div class="value">' . htmlspecialchars($vehicleTypeStats['Motocicleta']['ingresos'] ?? 0) . '</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card-metric">
                <i class="fas fa-bicycle fa-2x text-secondary mb-2"></i>
                <h5>Ingresos de Bicicletas</h5>
                <div class="value">' . htmlspecialchars($vehicleTypeStats['Bicicleta']['ingresos'] ?? 0) . '</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card-chart">
                <h5><i class="fas fa-chart-area me-2 text-info"></i>Actividad Diaria (Ingresos vs. Salidas)</h5>
                <canvas id="dailyAccessChart"></canvas>';
                if (empty($dailyAccessData)) {
                    $dynamicContent .= '<div class="text-center py-4 text-muted">No hay datos para esta gráfica en el rango seleccionado.</div>';
                }
$dynamicContent .= '
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card-chart">
                <h5><i class="fas fa-chart-bar me-2 text-success"></i>Actividad por Hora del Día</h5>
                <canvas id="hourlyAccessChart"></canvas>';
                if (empty($hourlyAccessData)) {
                    $dynamicContent .= '<div class="text-center py-4 text-muted">No hay datos para esta gráfica en el rango seleccionado.</div>';
                }
$dynamicContent .= '
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card-chart">
                <h5><i class="fas fa-chart-pie me-2 text-danger"></i>Distribución de Accesos por Tipo de Vehículo</h5>
                <div class="chart-container-small-pie"> <canvas id="vehicleTypeChart"></canvas>
                </div>';
                if (empty($vehicleTypeStatsRaw)) {
                    $dynamicContent .= '<div class="text-center py-4 text-muted">No hay datos para esta gráfica en el rango seleccionado.</div>';
                }
$dynamicContent .= '
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Datos pasados desde PHP (convertidos a JSON para JS)
            const dailyAccessDataRaw = ' . json_encode($dailyAccessData ?? []) . ';
            const hourlyAccessDataRaw = ' . json_encode($hourlyAccessData ?? []) . ';
            const vehicleTypeStatsRaw = ' . json_encode($vehicleTypeStatsRaw ?? []) . '; // Usar raw para la gráfica de tipo de vehículo

            // --- Gráfica de Actividad Diaria ---
            if (dailyAccessDataRaw.length > 0) {
                const dailyDates = dailyAccessDataRaw.map(d => d.access_date);
                const dailyIngresos = dailyAccessDataRaw.map(d => parseInt(d.daily_ingresos));
                const dailySalidas = dailyAccessDataRaw.map(d => parseInt(d.daily_salidas));

                const dailyCtx = document.getElementById("dailyAccessChart").getContext("2d");
                new Chart(dailyCtx, {
                    type: "line",
                    data: {
                        labels: dailyDates,
                        datasets: [{
                            label: "Ingresos",
                            data: dailyIngresos,
                            borderColor: "#28a745",
                            backgroundColor: "rgba(40, 167, 69, 0.2)",
                            fill: true,
                            tension: 0.3
                        }, {
                            label: "Salidas",
                            data: dailySalidas,
                            borderColor: "#dc3545",
                            backgroundColor: "rgba(220, 53, 69, 0.2)",
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: "Número de Movimientos"
                                }
                            }
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: "Movimientos Diarios"
                            }
                        }
                    }
                });
            }

            // --- Gráfica de Actividad por Hora del Día ---
            if (hourlyAccessDataRaw.length > 0) {
                const hourlyLabels = Array.from({
                    length: 24
                }, (_, i) => `${i}:00`);
                const hourlyIngresos = Array(24).fill(0);
                const hourlySalidas = Array(24).fill(0);

                hourlyAccessDataRaw.forEach(item => {
                    const hour = parseInt(item.access_hour);
                    hourlyIngresos[hour] = parseInt(item.hourly_ingresos);
                    hourlySalidas[hour] = parseInt(item.hourly_salidas);
                });

                const hourlyCtx = document.getElementById("hourlyAccessChart").getContext("2d");
                new Chart(hourlyCtx, {
                    type: "bar",
                    data: {
                        labels: hourlyLabels,
                        datasets: [{
                            label: "Ingresos",
                            data: hourlyIngresos,
                            backgroundColor: "rgba(23, 162, 184, 0.7)",
                            borderColor: "#17a2b8",
                            borderWidth: 1
                        }, {
                            label: "Salidas",
                            data: hourlySalidas,
                            backgroundColor: "rgba(255, 193, 7, 0.7)",
                            borderColor: "#ffc107",
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: "Hora del Día"
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: "Número de Movimientos"
                                }
                            }
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: "Movimientos por Hora"
                            }
                        }
                    }
                });
            }

            // --- Gráfica de Distribución por Tipo de Vehículo ---
            if (vehicleTypeStatsRaw.length > 0) {
                const vehicleTypes = vehicleTypeStatsRaw.map(v => v.tipo);
                const vehicleIngresos = vehicleTypeStatsRaw.map(v => parseInt(v.ingresos));
                const vehicleSalidas = vehicleTypeStatsRaw.map(v => parseInt(v.salidas));
                const vehicleTotals = vehicleIngresos.map((ing, i) => ing + vehicleSalidas[i]);

                const vehicleTypeCtx = document.getElementById("vehicleTypeChart").getContext("2d");
                new Chart(vehicleTypeCtx, {
                    type: "doughnut",
                    data: {
                        labels: vehicleTypes,
                        datasets: [{
                            label: "Total Movimientos",
                            data: vehicleTotals,
                            backgroundColor: [
                                "#007bff", // Azul para Automóvil
                                "#ffc107", // Amarillo para Motocicleta
                                "#28a745", // Verde para Bicicleta
                                "#6c757d", // Gris para Oficial
                                "#fd7e14" // Naranja para Aula móvil
                            ],
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: "Distribución de Movimientos por Tipo de Vehículo"
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || "";
                                        if (label) {
                                            label += ": ";
                                        }
                                        if (context.parsed !== null) {
                                            const totalAll = vehicleTotals.reduce((sum, val) => sum + val, 0);
                                            label += context.parsed + " (" + ((context.parsed / totalAll) * 100).toFixed(2) + "%)";
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
';

$htmlContent = str_replace('<div id="generalReportsContent">', '<div id="generalReportsContent">' . $dynamicContent, $htmlContent);

echo $htmlContent;
?>