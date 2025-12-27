<?php
session_start();

$host = 'localhost';
$dbname = 'senaparking_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage()); // In production, log this instead
}

$email = filter_var($_GET['correo'] ?? '', FILTER_SANITIZE_EMAIL);
$token = $_GET['token'] ?? '';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($new_password === $confirm_password) {
        // Verificar token - CORRECCIÓN: usar correoUsys y horaFecha
        $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE correoUsys = ? AND token = ? AND horaFecha >= DATE_SUB(NOW(), INTERVAL 1 HOUR)");
        $stmt->execute([$email, $token]);
        $reset = $stmt->fetch();

        if ($reset) {
            // Actualizar contraseña - CORRECCIÓN: usar correoUsys y passwordUsys
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE tb_usersys SET passwordUsys = ? WHERE correoUsys = ?");
            $stmt->execute([$hashed_password, $email]);

            // Eliminar token usado - CORRECCIÓN: usar correoUsys
            $stmt = $pdo->prepare("DELETE FROM password_resets WHERE correoUsys = ? AND token = ?");
            $stmt->execute([$email, $token]);

            $success = "Contraseña restablecida con éxito.";
        } else {
            $error = "El enlace es inválido o ha expirado.";
        }
    } else {
        $error = "Las contraseñas no coinciden.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="AdsoDeveloperSolutions801">
    <title>Restablecer Contraseña | SENAParking</title>
    <link rel="icon" type="x-icon" href="./frontend/public/images/favicon.ico">
    <link href="./frontend/public/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./frontend/public/css/styles.css">
</head>

<body class="bg-login"> <!-- Use existing login background animation -->
    
    <!-- Header Container -->
    <div id="header-containerIdx"></div>

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="glass-card animate-fade-in">
            <h2 class="auth-title">Restablecer Contraseña</h2>
            <p class="auth-subtitle">Ingresa tu nueva contraseña para acceder</p>
            
            <?php if ($error): ?>
                <div class="alert alert-modern alert-modern-danger" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <div><?php echo htmlspecialchars($error); ?></div>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-modern alert-modern-success" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <div>
                        <strong>¡Éxito!</strong> <?php echo htmlspecialchars($success); ?>
                        <p class="mb-0 mt-1 small">Redirigiendo en <span id="countdown">3</span> segundos...</p>
                    </div>
                </div>
                <script>
                    var seconds = 3;
                    var countdown = setInterval(function() {
                        seconds--;
                        var el = document.getElementById('countdown');
                        if(el) el.textContent = seconds;
                        if (seconds <= 0) {
                            clearInterval(countdown);
                            window.location.href = 'login.php';
                        }
                    }, 1000);
                </script>
            <?php else: ?>
                <form action="" method="POST">
                    
                    <div class="premium-input-group">
                        <label for="password">Nueva contraseña</label>
                        <div class="input-wrapper">
                            <input type="password" class="premium-input" id="password" name="password" minlength="6" required placeholder="Mínimo 6 caracteres">
                            <!-- Lock Icon -->
                            <svg class="input-icon-svg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        </div>
                    </div>

                    <div class="premium-input-group">
                        <label for="confirm_password">Confirmar contraseña</label>
                        <div class="input-wrapper">
                            <input type="password" class="premium-input" id="confirm_password" name="confirm_password" minlength="6" required placeholder="Repite la contraseña">
                            <!-- Lock Icon -->
                            <svg class="input-icon-svg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        </div>
                    </div>

                    <button type="submit" class="btn-modern">
                        Restablecer contraseña
                    </button>
                    
                    <div class="auth-footer">
                        <a href="login.php" class="back-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg>
                            Cancelar y volver
                        </a>
                    </div>

                </form>
            <?php endif; ?>
        </div>
    </div>

    <script src="./frontend/public/js/scriptsDOM.js"></script>

</body>
</html>