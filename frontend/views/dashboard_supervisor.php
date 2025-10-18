<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // ✅ solo se ejecuta si no hay sesión activa
}
setlocale(LC_TIME, 'es_ES.UTF-8');
date_default_timezone_set('America/Bogota');

if (!isset($_SESSION['rol'])) {
    header("location: ../../login.php");
    exit();
}

// Mostrar vista dependiendo del estado de la sesion
if ($_SESSION["rol"] != 2) {
    header("Location: ../../login.php");
}

// cargamos
require_once __DIR__ . '/../../backend/controllers/MostrarDatosController.php';
require_once('../../backend/config/conexion.php');
?>

<!DOCTYPE html>
<html lang="es"> <!-- Define el idioma de la página como español -->

<head>
    <meta charset="UTF-8"> <!-- Define la codificación de caracteres como UTF-8 -->
    <meta name="viewport" content="width=, initial-scale=1.0"> <!-- Establece el ancho y escala de la vista para dispositivos móviles -->
    <meta name="author" content="AdsoDeveloperSolutions801"> <!-- Define el autor de la página -->
    <meta name="course" content="ADSO 2873801"> <!-- Define el curso asociado -->
    <!-- Favicon que aparece en la pestaña del navegador -->
    <link rel="icon" type="x-icon" href="../public/images/favicon.ico">
    <!-- Enlace al archivo CSS de Bootstrap para aplicar estilos prediseñados -->
    <!-- Enlace al archivo de Bootstrap para proporcionar estilos prediseñados -->
    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
    <!-- Enlace a los estilos personalizados del proyecto -->
    <link rel="stylesheet" href="../public/css/styles_dashboard.css">
    <title>Supervisor DASHBOARD | SENAParking</title> <!-- Título que aparece en la pestaña del navegador -->
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

    <div class="container-fluid"> <!-- Contenedor que ocupa todo el ancho disponible -->
        <div class="row"> <!-- Fila que contiene las columnas para el sidebar y el contenido principal -->

            <!-- Sidebar (navegación lateral) del supervisor -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar sidebar-supervisor">
                <div class="sidebar-sticky"> <!-- Estilo que asegura que el sidebar permanezca fijo durante el scroll -->
                    <ul class="nav flex-column"> <!-- Lista de navegación -->
                        <!-- Elemento de navegación para el Dashboard -->
                        <li class="nav-item active">
                            <a class="nav-link" href="#">
                                <!-- Ícono de Home (Casa) usando SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-home">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                </svg>
                                Dashboard <span class="sr-only"></span>
                            </a>
                        </li>
                        <!-- Elemento de navegación para registrar guardias -->
                        <li class="nav-item">
                            <a class="nav-link" href="/SENAParking/frontend/views/reg_userSystem.php">
                                <!-- Ícono de añadir usuario usando SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-user-plus">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="8.5" cy="7" r="3.5"></circle>
                                    <line x1="20" y1="8" x2="20" y2="14"></line>
                                    <line x1="23" y1="11" x2="17" y2="11"></line>
                                </svg>
                                Registrar Guardia
                            </a>
                        </li>
                        <!-- Elemento de navegación para los informes de los guardias -->
                        <li class="nav-item">
                            <a class="nav-link" href="/SENAParking/frontend/views/reportes.php">
                                <!-- Ícono de archivo de texto usando SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-file-text">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                                Informes Guardias
                            </a>
                        </li>
                        <!-- Elemento de navegación para la disponibilidad -->
                        <!-- <li class="nav-item"> -->
                        <!-- <a class="nav-link" href="#"> -->
                        <!-- Ícono de lista usando SVG -->
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" -->
                        <!-- fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" -->
                        <!-- stroke-linejoin="round" class="feather feather-list"> -->
                        <!-- <line x1="8" y1="6" x2="21" y2="6"></line> -->
                        <!-- <line x1="8" y1="12" x2="21" y2="12"></line> -->
                        <!-- <line x1="8" y1="18" x2="21" y2="18"></line> -->
                        <!-- <line x1="3" y1="6" x2="3" y2="6"></line> -->
                        <!-- <line x1="3" y1="12" x2="3" y2="12"></line> -->
                        <!-- <line x1="3" y1="18" x2="3" y2="18"></line> -->
                        <!-- </svg> -->
                        <!-- Disponibilidad -->
                        <!-- </a> -->
                        <!-- </li> -->
                        <!-- Elemento de navegación para cerrar sesión -->
                        <li class="nav-item">
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
                    </ul>
                </div>
            </nav>

            <!-- Contenido principal de la página -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2 bg-supervisor-header py-2 px-3 rounded">Panel del Supervisor</h1> <!-- Título principal del panel -->
                </div>

                <div id="dashboard-content">
                    <!-- Resumen de los cupos disponibles -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card card-cupos-resumen">
                                <div class="card-header">
                                    Resumen de Cupos
                                </div>
                                <div class="card-body">
                                    <!-- Información de los cupos disponibles -->
                                    <!-- <p class="mb-1"><strong class="text-secondary">Total:</strong> <span class="font-weight-bold" id="total-cupos-sup">100</span></p>
                                    <p class="mb-1"><strong class="text-danger">Ocupados:</strong> <span class="font-weight-bold" id="cupos-ocupados-sup">65</span></p>
                                    <p class="mb-0"><strong class="text-success">Disponibles:</strong> <span class="font-weight-bold" id="cupos-disponibles-sup">35</span></p> -->


                                    <div class="card-body"
                                        <div class="row mb-4">
                                        <div class="card card-resumen-general border-0 w-100" style="background: linear-gradient(135deg, #4CAF50, rgba(124, 199, 86, 1));">
                                            <div class="card-body" style="color: #ffffffff;">
                                                <h5 class="card-title">Usuarios Parqueadero</h5>
                                                <p class="card-text font-weight-bold" style="font-size: 1.5em;">
                                                    <?php echo isset($totalUsuariosParqueadero) ?       $totalUsuariosParqueadero : 0; ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card card-resumen-general border-0 w-100" style="background: linear-gradient(135deg, #FFD700, #FFEA80);">
                                        <div class="card-body" style="color: #71277A;">
                                            <h5 class="card-title">Accesos Hoy</h5>
                                            <p class="card-text font-weight-bold" style="font-size: 1.5em;">
                                                <?php echo isset($accesosHoy) ? $accesosHoy : 0; ?>
                                            </p>
                                        </div>
                                    </div>


                                    <div class="card card-resumen-general border-0 w-100" style="background: linear-gradient(135deg, #71277A, #9B479D);">
                                        <div class="card-body" style="color: #FFEA80;">
                                            <h5 class="card-title">Salidas Hoy</h5>
                                            <p class="card-text font-weight-bold" style="font-size: 1.5em;">
                                                <?php echo isset($salidasHoy) ? $salidasHoy : 0; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Código agregado por Cristian 👀⚠️🚧 -->
                        <!-- Detalles adicionales de las actividades recientes -->
                        <div class="col-md-8">
                            <div class="card card-cupos-resumen">
                                <div class="card-header">
                                    Actividades de Usuarios
                                </div>
                                <div class="card-body">
                                    <!-- Información de los cupos disponibles -->
                                    <?php if (!empty($actividades)): ?>
                                        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                                            <thead>
                                                <tr>
                                                    <th style=" background-color: #f2f2f2; padding: 10px; border-bottom: 1px solid #ccc; text-align: left;">Usuario</th>
                                                    <th style=" background-color: #f2f2f2; padding: 10px; border-bottom: 1px solid #ccc; text-align: left;">Acción</th>
                                                    <th style=" background-color: #f2f2f2; padding: 10px; border-bottom: 1px solid #ccc; text-align: left;">Fecha </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($actividades as $actividad): ?>
                                                    <tr>
                                                        <td style="padding: 10px; border-bottom: 1px solid #ccc; text-align: left;"><?= htmlspecialchars($actividad['Usuario']) ?></td>
                                                        <td style="padding: 10px; border-bottom: 1px solid #ccc; text-align: left;"><?= htmlspecialchars($actividad['Accion']) ?></td>
                                                        <td style="padding: 10px; border-bottom: 1px solid #ccc; text-align: left;"><?= strftime("%d de %B de %Y - %I:%M %p", strtotime($actividad['Fecha'])) ?>

                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php else: ?>
                                        <p>No hay actividades registradas.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>



                    </div>

                    <!-- Informes de actividades de los guardias -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-gestion-usuarios">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Gestión de Usuarios del Sistema</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">Administrar los usuarios con acceso al sistema (guardas de seguridad).</p>
                                    <?php include 'tabla_usuarios.php'; ?>
                                </div>
                            </div>
                        </div>
                        <!-- Detalles adicionales de la disponibilidad de cupos -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    Disponibilidad Detallada
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">Información detallada sobre la disponibilidad de cupos.</p>
                                    <!-- Lista con información de las zonas y cupos disponibles -->
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Zona A
                                            <span class="badge badge-primary badge-pill">10/20</span> <!-- Cupos de la zona A -->
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Zona B
                                            <span class="badge badge-warning badge-pill">15/30</span> <!-- Cupos de la zona B -->
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Motos
                                            <span class="badge badge-success badge-pill">5/10</span> <!-- Cupos para motos -->
                                        </li>
                                    </ul>
                                    <!-- Botón para ver más detalles sobre cada zona -->
                                    <!--<button class="btn btn-sm btn-outline-secondary mt-2">Ver Detalles por Zona</button>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <!-- Fila de tarjetas para el registro de guardia y los informes -->
        <!--<div class="row">
                        <!- Registro de un nuevo guardia 
                        <div class="col-md-6">
                            <div class="card card-registro-guardia">
                                <div class="card-header">
                                    Registro de Guardia
                                </div>
                                <div class="card-body">
                                    <!-Formulario para registrar un guardia 
                                    <form id="form-registro-guardia">
                                        <div class="form-group">
                                            <label for="nombre-guardia">Nombre:</label>
                                            <input type="text" class="form-control" id="nombre-guardia" required> <!- Campo de texto para el nombre 
                                        </div>
                                        <div class="form-group">
                                            <label for="usuario-guardia">Usuario:</label>
                                            <input type="text" class="form-control" id="usuario-guardia" required> <!- Campo de texto para el usuario
                                        </div>
                                        <div class="form-group">
                                            <label for="password-guardia">Contraseña:</label>
                                            <input type="password" class="form-control" id="password-guardia" required> <!- Campo para ingresar la contraseña 
                                        </div>
                                        <button type="submit" class="btn btn-registro-guardia">Registrar Guardia</button> <!- Botón de registro -->
        <!-- </form>
                                </div>
                            </div>
                        </div> -->
        </main>
    </div>
    </div>

    <!-- Función para llamar al Header dinámicamente -->
    <script src="./../public/js/scriptsDOM.js"></script>

    <!-- Otros scripts necesarios para la funcionalidad de la página -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="/frontend/public/js/bootstrap.min.js"></script>
    <script src="script.js"></script>

    <!-- script para que cuando se cierre la sesion refresque la ventana -->
    <script src="../public/js/ref_cierre.js"></script>
</body>

</html>