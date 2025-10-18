<?php
session_start();

if (!isset($_SESSION['rol'])) {
    header("location: ../../login.php");
    exit();
}

// Mostrar vista dependiendo del estado de la sesion
if ($_SESSION["rol"] != 1) {
    header("Location: ../../login.php");
}

// cargamos
require_once __DIR__ . '/../../backend/controllers/MostrarDatosController.php';
require_once('../../backend/config/conexion.php');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Definición de la codificación de caracteres para la página -->
    <meta charset="UTF-8">

    <!-- Definición del viewport para hacer la página responsive (adaptable a distintos tamaños de pantalla) -->
    <meta name="viewport" content="width=, initial-scale=1.0">

    <!-- Metadatos adicionales sobre el autor y el curso -->
    <meta name="author" content="AdsoDeveloperSolutions801">
    <meta name="course" content="ADSO 2873801">

    <!-- Favicon para el ícono en la pestaña del navegador -->
    <link rel="icon" type="x-icon" href="../public/images/favicon.ico">

    <!-- Enlace al archivo de estilo de Bootstrap (para diseño responsivo y componentes predefinidos) -->
    <!-- Enlace al archivo de Bootstrap para proporcionar estilos prediseñados -->
    <link rel="stylesheet" href="../public/css/bootstrap.min.css">

    <!-- Enlace al archivo de estilos personalizados -->
    <link rel="stylesheet" href="../public/css/styles_dashboard.css">

    <!-- Título de la página que aparecerá en la pestaña del navegador -->
    <title>Administrador DASHBOARD | SENAParking</title>
</head>

