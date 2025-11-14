<?php
session_start();

if (isset($_SESSION['rol'])) {
    switch ($_SESSION['rol']) {
        case "admin":
            header("location: frontend/views/dashboard_admin.php");
            break;
        case "supervisor":
            header("location: frontend/views/dashboard_supervisor.php");
            break;
        case "guardia":
            header("location: frontend/views/dashboard_guardia.php");
            break;
        default:
            header("location: login.php");
            break;
    }
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="AdsoDeveloperSolutions801">
    <meta name="course" content="ADSO 2873801">
    <link rel="icon" type="image/x-icon" href="./frontend/public/images/favicon.ico">
    <link rel="stylesheet" href="./frontend/public/css/styles_validaciones.css">
    <link rel="stylesheet" href="./frontend/public/css/styles.css">
    <link rel="stylesheet" href="./frontend/public/css/loader.css">
    <link href="./frontend/public/css/bootstrap.min.css" rel="stylesheet">
    <meta name="theme-color" content="#000000">
    <meta http-equiv="refresh" content="60">
    <title>Login | SENAParking</title>
</head>

<body class="bg-login">

    <!-- Contenedor para el header -->
    <div id="header-containerIdx"></div>


    <!-- Logo SENA -->
    <img src="./frontend/public/images/logo_sena.png" alt="Logo SENA"
        style="position: absolute; top: 100px; right: 70px; width: 100px;">

    <!-- Contenedor de registro -->
    <div class="register-container">
        <form id="formulario" class="formulario">
            <div class="row mb-3">
                <div class="col-12"id="grupo__correo">
                    <label for="correo_electronico" class="form-label">Email:</label>
                    <input type="email" class="form-control formulario__input" name="correo" id="correo" placeholder="Email" required>
                    <p class="formulario__input-error">El correo solo puede contener letras, números, puntos, guiones y guión bajo.</p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12" id="grupo__password">
                    <label for="password" class="form-label">Contraseña:</label>
                    <input type="password" class="form-control formulario__input" name="password" id="password" placeholder="Contraseña" required>
                    <p class="formulario__input-error">La contraseña tiene que ser de 4 a 12 dígitos.</p>
                </div>
            </div>
            
        <form id="formulario" class="formulario" action="backend/controllers/LoginController.php" method="POST">
    <!-- ... tus campos ... -->
    <div class="row">
        <div class="col-12">
            <button type="submit" class="btn btn-dark py-2 btn-hover" id="singbtn">Ingresar</button>
        </div>
    </div>
    <a href="./forgot_password.html" class="text-muted mt-3" style="font-size: 14px;">¿Olvidaste tu contraseña?</a>
</form>

<!-- Aquí aparecerá el mensaje de error -->
<p id="error-message" class="text-danger mt-3" style="text-align:center; display:none;"></p>

    </div>


    <!-- Scripts -->
    <script src="./frontend/public/js/scriptsDOM.js"></script>
    <script src="./frontend/public/js/validacion_login.js"></script>

    <!-- Loader -->
<div id="loader-overlay">
  <div class="spinner"></div>
  <!-- Script loader-->
<script src="./frontend/public/js/loader.js"></script>
</div>
</body>

</html>