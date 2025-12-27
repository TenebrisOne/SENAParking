<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="AdsoDeveloperSolutions801">
    <title>Recuperar contraseña | SENAParking</title>
    <!-- Favicon -->
    <link rel="icon" type="x-icon" href="./frontend/public/images/favicon.ico">
    <!-- Bootstrap -->
    <link href="./frontend/public/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="./frontend/public/css/styles.css">
</head>

<body class="bg-login">

    <!-- Header Container -->
    <div id="header-containerIdx"></div>

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="glass-card animate-fade-in">
            <h2 class="auth-title">Recuperar contraseña</h2>
            <p class="auth-subtitle">Ingresa tu número de documento para recibir un enlace de recuperación</p>
            
            <!-- Mensajes de sesión -->
            <div id="messages"></div>
            
            <form action="./process_forgot_password.php" method="POST">
                
                <div class="premium-input-group">
                    <label for="numeroDocumento">Número de identificación</label>
                    <div class="input-wrapper">
                        <input type="text" class="premium-input" name="numeroDocumento" id="numeroDocumento" placeholder="Ej: 1098765432" pattern="[0-9]+" title="Solo números" required>
                        <!-- User Icon -->
                        <svg class="input-icon-svg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                    <small class="form-text text-muted mt-2" style="font-size: 0.8rem; display: block; margin-left: 5px;">Tu documento registrado en el sistema</small>
                </div>

                <button type="submit" class="btn-modern" id="singbtn">
                    Enviar enlace
                </button>
                
                <div class="auth-footer">
                    <a href="login.php" class="back-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg>
                        Volver al inicio de sesión
                    </a>
                </div>

            </form>
        </div>
    </div>
    
    <script>
        // Mostrar mensajes de sesión si existen
        <?php
        session_start();
        if (isset($_SESSION['message'])) {
            // Update to use modern alert class
            echo "document.getElementById('messages').innerHTML = '<div class=\"alert alert-modern alert-modern-success\" role=\"alert\">" . 
                 "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"feather feather-check-circle\"><path d=\"M22 11.08V12a10 10 0 1 1-5.93-9.14\"></path><polyline points=\"22 4 12 14.01 9 11.01\"></polyline></svg>" .
                 "<div>" . addslashes($_SESSION['message']) . "</div></div>';";
            unset($_SESSION['message']);
        }
        if (isset($_SESSION['error'])) {
            // Update to use modern alert class
            echo "document.getElementById('messages').innerHTML = '<div class=\"alert alert-modern alert-modern-danger\" role=\"alert\">" . 
                 "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"feather feather-alert-circle\"><circle cx=\"12\" cy=\"12\" r=\"10\"></circle><line x1=\"12\" y1=\"8\" x2=\"12\" y2=\"12\"></line><line x1=\"12\" y1=\"16\" x2=\"12.01\" y2=\"16\"></line></svg>" .
                 "<div>" . addslashes($_SESSION['error']) . "</div></div>';";
            unset($_SESSION['error']);
        }
        ?>
    </script>

    <!-- Función para llamar al Header-->
    <script src="./frontend/public/js/scriptsDOM.js"></script>

</body>
</html>