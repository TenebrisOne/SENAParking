<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Usuario | SENAParking</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/sityles_views.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .header-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem 0;
            margin-bottom: 2rem;
            border-radius: 8px;
        }

        .card-custom {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .user-detail-header {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 1rem;
            color: #495057;
        }

        .user-info p {
            margin-bottom: 0.5rem;
        }

        .chart-container {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }

        .table-responsive {
            margin-top: 1rem;
        }

        .table thead th {
            background-color: #e9ecef;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .no-records {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
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
    </style>
</head>

<body class="bg-light">
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
                <a id="downloadPdfButton" href="#" class="btn btn-danger">
                    <i class="fas fa-file-pdf me-2"></i>Descargar PDF
                </a>
                <img src="../public/images/logo_sena.png" alt="Logo SENA" style="width: 80px;">
            </div>

            <div class="header-section text-center">
                <h1 class="mb-2"><i class="fas fa-user-alt me-3"></i>Detalle de Usuario</h1>
                <p class="lead">Información detallada y registros de acceso del usuario.</p>
            </div>

            <div id="userDetailsContent">
                <div class="card-custom text-center py-5">
                    <i class="fas fa-spinner fa-spin fa-3x mb-3 text-primary"></i>
                    <h4>Cargando detalles del usuario...</h4>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="../public/js/scriptsDOM.js"></script>

    <script>
        const userDetailsContent = document.getElementById('userDetailsContent');
        const loadingIndicator = document.getElementById('loadingIndicator');

        function showLoading(show) {
            loadingIndicator.style.display = show ? 'flex' : 'none';
        }

        async function loadUserDetails() {
            showLoading(true);
            const urlParams = new URLSearchParams(window.location.search);
            const userId = urlParams.get('id');

            if (!userId) {
                userDetailsContent.innerHTML = `
                    <div class="card-custom text-center py-5">
                        <i class="fas fa-exclamation-triangle fa-3x mb-3 text-warning"></i>
                        <h4>ID de usuario no proporcionado.</h4>
                        <p>Por favor, regrese a la lista de usuarios y seleccione uno.</p>
                        <button class="btn btn-primary mt-3" onclick="goBack()">Volver</button>
                    </div>
                `;
                showLoading(false);
                return;
            }

            try {
                const response = await fetch(`../../backend/controllers/UserDetailReportController.php?id=${encodeURIComponent(userId)}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();

                if (data.user) {
                    renderUserDetails(data);
                    document.getElementById('downloadPdfButton').href = `../../backend/controllers/UserDetailReportPDF.php?id=${encodeURIComponent(userId)}`;
                } else {
                    userDetailsContent.innerHTML = `
                        <div class="card-custom text-center py-5">
                            <i class="fas fa-exclamation-triangle fa-3x mb-3 text-warning"></i>
                            <h4>Usuario no encontrado</h4>
                            <p>El ID de usuario especificado no es válido o no existe.</p>
                            <button class="btn btn-primary mt-3" onclick="goBack()">Volver a la lista de usuarios</button>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error fetching user details:', error);
                userDetailsContent.innerHTML = `
                    <div class="card-custom text-center py-5">
                        <i class="fas fa-exclamation-circle fa-3x mb-3 text-danger"></i>
                        <h4>Error al cargar el detalle del usuario</h4>
                        <p>${error.message}</p>
                    </div>
                `;
            } finally {
                showLoading(false);
            }
        }

        function renderUserDetails(data) {
            const user = data.user;
            const accessStats = data.accessStats;
            const userAccesses = data.userAccesses;

            let accessTableRows = '';
            if (userAccesses && userAccesses.length > 0) {
                userAccesses.forEach(accessItem => {
                    accessTableRows += `
                        <tr>
                            <td>${accessItem.fecha_hora}</td>
                            <td>${accessItem.tipo_accion}</td>
                            <td>${accessItem.placa}</td>
                            <td>${accessItem.espacio_asignado || 'N/A'}</td>
                        </tr>
                    `;
                });
            } else {
                accessTableRows = `
                    <tr>
                        <td colspan="4" class="text-center py-3">
                            <div class="no-records">
                                <i class="fas fa-car-alt fa-2x mb-2"></i>
                                <p>No se encontraron registros de acceso para este usuario.</p>
                            </div>
                        </td>
                    </tr>
                `;
            }

            let chartHtml = '';
            if (accessStats.ingresos + accessStats.salidas > 0) {
                chartHtml = `<canvas id="accessChart"></canvas>`;
            } else {
                chartHtml = `<div class="text-center py-4"><i class="fas fa-chart-pie fa-3x mb-2 text-muted"></i><p>No hay datos de acceso para graficar.</p></div>`;
            }

            userDetailsContent.innerHTML = `
                <div class="card-custom">
                    <h5 class="user-detail-header"><i class="fas fa-id-card me-2 text-primary"></i>Datos del Usuario</h5>
                    <div class="user-info row">
                        <p class="col-md-6"><strong>Nombres:</strong> ${user.nombres_park}</p>
                        <p class="col-md-6"><strong>Apellidos:</strong> ${user.apellidos_park}</p>
                        <p class="col-md-6"><strong>Tipo de Usuario:</strong> ${user.tipo_user}</p>
                        <p class="col-md-6"><strong>Documento:</strong> ${user.tipo_documento} ${user.numero_documento}</p>
                        <p class="col-md-6"><strong>Edificio:</strong> ${user.edificio || 'N/A'}</p>
                        <p class="col-md-6"><strong>Contacto:</strong> ${user.numero_contacto || 'N/A'}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="card-custom">
                            <h5 class="user-detail-header"><i class="fas fa-chart-pie me-2 text-success"></i>Estadísticas de Acceso</h5>
                            <div class="chart-container">
                                ${chartHtml}
                            </div>
                            <div class="text-center mt-3">
                                <p><strong>Ingresos:</strong> ${accessStats.ingresos}</p>
                                <p><strong>Salidas:</strong> ${accessStats.salidas}</p>
                                <p><strong>Total Movimientos:</strong> ${accessStats.ingresos + accessStats.salidas}</p>
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
                                    <tbody>
                                        ${accessTableRows}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            if (accessStats.ingresos + accessStats.salidas > 0) {
                const ctx = document.getElementById("accessChart").getContext("2d");
                new Chart(ctx, {
                    type: "pie",
                    data: {
                        labels: ["Ingresos", "Salidas"],
                        datasets: [{
                            data: [accessStats.ingresos, accessStats.salidas],
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
                                            label += context.parsed + " (" + ((context.parsed / (accessStats.ingresos + accessStats.salidas)) * 100).toFixed(2) + "%)";
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
            loadUserDetails();
        });

        function goBack() {
            window.history.back();
        }
    </script>
</body>

</html>