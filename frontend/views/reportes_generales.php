<?php
session_start();

if (!isset($_SESSION['rol'])) {
    header("location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes Generales | SENAParking</title>
        <meta name="author" content="AdsoDeveloperSolutions801"> <!-- Define al autor de la página -->
    <meta name="course" content="ADSO 2873801"> <!-- Define el curso -->

    <!-- Favicon que se muestra en la pestaña del navegador -->
    <link rel="icon" type="x-icon" href="../../frontend/public/images/favicon.ico">

    <!-- Enlace al archivo de Bootstrap para proporcionar estilos prediseñados -->
    <link rel="stylesheet" href="../../frontend/public/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/sityles_views.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .header-section {
            background: linear-gradient(135deg, #4CAF50 0%, #4CAF50 100%);
            color: white;
            padding: 1.5rem 0;
            margin-bottom: 2rem;
            border-radius: 8px;
        }

        .card-metric {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .card-metric h5 {
            font-size: 1rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .card-metric .value {
            font-size: 2rem;
            font-weight: bold;
            color: #343a40;
        }

        .card-chart {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .date-filter-section {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        /* Loading indicator */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        /* Small pie chart container */
        .chart-container-small-pie {
            position: relative;
            width: 100%;
            max-width: 450px; /* Adjust this value as needed */
            margin: 0 auto;
            padding: 10px;
        }

        #vehicleTypeChart {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body class="bg-light">

    <!-- Contenedor donde se insertará el header dinámicamente -->
    <div id="header-container"></div>


    <div id="loadingIndicator" class="loading-overlay" style="display: none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>
    <div id="header-container"></div>

    <div class="container-fluid">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <button class="btn btn-secondary" onclick="goBack()">
                    <i class="fas fa-arrow-left me-2"></i>Volver a Usuarios
                </button>
                <img src="../../frontend/public/images/logo_sena.png" alt="Logo SENA" style="width: 80px;">
            </div>

            <div class="header-section text-center">
                <h1 class="mb-2"><i class="fas fa-chart-line me-3"></i>Reportes Generales</h1>
                <p class="lead">Métricas y estadísticas globales del parqueadero.</p>
            </div>

            <div class="date-filter-section">
                <h5><i class="fas fa-calendar-alt me-2 text-primary"></i>Filtrar por Fecha</h5>
                <form id="filterForm" class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label for="startDate" class="form-label">Fecha Inicio</label>
                        <input type="date" class="form-control" id="startDate" name="startDate" value="">
                    </div>
                    <div class="col-md-5">
                        <label for="endDate" class="form-label">Fecha Fin</label>
                        <input type="date" class="form-control" id="endDate" name="endDate" value="">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Aplicar Filtro</button>
                    </div>
                    <div class="col-md-2">
                        <a id="downloadPdfBtn" href="#" class="btn btn-danger w-100">
                            <i class="fas fa-file-pdf me-2"></i>Descargar PDF
                        </a>
                    </div>
                </form>
            </div>

            <div id="generalReportsContent">
                <div class="text-center py-5">
                </div>
            </div>

            <div class="row mt-4 mb-5">
                <div class="col-12 text-center">
                    <a href="../../frontend/views/reporte_usuarios.php" class="btn btn-secondary btn-lg px-5">
                        <i class="fas fa-users me-2"></i>Volver a Reporte de Usuarios
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const generalReportsContent = document.getElementById('generalReportsContent');
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        const filterForm = document.getElementById('filterForm');
        const downloadPdfBtn = document.getElementById('downloadPdfBtn');
        const loadingIndicator = document.getElementById('loadingIndicator');

        let dailyChartInstance = null;
        let hourlyChartInstance = null;
        let vehicleTypeChartInstance = null;

        function showLoading(show) {
            loadingIndicator.style.display = show ? 'flex' : 'none';
        }
        </script>


    <div class="row">
      <div class="col-md-6">
        <canvas id="graficoEntradasSalidas"></canvas>
      </div>
      <div class="col-md-6">
        <canvas id="graficoReservados"></canvas>
      </div>
    </div>

    
    <script>

    // Cargar el contenido del header desde un archivo HTML externo
fetch("../../frontend/views/layouts/header.php")

    .then(response => response.text())
    .then(data => {
        document.getElementById('header-container').innerHTML = data;
    })
    .catch(error => console.error('Error al cargar el header:', error));
    

// Función para retroceder en el historial del navegador
function goBack() {
    window.history.back();

}

    </script>

    <!-- Botón para volver a detalle del usuario -->
  




  <script>
    //const ctx1 = document.getElementById('graficoEntradasSalidas').getContext('2d');
    const grafico1 = new Chart(ctx1, {
  type: 'bar',
  data: {
    labels: ['Entradas', 'Salidas'],
    datasets: [{
      label: 'Cantidad de Vehículos',
      data: [120, 100],
      backgroundColor: ['#0d6efd', '#dc3545']
    }]
  },
  options: {
    responsive: true,
    plugins: {
      title: { display: true, text: 'Flujo de Vehículos' }
    }
  }
}); 

        async function loadGeneralReports(startDate = '', endDate = '') {
            showLoading(true);
            try {
                const queryParams = new URLSearchParams();
                if (startDate) queryParams.append('startDate', startDate);
                if (endDate) queryParams.append('endDate', endDate);

                const response = await fetch(`../../backend/controllers/GeneralReportController.php?${queryParams.toString()}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();

                renderGeneralReports(data);

                // Update PDF download link
                let pdfHref = `../../backend/controllers/GeneralReportPDF.php?${queryParams.toString()}`;
                downloadPdfBtn.href = pdfHref;

            } catch (error) {
                console.error('Error fetching general reports:', error);
                generalReportsContent.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-exclamation-circle fa-3x mb-3 text-danger"></i>
                        <h4>Error al cargar los reportes generales</h4>
                        <p>${error.message}</p>
                    </div>
                `;
            } finally {
                showLoading(false);
            }
        }

        function renderGeneralReports(data) {
            const generalStats = data.generalStats;
            const vehicleTypeStatsRaw = data.vehicleTypeStatsRaw;
            const dailyAccessData = data.dailyAccessData;
            const hourlyAccessData = data.hourlyAccessData;

            const ocupacion_porcentaje = generalStats.capacidad_total > 0 ?
                `${((generalStats.ocupacion_actual / generalStats.capacidad_total) * 100).toFixed(2)}%` : 'N/A';

            generalReportsContent.innerHTML = `
                <div class="row">
                    <div class="col-md-4">
                        <div class="card-metric">
                            <i class="fas fa-arrow-alt-circle-down fa-2x text-success mb-2"></i>
                            <h5>Vehículos Ingresados</h5>
                            <div class="value">${generalStats.total_ingresos}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-metric">
                            <i class="fas fa-arrow-alt-circle-up fa-2x text-danger mb-2"></i>
                            <h5>Vehículos Salidos</h5>
                            <div class="value">${generalStats.total_salidas}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-metric">
                            <i class="fas fa-chart-pie fa-2x text-info mb-2"></i>
                            <h5>Ocupación Actual</h5>
                            <div class="value">${ocupacion_porcentaje}</div>
                            <p class="mt-2 text-muted" style="font-size: 0.8rem;">(${generalStats.ocupacion_actual} de ${generalStats.capacidad_total} espacios ocupados)</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card-metric">
                            <i class="fas fa-motorcycle fa-2x text-warning mb-2"></i>
                            <h5>Ingresos de Motos</h5>
                            <div class="value">${data.vehicleTypeStats.Motocicleta.ingresos}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card-metric">
                            <i class="fas fa-bicycle fa-2x text-secondary mb-2"></i>
                            <h5>Ingresos de Bicicletas</h5>
                            <div class="value">${data.vehicleTypeStats.Bicicleta.ingresos}</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="card-chart">
                            <h5><i class="fas fa-chart-area me-2 text-info"></i>Actividad Diaria (Ingresos vs. Salidas)</h5>
                            <canvas id="dailyAccessChart"></canvas>
                            ${dailyAccessData.length === 0 ? '<div class="text-center py-4 text-muted">No hay datos para esta gráfica en el rango seleccionado.</div>' : ''}
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card-chart">
                            <h5><i class="fas fa-chart-bar me-2 text-success"></i>Actividad por Hora del Día</h5>
                            <canvas id="hourlyAccessChart"></canvas>
                            ${hourlyAccessData.length === 0 ? '<div class="text-center py-4 text-muted">No hay datos para esta gráfica en el rango seleccionado.</div>' : ''}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card-chart">
                            <h5><i class="fas fa-chart-pie me-2 text-danger"></i>Distribución de Accesos por Tipo de Vehículo</h5>
                            <div class="chart-container-small-pie">
                                <canvas id="vehicleTypeChart"></canvas>
                            </div>
                            ${vehicleTypeStatsRaw.length === 0 ? '<div class="text-center py-4 text-muted">No hay datos para esta gráfica en el rango seleccionado.</div>' : ''}
                        </div>
                    </div>
                </div>
            `;

            // Destroy previous chart instances to prevent memory leaks/errors
            if (dailyChartInstance) dailyChartInstance.destroy();
            if (hourlyChartInstance) hourlyChartInstance.destroy();
            if (vehicleTypeChartInstance) vehicleTypeChartInstance.destroy();

            // Render Daily Access Chart
            if (dailyAccessData.length > 0) {
                const dailyDates = dailyAccessData.map(d => d.access_date);
                const dailyIngresos = dailyAccessData.map(d => parseInt(d.daily_ingresos));
                const dailySalidas = dailyAccessData.map(d => parseInt(d.daily_salidas));

                const dailyCtx = document.getElementById("dailyAccessChart").getContext("2d");
                dailyChartInstance = new Chart(dailyCtx, {
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

            // Render Hourly Access Chart
            if (hourlyAccessData.length > 0) {
                const hourlyLabels = Array.from({
                    length: 24
                }, (_, i) => `${i}:00`);
                const hourlyIngresos = Array(24).fill(0);
                const hourlySalidas = Array(24).fill(0);

                hourlyAccessData.forEach(item => {
                    const hour = parseInt(item.access_hour);
                    hourlyIngresos[hour] = parseInt(item.hourly_ingresos);
                    hourlySalidas[hour] = parseInt(item.hourly_salidas);
                });

                const hourlyCtx = document.getElementById("hourlyAccessChart").getContext("2d");
                hourlyChartInstance = new Chart(hourlyCtx, {
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

            // Render Vehicle Type Chart
            if (vehicleTypeStatsRaw.length > 0) {
                const vehicleTypes = vehicleTypeStatsRaw.map(v => v.tipo);
                const vehicleIngresos = vehicleTypeStatsRaw.map(v => parseInt(v.ingresos));
                const vehicleSalidas = vehicleTypeStatsRaw.map(v => parseInt(v.salidas));
                const vehicleTotals = vehicleIngresos.map((ing, i) => ing + vehicleSalidas[i]);

                const vehicleTypeCtx = document.getElementById("vehicleTypeChart").getContext("2d");
                vehicleTypeChartInstance = new Chart(vehicleTypeCtx, {
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
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (typeof loadHeader === 'function') {
                loadHeader();
            }

            // Set default date range to last 30 days
            const today = new Date();
            const thirtyDaysAgo = new Date(today);
            thirtyDaysAgo.setDate(today.getDate() - 30);

            startDateInput.value = thirtyDaysAgo.toISOString().split('T')[0];
            endDateInput.value = today.toISOString().split('T')[0];

            // Initial load with default dates
            loadGeneralReports(startDateInput.value, endDateInput.value);

            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const startDate = startDateInput.value;
                const endDate = endDateInput.value;
                loadGeneralReports(startDate, endDate);
            });
        });

        function goBack() {
            window.history.back();
        }
    </script>
</body>

</html>