<body>

    <?php
    if (isset($_GET['mensaje'])) {
        echo "<script>
            alert('" . htmlspecialchars($_GET['mensaje']) . "');
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.pathname);
            }
        </script>";
    }
    ?>

    <!-- Contenedor donde se insertará el header dinámicamente -->
    <div id="header-container"></div>


    <!-- Contenedor principal con un diseño de rejilla (Bootstrap) -->
    <div class="container-fluid">
        <div class="row">
            <!-- Barra lateral (sidebar) de navegación para el administrador -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar sidebar-admin">
                <div class="sidebar-sticky">
                    <!-- Lista de navegación -->
                    <ul class="nav flex-column">
                        <!-- Primer ítem de la barra lateral, un botón con icono SVG -->
                        <li class="nav-item btn btn-primary mt-1">
                            <a class="nav-link" href="#">
                                <!-- Icono de inicio (home) utilizando SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-home">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                </svg>
                                Dashboard <span class="sr-only"></span>
                            </a>
                        </li>
                        <!-- Ítems similares con otros iconos y textos, cada uno corresponde a una opción en la barra lateral -->
                        <li class="nav-item btn btn-secondary mt-1">
                            <!--Cambio ruta localhost, se agrega SENAParking a la navegacion del boton-->
                            <a class="nav-link" href="/SENAParking/frontend/views/reg_userSystem.php">
                                <!-- Icono de usuarios -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-users">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                Gestión Usuarios
                            </a>
                        </li>
                        <!-- Se repiten ítems similares para las secciones de reportes y configuración -->
                        <li class="nav-item btn btn-secondary mt-1">
                            <a class="nav-link" href="/SENAParking/backend/controllers/ReportesUserController.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-bar-chart-2">
                                    <line x1="18" y1="20" x2="18" y2="10"></line>
                                    <line x1="12" y1="20" x2="12" y2="4"></line>
                                    <line x1="6" y1="20" x2="6" y2="14"></line>
                                </svg>
                                Reportes Generales
                            </a>
                        </li>

                        <!-- Item de navegación para cerrar sesión -->
                        <li class="nav-item btn btn-secondary mt-1">
                            <a class="nav-link" href="/SENAParking/logout.php">
                                <!-- Ícono de cerrar sesión usando SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-log-out">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                    <polyline points="16 17 21 12 16 7" />
                                    <line x1="21" y1="12" x2="9" y2="12" />
                                </svg>
                                Cerrar sesión
                            </a>
                        </li>


                        <!--<li class="nav-item btn btn-secondary mt-1">
                            <a class="nav-link" href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-settings">
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <path
                                        d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-1.82.33H15a1.65 1.65 0 0 0-1-1.51v-2a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0-2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33H19a1.65 1.65 0 0 0-1-1.51V15z">
                                    </path>
                                </svg>
                                Configuración
                            </a>
                        </li> -->


                    </ul>
                </div>
            </nav>

            <!-- Contenido principal donde se muestran las tarjetas de resumen y los reportes -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <!-- Barra de título -->
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2 bg-admin-header py-2 px-3 rounded">Panel del Administrador</h1>
                </div>

                <!-- Contenido del panel de administración -->
                <div id="dashboard-content">
                    <div class="row mb-4">
                        <!-- Tarjetas de resumen con formularios -->
                        <div class="col-md-3">
                            <form method="POST">
                                <input type="hidden" name="tipo" value="usuarios_sistema">
                                <button type="submit" class="card card-resumen-general bg-resumen-usuarios-sistema border-0 w-100">
                                    <div class="card-body">
                                        <h5 class="card-title">Usuarios del Sistema</h5>
                                        <p class="card-text font-weight-bold" style="font-size: 1.5em;">
                                            <?php echo isset($totalUsuariosSistema) ? $totalUsuariosSistema : 0; ?>
                                        </p>
                                    </div>
                                </button>
                            </form>
                        </div>

                        <div class="col-md-3">
                            <form method="POST">
                                <input type="hidden" name="tipo" value="vehiculos_parqueadero">
                                <button type="submit" class="card card-resumen-general bg-resumen-vehiculos-parqueadero border-0 w-100">
                                    <div class="card-body">
                                        <h5 class="card-title">Vehículos Parqueadero</h5>
                                        <p class="card-text font-weight-bold" style="font-size: 1.5em;">
                                            <?php echo isset($totalVehiculosParqueadero) ? $totalVehiculosParqueadero : 0; ?>
                                        </p>
                                    </div>
                                </button>
                            </form>
                        </div>

                        <div class="col-md-3">
                            <form method="POST">
                                <input type="hidden" name="tipo" value="accesos_hoy">
                                <button type="submit" class="card card-resumen-general bg-resumen-accesos-hoy border-0 w-100">
                                    <div class="card-body">
                                        <h5 class="card-title">Accesos Hoy</h5>
                                        <p class="card-text font-weight-bold" style="font-size: 1.5em;">
                                            <?php echo isset($accesosHoy) ? $accesosHoy : 0; ?>
                                        </p>
                                    </div>
                                </button>
                            </form>
                        </div>

                        <div class="col-md-3">
                            <form method="POST">
                                <input type="hidden" name="tipo" value="salidas_hoy">
                                <button type="submit" class="card card-resumen-general bg-resumen-salidas-hoy border-0 w-100">
                                    <div class="card-body">
                                        <h5 class="card-title">Salidas Hoy</h5>
                                        <p class="card-text font-weight-bold" style="font-size: 1.5em;">
                                            <?php echo isset($salidasHoy) ? $salidasHoy : 0; ?>
                                        </p>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Reportes generales dinámicos -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card card-reportes-generales">
                                <div class="card-header">
                                    <?php echo isset($titulo) ? htmlspecialchars($titulo) : 'Reportes Generales'; ?>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">Datos generados dinámicamente con base en la tarjeta seleccionada.</p>
                                    <?php
                                    if (isset($tabla) && !empty($tabla)) {
                                        include __DIR__ . '/tabla_dinamica.php';
                                    } else {
                                        echo '<p class="text-muted">Selecciona una tarjeta para mostrar un reporte detallado.</p>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filas para la gestión de usuarios y los reportes -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-gestion-usuarios">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Gestión de Usuarios del Sistema</h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">Administrar los usuarios con acceso al sistema (guardias, supervisores, administradores).</p>
                                <?php include 'tabla_usuarios.php'; ?>
                                <a href="/SENAParking/frontend/views/reg_userSystem.php" class="btn btn-registrar-usuario btn-sm mt-2">Registrar Nuevo Usuario</a>
                            </div>
                        </div>
                    </div>
                    <!-- Gestión de Usuarios del Parqueadero -->
                    <div class="col-md-6">
                        <div class="card card-gestion-usuarios">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Gestión de Usuarios Parqueadero</h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">Administrar los usuarios con acceso al parqueadero (servidor público, contratista,
                                    trabajador oficial, visitante autorizado, aprendiz).</p>
                                <?php include 'tabla_usuariosparqueadero.php'; ?>
                                <a href="/SENAParking/frontend/views/reg_userParking.php" class="btn btn-registrar-usuario btn-sm mt-2">Registrar Nuevo Usuario</a>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        </main>
    </div>
    </div>

    <!-- Función para llamar al Header dinámico -->
    <script src="./../public/js/scriptsDOM.js"></script>

    <!-- Scripts para las dependencias de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="../public/js/bootstrap.min.js"></script>

    <!-- Otro script personalizado -->
    <script src="../public/js/usuarios.js"></script>
    <script src="../public/js/usuariosParqueadero.js"></script>


    <!-- Script de tu dashboard dinámico -->
    <script src="../public/js/dashboard_admin.js"></script>

    <!-- script para que cuando se cierre la sesion refresque la ventana -->
    <script src="../public/js/ref_cierre.js"></script>

</body>

</html>