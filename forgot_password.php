<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <meta name="author" content="AdsoDeveloperSolutions801">
    <meta name="course" content="ADSO 2873801">
    <!-- Favicon (ícono en la pestaña del navegador) -->
    <link rel="icon" type="x-icon" href="./frontend/public/images/favicon.ico">
    <!-- Enlace a Bootstrap -->
    <link href="./frontend/public/css/bootstrap.min.css" rel="stylesheet">
    <!-- Enlace a estilos personalizados -->
    <link rel="stylesheet" href="./frontend/public/css/styles.css">
    <title>Olvidé mi contraseña</title>
</head>


<body style="background-color: #f8f9fa;">

    <!-- Contenedor donde se insertará el header -->
    <div id="header-containerIdx"></div>


    <div class="register-container" style="background-color: #4CAF50;">

    <h3>Recuperar contraseña</h3>
    
    <!-- Mensajes de sesión -->
    <div id="messages"></div>
    
    <form action="./process_forgot_password.php" method="POST">
        <div class="row mb-3" id="grupo__correo">
            <div class="col-12">
                <label for="correo" class="form-label">Correo electrónico:</label>
                <input type="email" class="form-control formulario__input" name="correo" id="correo" placeholder="correo@ejemplo.com" required>
                <small class="form-text text-muted">Ingresa el correo registrado en el sistema</small>
            </div>
        </div>
        <!-- Botón de envío -->
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-dark py-2 btn-hover w-100" id="singbtn">Restablecer contraseña</button>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12 text-center">
                <a href="login.php" class="text-white">Volver al inicio de sesión</a>
            </div>
        </div>
    </form>

    </div>
    
    <script>
        // Mostrar mensajes de sesión si existen
        <?php
        session_start();
        if (isset($_SESSION['message'])) {
            echo "document.getElementById('messages').innerHTML = '<div class=\"alert alert-success\" role=\"alert\">" . addslashes($_SESSION['message']) . "</div>';";
            unset($_SESSION['message']);
        }
        if (isset($_SESSION['error'])) {
            echo "document.getElementById('messages').innerHTML = '<div class=\"alert alert-danger\" role=\"alert\">" . addslashes($_SESSION['error']) . "</div>';";
            unset($_SESSION['error']);
        }
        ?>
    </script>

    <!-- Función para llamar al Header-->
    <script src="./frontend/public/js/scriptsDOM.js"></script>


</body>
</html>