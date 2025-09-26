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
    <!-- Favicon (ícono en la pestaña del navegador) -->
    <link rel="icon" type="x-icon" href="../public/images/favicon.ico">
    <!-- Enlace a Bootstrap -->
    <link href="../public/css/bootstrap.min.css" rel="stylesheet">
    <!-- Enlace a estilos personalizados -->
    <link rel="stylesheet" href="../public/css/sityles_views.css">
    <link rel="stylesheet" href="../public/css/styles_validaciones.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Registro de Usuarios | SENAParking</title>
</head>

<body>
    <!-- Contenedor donde se insertará el header -->
    <div id="header-container"></div>

    <!-- Logo SENA -->
    <img src="../public/images/logo_sena.png" alt="Logo SENA"
        style="position: absolute; top: 100px; right: 70px; width: 100px;">
    </div>

    <!-- Flecha de retroceso -->
    <div class="col-2 col-md-1 text-start">
        <div class="back-arrow" onclick="goBack()">
            <i class="fas fa-arrow-left"></i>
            <a class="nav-link" href="/SENAParking/frontend/views/dashboard_admin.php"></a>
        </div>
    </div>

    <div class="register-container">
        <h2>
            <center>Registrar Usuario Parqueadero</center>
        </h2><br>
        <form id="formulario" class="formulario">
            <div class="row mb-3">
                <!--Campo para solicitar el nombre-->
                <div class="col-md-6" id="grupo__nombre"><!--Divide en columnas para alinear los campos-->
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" class="form-control formulario__input" name="nombre" id="nombre"
                        placeholder="Ingrese sus nombres" required>
                    <p class="formulario__input-error">El nombre tiene que ser de 4 a 16 dígitos y solo debe contener
                        letras.</p>
                </div>
                <!--Campo para solicitar el apellido-->
                <div class="col-md-6" id="grupo__apellido">
                    <label for="apellido" class="form-label">Apellido:</label>
                    <input type="text" class="form-control formulario__input" name="apellido" id="apellido"
                        placeholder="Ingrese sus apellidos" required>
                    <p class="formulario__input-error">El apellido tiene que ser de 4 a 16 dígitos y solo puede contener
                        letras.</p>
                </div>
            </div>
            <div class="row mb-3">
                <!--Campo para solicitar el tipo de documento-->
                <div class="col-md-6">
                    <label for="tipo_documento" class="form-label formulario__input">Tipo de documento:</label>
                    <select class="form-select" name="tipdoc" id="tipdoc" required>
                        <option value="" selected disabled>Seleccione tipo de documento</option>
                        <option value="cedula_ciudadania">Cédula de Ciudadania</option>
                        <option value="cedula_extranjeria">Cédula de Extranjeria</option>
                        <option value="otro">Pasaporte</option>
                        <option value="tarjeta_identidad">Tarjeta Identidad</option>
                    </select>
                    <p class="formulario__input-error">seleccione un tipo de documento</p>
                </div>
                <!--Campo para solicitar el documento-->
                <div class="col-md-6" id="grupo__documento">
                    <label for="documento" class="form-label">Documento:</label>
                    <input type="text" class="form-control formulario__input" name="documento" id="documento"
                        placeholder="Ingrese su documento" required>
                    <p class="formulario__input-error">El documento tiene que ser de 6 a 10 dígitos y solo puede
                        contener números.</p>
                </div>
            </div>
            <!--Campo para solicitar el núm de contacto-->
            <div class="row mb-3">
                <div class="col-md-6" id="grupo__numero"><!--Divide en columnas para alinear los campos-->
                    <label for="numero_contacto" class="form-label">Número contacto:</label>
                    <input type="text" class="form-control formulario__input" name="numero" id="numero"
                        placeholder="Ingrese número de contacto" required>
                    <p class="formulario__input-error">El numero de contacto tiene que ser de 7 a 14 dígitos y solo
                        puede contener números.</p>
                </div>
                <!--Campo para solicitar el tipo de usuario-->
                <div class="col-md-6">
                    <label for="tipo_usuario" class="form-label">Tipo de usuario:</label>
                    <select class="form-select" name="tipo_usuario" id="tipo_usuario" required>
                        <option value="" selected disabled>Seleccione tipo de usuario</option>
                        <option value="servidor_público">Servidor público</option>
                        <option value="contratista">Contratista</option>
                        <option value="trabajador_oficial">Trabajador Oficial</option>
                        <option value="visitante_autorizado">Visitante Autorizado</option>
                        <option value="aprendiz">Aprendiz</option>
                        <option value="instructor">Instructor</option>
                    </select>
                    <p class="formulario__input-error">Ingrese un tipo de usuario</p>
                </div>
            </div>
            <div class="col-md-6" id="grupo__edificio">
                <label for="edificio" class="form-label">Centro:</label>
                <select class="form-select" name="edificio" id="edificio" required>
                    <option value="" selected disabled>Seleccione una opción</option>
                    <option value="CMD">Centro de Diseño y Metrología</option>
                    <option value="CGI">Centro de Gestión Industrial</option>
                    <option value="CENIGRAF">Cenigraf</option>
                </select>
                <p class="formulario__input-error">Por favor seleccione un edificio.</p>
            </div>
            <br>
            <!-- Botón Confirmar -->
            <div class="text-center">
                <button type="submit" class="btn btn-confirmar">Confirmar</button>
            </div>
        </form>
    </div>

    <!-- Cargar el footer-->
    <!-- Función para llamar al Header-->
    <script src="./../public/js/scriptsDOM.js"></script>

    <!-- llama al archivo de lavidacion del formulario -->
    <script src="../public/js/validacion_user_parking.js"></script>

    <!-- script para que cuando se cierre la sesion refresque la ventana -->
    <script src="../public/js/ref_cierre.js"></script>


</body>

</html>