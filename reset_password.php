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
    die("Error de conexión: " . $e->getMessage());
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
    <meta name="course" content="ADSO 2873801">
    <!-- Favicon (ícono en la pestaña del navegador) -->
    <link rel="icon" type="x-icon" href="./frontend/public/images/favicon.ico">
    <!-- Enlace a Bootstrap -->
    <link href="./frontend/public/css/bootstrap.min.css" rel="stylesheet">
    <!-- Enlace a estilos personalizados -->
    <link rel="stylesheet" href="./frontend/public/css/styles.css">
    <title>Restablecer Contraseña</title>
</head>

<body style="background-color: #f8f9fa;">
    
    <!-- Contenedor donde se insertará el header -->
    <div id="header-containerIdx"></div>

    <div class="register-container" style="background-color: #4CAF50;">
        <h3>Restablecer contraseña</h3>
        
        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($success); ?>
                <p class="mt-2">Redirigiendo al inicio de sesión en <span id="countdown">3</span> segundos...</p>
            </div>
            <script>
                var seconds = 3;
                var countdown = setInterval(function() {
                    seconds--;
                    document.getElementById('countdown').textContent = seconds;
                    if (seconds <= 0) {
                        clearInterval(countdown);
                        window.location.href = 'login.php';
                    }
                }, 1000);
            </script>
        <?php else: ?>
            <form action="" method="POST">
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="password" class="form-label">Nueva contraseña:</label>
                        <input type="password" class="form-control" id="password" name="password" minlength="6" required>
                        <small class="form-text text-muted">Mínimo 6 caracteres</small>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="confirm_password" class="form-label">Confirmar contraseña:</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" minlength="6" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-dark py-2 btn-hover w-100">Restablecer contraseña</button>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <!-- Función para llamar al Header-->
    <script src="./frontend/public/js/scriptsDOM.js"></script>

</body>
</html>