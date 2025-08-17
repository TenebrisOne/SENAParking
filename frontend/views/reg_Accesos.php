<?php
session_start();

if (!isset($_SESSION['rol'])) {
    header("location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

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
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Registro de Accesos | SENAParking</title>
</head>

<body>
    <!-- Contenedor donde se insertará el header -->
    <div id="header-container"></div>

        <!-- Logo SENA -->
    <img src="../public/images/logo_sena.png" alt="Logo SENA"
        style="position: absolute; top: 100px; right: 70px; width: 100px;">
    </div>

    <!-- Botón para volver atrás -->
    <div class="back-arrow" onclick="goBack()">
        <i class="fas fa-arrow-left"></i>
    </div>

    <!-- Formulario -->
    <div class="register-container">
        <div class="text-center">
            <h2>¿Quien ingresa?</h2>
        </div>
        

        <form id="formulario">
            <!--Fila del grid de Bootstrap con margen inferior de 3-->
            <div class="row mb-3">
                <div class="col-12" id="grupo__nombre"> <!-- Columna de 12 espacios-->
                                <!-- Etiqueta para el campo de texto, conectada con el input mediante el atributo "for"-->
                    <label for="nombre" class="form-label">Nombres:</label>
                    <input type="text" class="form-control formulario__input" name="nombre" id="nombre" placeholder="Ingrese sus nombres" required>
                    <p class="formulario__input-error">El nombre tiene que ser de 4 a 80 digitos y solo debe contener letras.</p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12" id="grupo__documento">
                    <label for="cedula" class="form-label">Numero de Documento:</label>
                    <input type="text" class="form-control formulario__input" name="documento" id="documento" placeholder="Ingrese el Numero Documento"required>
                    <p class="formulario__input-error">El documento tiene que ser de 6 a 10 dígitos y solo debe contener numeros.</p>
                </div>
            </div>
            <div class="text-center">
                <button type="button" onclick="window.location.href='./crud_vehiculos.php'" class="btn btn-confirmar">Confirmar</button>
            </div>
        </form>
    </div>



    <!-- Función para llamar al Header-->
    <script src="./../public/js/scriptsDOM.js"></script>

    <!--Funcion para llamarla validacion-->
    <script src="/frontend/public/js/validacion_reg_accesos.js"></script>

    <!-- script para que cuando se cierre la sesion refresque la ventana -->
    <script src="../public/js/ref_cierre.js"></script>

</body>

</html>