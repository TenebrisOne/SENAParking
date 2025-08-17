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
    <title>Reporte de Usuarios | SENAParking</title>
    <link rel="icon" type="x-icon" href="../public/images/favicon.ico">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/sityles_views.css">
    <style>
        .header-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem 0;
            margin-bottom: 2rem;
            border-radius: 8px;
        }

        .search-section,
        .table-section,
        .pagination-section {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .search-input {
            border-radius: 25px;
            padding: 0.75rem 1.5rem;
            border: 2px solid #e9ecef;
            transition: border-color 0.2s;
        }

        .search-input:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }

        .clear-search {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: none;
            color: #6c757d;
            cursor: pointer;
            display: none; /* Controlado por JS, o directamente por el controlador al generar la página */
        }

        .search-container {
            position: relative;
        }

        .no-results {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
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

        .btn-detail {
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem;
        }

        .pagination .page-link {
            border-radius: 0.5rem;
            margin: 0 0.2rem;
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
        }
    </style>
</head>

<body class="bg-light">
    <div id="header-container"></div>

    <div class="container-fluid">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <button class="btn btn-secondary" onclick="goBack()">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </button>
                <img src="../public/images/logo_sena.png" alt="Logo SENA" style="width: 80px;">
            </div>

            <div class="header-section text-center">
                <h1 class="mb-2"><i class="fas fa-file-alt me-3"></i>Reporte de Usuarios</h1>
                <p class="lead">Lista y gestión de usuarios registrados en el parqueadero.</p>
            </div>

            <div class="search-section">
                <form action="../../backend/controllers/ReportesUserController.php" method="GET" class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-3 mb-md-0">
                            <i class="fas fa-search me-2 text-primary"></i>
                            Buscar Usuarios
                        </h5>
                    </div>
                    <div class="col-md-4">
                        <div class="search-container">
                            <input type="text" class="form-control search-input" id="searchInput"
                                placeholder="Buscar por nombre, apellido o documento..." name="search" value="">
                            <button type="button" class="clear-search" id="clearSearch" onclick="clearSearch()">
                                <i class="fas fa-times"></i>
                            </button>
                            <button type="submit" class="btn btn-primary d-none">Buscar</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-section">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tipo Usuario</th>
                                <th>Documento</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Contacto</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody">
                            <tr><td colspan="7" class="text-center">Cargando usuarios...</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="no-results d-none" id="noResults">
                    <i class="fas fa-users-slash fa-3x mb-3"></i>
                    <h4>No se encontraron usuarios</h4>
                    <p>Intenta con otros términos de búsqueda o verifica los filtros.</p>
                </div>

                <nav aria-label="Page navigation" class="pagination-section">
                    <ul class="pagination justify-content-center" id="paginationControls">
                        </ul>
                </nav>
            </div>


            <div class="row mt-4 mb-5">
                <div class="col-12 text-center">
                    <a href="../../backend/controllers/GeneralReportController.php" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-chart-bar me-2"></i>Ver Reportes Generales
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="../public/js/scriptsDOM.js"></script>

    <script>
        // Funciones JS básicas para navegación y UI, el contenido dinámico lo inyecta el controlador PHP
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof loadHeader === 'function') {
                loadHeader();
            }

            const searchInput = document.getElementById('searchInput');
            const clearButton = document.getElementById('clearSearch');

            function toggleClearButton() {
                if (searchInput.value.length > 0) {
                    clearButton.style.display = 'block';
                } else {
                    clearButton.style.display = 'none';
                }
            }

            searchInput.addEventListener('input', toggleClearButton);
            toggleClearButton(); // Ejecutar al cargar para el valor inicial

            // Redirigir para recargar la página sin el término de búsqueda
            clearButton.addEventListener('click', function() {
                searchInput.value = '';
                // Simula un envío de formulario GET sin el parámetro 'search'
                window.location.href = window.location.pathname; // Va a la URL base del controlador
            });

            // Precargar el valor del buscador (si hay un término de búsqueda en la URL)
            const urlParams = new URLSearchParams(window.location.search);
            const searchTermParam = urlParams.get('search');
            if (searchTermParam) {
                searchInput.value = searchTermParam;
                toggleClearButton();
            }
        });

        function goBack() {
            window.history.back();
        }
    </script>
    <!-- Función para llamar al Header-->
    <script src="./../public/js/scriptsDOM.js"></script>

    <!-- script para que cuando se cierre la sesion refresque la ventana -->
    <script src="../public/js/ref_cierre.js"></script>

</body>

</html>