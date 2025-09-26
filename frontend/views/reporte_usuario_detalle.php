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
    <title>Detalle de Usuario | SENAParking</title>
    <link rel="icon" type="x-icon" href="../public/images/favicon.ico">
    <!-- Enlace al archivo de Bootstrap para proporcionar estilos prediseñados -->
    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
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
            /* Tamaño máximo para la gráfica de tarta */
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
    </style>
</head>

<body class="bg-light">
    <div id="header-container"></div>

    <div class="container-fluid">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <button class="btn btn-secondary" onclick="goBack()">
                    <i class="fas fa-arrow-left me-2"></i>Volver a Usuarios
                </button>
                <img src="../public/images/logo_sena.png" alt="Logo SENA" style="width: 80px;">
            </div>

            <div class="header-section text-center">
                <h1 class="mb-2"><i class="fas fa-user-alt me-3"></i>Detalle de Usuario</h1>
                <p class="lead">Información detallada y registros de acceso del usuario.</p>
            </div>

            <div id="userDetailsContent">

            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="../public/js/scriptsDOM.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof loadHeader === 'function') {
                loadHeader();
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