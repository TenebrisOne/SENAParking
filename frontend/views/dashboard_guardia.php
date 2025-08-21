<?php
session_start();

if (!isset($_SESSION['rol'])) {
    header("location: ../../login.php");
    exit();
}

// Mostrar vista dependiendo del estado de la sesion
if ($_SESSION["rol"] != 3) {
    header("Location: ../../login.php");
}

?>

<!DOCTYPE html>
<html lang="es"> <!-- Establece el idioma de la página web como español -->

<head>
    <!-- Metaetiquetas para el encabezado del documento -->
    <meta charset="UTF-8"> <!-- Establece la codificación de caracteres -->
    <meta name="viewport" content="width=, initial-scale=1.0"> <!-- Define el ancho de la vista para dispositivos móviles -->
    <meta name="author" content="AdsoDeveloperSolutions801"> <!-- Define al autor de la página -->
    <meta name="course" content="ADSO 2873801"> <!-- Define el curso -->

    <!-- Favicon que se muestra en la pestaña del navegador -->
    <link rel="icon" type="x-icon" href="../public/images/favicon.ico">

    <!-- Enlace al archivo de Bootstrap para proporcionar estilos prediseñados -->
    <link rel="stylesheet" href="../public/css/bootstrap.min.css">

    <!-- Enlace al archivo de estilos personalizados -->
    <link rel="stylesheet" href="../public/css/styles_dashboard.css">

    <!-- Título de la página -->
    <title>Guardia DASHBOARD | SENAParking</title>
</head>

<body>
    <!-- Contenedor donde se insertará el header dinámicamente -->
    <div id="header-container"></div>

    <div class="container-fluid"> <!-- Contenedor de toda la página -->
        <div class="row"> <!-- Fila principal con dos columnas: Sidebar y el contenido principal -->

            <!-- Sidebar (navegación lateral) -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar sidebar-guardia">
                <div class="sidebar-sticky"> <!-- Elemento que asegura que el sidebar se quede fijo cuando se hace scroll -->
                    <ul class="nav flex-column"> <!-- Lista de navegación -->
                        <!-- Item de navegación para el Dashboard -->
                        <li class="nav-item active">
                            <a class="nav-link" href="/SENAParking/frontend/views/dashboard_guardia.php">
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

                        <!-- Item de navegación para usuarios -->
                        <li class="nav-item">
                            <a class="nav-link" href="/frontend/views/reg_userParking.php">
                                <!-- Ícono de usuarios usando SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-users">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                Usuarios

                            </a>
                        </li>

                        <!-- Item de navegación para registrar acceso -->
                        <li class="nav-item">
                            <a class="nav-link" href="/SENAParking/frontend/views/crud_vehiculos.php">
                                <!-- Ícono de log-in usando SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-log-in">
                                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M14 12H3"></path>
                                </svg>
                                Registrar Acceso
                            </a>
                        </li>

                        <!-- Item de navegación para registrar salida -->
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="/frontend/views/"> -->
                        <!-- Ícono de log-out usando SVG -->
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-log-out">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"></path>
                                </svg>
                                Registrar Salida
                            </a>
                        </li> -->

                        <!-- Item de navegación para ver disponibilidad -->
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="#">
                                Ícono de lista usando SVG 
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-list">
                                    <line x1="8" y1="6" x2="21" y2="6"></line>
                                    <line x1="8" y1="12" x2="21" y2="12"></line>
                                    <line x1="8" y1="18" x2="21" y2="18"></line>
                                    <line x1="3" y1="6" x2="3" y2="6"></line>
                                    <line x1="3" y1="12" x2="3" y2="12"></line>
                                    <line x1="3" y1="18" x2="3" y2="18"></line>
                                </svg>
                                Disponibilidad
                            </a>
                        </li> -->

                        <!-- Elemento de navegación para cerrar sesión -->
                        <li class="nav-item">
                            <a class="nav-link" href="/SENAParking/logout.php"">
        <!-- Ícono de cerrar sesión usando SVG -->
        <svg xmlns=" http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
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
                    <h1 class="h2 bg-guardia-header py-2 px-3 rounded">Panel del Guardia</h1> <!-- Título principal del panel -->
                </div>

                <div id="dashboard-content">
                    <!-- Fila de tarjetas con la información relevante -->
                    <div class="row">
                        <!-- Tarjeta de Disponibilidad de Cupos -->
                        <div class="col-md-6">
                            <div class="card card-disponibilidad">
                                <div class="card-header">
                                    Disponibilidad de Cupos
                                </div>
                                <div class="card-body">
                                    <p><strong>Total de Cupos:</strong> <span id="total-cupos">100</span></p>
                                    <p><strong>Cupos Ocupados:</strong> <span class="text-danger" id="cupos-ocupados">65</span></p>
                                    <p><strong>Cupos Disponibles:</strong> <span class="text-success font-weight-bold" id="cupos-disponibles">35</span></p>
                                </div>
                            </div>
                        </div>

                        <!-- Tarjeta de Registro de Acceso -->
                        <div class="col-md-6">
                            <div class="card card-registro-acceso">
                                <div class="card-header">
                                    Registro de Acceso
                                </div>
                                <div class="card-body">
                                    <form id="form-acceso">
                                        <div class="form-group">
                                            <label for="placa-acceso">Placa del Vehículo:</label>
                                            <input type="text" class="form-control" id="placa-acceso" required> <!-- Campo para ingresar la placa -->
                                        </div>
                                        <button type="submit" class="btn btn-registro-acceso">Registrar Entrada</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Tarjeta de Registro de Salida -->
                        <div class="col-md-6">
                            <div class="card card-registro-salida">
                                <div class="card-header">
                                    Registro de Salida
                                </div>
                                <div class="card-body">
                                    <form id="form-salida">
                                        <div class="form-group">
                                            <label for="placa-salida">Placa del Vehículo:</label>
                                            <input type="text" class="form-control" id="placa-salida" required> <!-- Campo para ingresar la placa -->
                                        </div>
                                        <button type="submit" class="btn btn-registro-salida">Registrar Salida</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Tarjeta de Usuarios Registrados -->
                        <div class="col-md-6">
                            <div class="card card-usuarios">
                                <div class="card-header">
                                    Usuarios del Parqueadero
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">Lista de usuarios registrados (solo lectura).</p>
                                    <ul class="list-group">
                                        <li class="list-group-item">Usuario 1 - ABC-123</li>
                                        <li class="list-group-item">Usuario 2 - DEF-456</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Función para insertar el header dinámicamente -->
    <script src="./../public/js/scriptsDOM.js"></script>

    <!-- Cargar los scripts necesarios de jQuery y Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="/frontend/public/js/bootstrap.min.js"></script>
    <script src="script.js"></script> <!-- Script principal -->

    <!-- script para que cuando se cierre la sesion refresque la ventana -->
    <script src="../public/js/ref_cierre.js"></script>

</body>

</html>