<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="AdsoDeveloperSolutions801">
    <meta name="course" content="ADSO 2873801">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon (ícono en la pestaña del navegador) -->
    <link rel="icon" type="x-icon" href="./frontend/public/images/favicon.ico">
    <!-- Enlace a Bootstrap -->
    <link href="./frontend/public/css/bootstrap.min.css" rel="stylesheet">
    <!-- Enlace a estilos personalizados -->
    <link rel="stylesheet" href="./frontend/public/css/styles.css">
    <title>SENAPARKING</title>
</head>

<body class="bg-index">
    
    <!-- Contenedor donde se insertará el header -->
    <div id="header-containerIdx"></div>


    <!-- Logo SENA -->
    <img src="./frontend/public/images/logo_sena.png" alt="Logo SENA"
        style="position: absolute; top: 100px; right: 70px; width: 100px;">
    </div>

    <!-- Contenedor principal clase de Bootstrap para ocupar todo el ancho del viewport -->
    <div class="container-fluid background">
        <!-- Fila que ocupa el 100% de la altura de la ventana (vh-100), 
    centra su contenido tanto vertical como horizontalmente -->
        <div class="row vh-100 justify-content-center align-items-center">

            <div class="col-md-4 login-container text-center p-4">
                <div class="d-flex justify-content-center gap-5">
                    <!-- Botón(negro) con padding vertical de 3 y un efecto hover personalizado -->
                    <button type="button" onclick="window.location.href='./login.php'" class="btn btn-dark py-2 btn-hover">⭐ Iniciar Sesión</button>
                    <!-- Botón(verde) con el mismo estilo anterior -->
                    <button class="btn btn-success py-2 btn-hover">⭐ ¿Ayuda?</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Función para llamar al Header-->
    <script src="./frontend/public/js/scriptsDOM.js"></script>

</body>

</html>

<?php

// Obtiene el parámetro "url" de la URL
$url = $_GET['url'] ?? 'inicio'; // Si no hay "url", carga la página de inicio

// Sanitiza la URL (evita cargar archivos maliciosos)
$url = trim($url, '/');
$url = explode('/', $url)[0]; // Solo tomamos la primera parte de la URL

// Ruta al archivo de vista
$ruta = "/frontend/views/$url.php";



// Verifica si existe la vista, si no, muestra error 404
if (file_exists($ruta)) {
    include $ruta;
} else {
    include "./404.html";
}

?>