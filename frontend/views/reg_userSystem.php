<?php
session_start();

if (!isset($_SESSION['rol'])) {
    header("location: ../login.php");
    exit();
}
if ($_SESSION['rol'] == 3) {
    header("location: ../dashboard_guardia.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <meta name="author" content="AdsoDeveloperSolutions801">
    <meta name="course" content="ADSO 2873801">
    <title>Registro Usuarios del Sistema | SENAParking</title>
    <link href="../public/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="x-icon" href="../public/images/favicon.ico">
    <link rel="stylesheet" href="../public/css/sityles_views.css">
    <link rel="stylesheet" href="../public/css/styles_validaciones.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <!-- Contenedor donde se insertará el header -->
    <div id="header-container"></div>

    <!-- Logo SENA -->
    <img src="../public/images/logo_sena.png" alt="Logo SENA"
        style="position: absolute; top: 100px; right: 70px; width: 100px;">

    <!--Carros decorativos-->
    <img src="../public/images/backgrounds/Image.png" alt="Auto decorativo izquierdo" class="car-left">
    <img src="../public/images/backgrounds/Image.png" alt="Auto decorativo inferior izquierdo" class="car-bottom-left">

    <!-- Flecha de retroceso -->
    <div class="col-2 col-md-1 text-start">
        <div class="back-arrow" onclick="goBack()">
            <i class="fas fa-arrow-left"></i>
            <a class="nav-link" href="/SENAParking/frontend/views/dashboard_admin.php"></a>
        </div>
    </div>

    <!-- Formulario -->
    <div class="register-container">
        <h2><center>Registrar Usuario Sistema</center></h2>
        <form id="formulario" class="formulario">
            <div class="row mb-3">
                <div class="col-12" id="grupo__nombre">
                    <label for="nombre" class="form-label">Nombres:</label>
                    <input type="text" class="form-control formulario__input" name="nombre" id="nombre"
                        placeholder="Ingrese sus nombres" required>
                    <p class="formulario__input-error">El nombre tiene que ser de 4 a 16 dígitos y solo puede contener letras.</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12" id="grupo__apellido">
                    <label for="apellido" class="form-label">Apellidos:</label>
                    <input type="text" class="form-control formulario__input" name="apellido" id="apellido"
                        placeholder="Ingrese sus apellidos" required>
                    <p class="formulario__input-error">El apellido tiene que ser de 4 a 16 dígitos y solo puede contener letras.</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12" id="grupo__tipdoc">
                    <label for="tipdoc" class="form-label">Tipo de Documento:</label>
                    <select class="form-select" id="tipdoc" name="tipdoc" required>
                        <option value="" selected disabled>Seleccione el tipo de documento</option>
                        <option value="Cedula_ciudadania">Cédula de Ciudadanía</option>
                        <option value="Cedula_extranjeria">Cédula de Extranjería</option>
                        <option value="Pasaporte">Pasaporte</option>
                        <option value="Otros">Otros</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12" id="grupo__documento">
                    <label for="cedula" class="form-label">Documento:</label>
                    <input type="text" class="form-control formulario__input" name="documento" id="documento"
                        placeholder="Ingrese el Documento" required>
                    <p class="formulario__input-error">El documento debe tener entre 6 y 10 dígitos y solo puede contener números.</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12" id="grupo__rol">
                    <label for="rol" class="form-label">Rol:</label>
                    <select class="form-select" id="rol" name="rol" required>
                        <option value="" selected disabled>Selecciona el rol</option>
                        <?php if($_SESSION['rol'] == 1):?>
                        <option value="1">Administrador</option>
                        <option value="2">Supervisor</option>
                        <?php endif;?>
                        <option value="3">Guarda de Seguridad</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12" id="grupo__correo">
                    <label for="email" class="form-label">Correo Electrónico:</label>
                    <input type="email" class="form-control formulario__input" name="correo" id="correo"
                        placeholder="Email" required>
                    <p class="formulario__input-error">El correo solo puede contener letras, números, puntos, guiones y guión bajo.</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12" id="grupo__numero">
                    <label for="numero" class="form-label">Número de Contacto:</label>
                    <input type="text" class="form-control formulario__input" name="numero" id="numero"
                        placeholder="Número" required>
                    <p class="formulario__input-error">El número tiene que ser de 7 a 14 dígitos y solo puede contener números.</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12" id="grupo__usuario">
                    <label for="usuario" class="form-label">Nombre de Usuario:</label>
                    <input type="text" class="form-control formulario__input" name="usuario" id="usuario"
                        placeholder="Usuario" required>
                    <p class="formulario__input-error">El nombre tiene que ser de 4 a 16 dígitos y solo puede contener letras y números.</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12" id="grupo__contrasena">
                    <label for="contrasena" class="form-label">Contraseña:</label>
                    <input type="password" class="form-control formulario__input" name="contrasena" id="contrasena"
                        placeholder="Password" required>
                    <p class="formulario__input-error">La contraseña tiene que ser de 4 a 12 dígitos.</p>
                </div>
            </div>

            <form action="controllers/ActividadController.php" method="GET">
                <input type="hidden" name="accion" value="crear_userSys">
                <button type="submit" class="btn btn-confirmar">Registrar</button>
            </form>

        </form>
    </div>
    
    
    <!-- Cargar el header-->
    <script src="./../public/js/scriptsDOM.js"></script>
    <script src="../public/js/validacion_user_system.js"></script>

    <!-- script para que cuando se cierre la sesion refresque la ventana -->
    <script src="../public/js/ref_cierre.js"></script>

</body>

</html>