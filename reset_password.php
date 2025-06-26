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
        // Verificar token
        $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE correo = ? AND token = ? AND hora_fecha >= DATE_SUB(NOW(), INTERVAL 1 HOUR)");
        $stmt->execute([$email, $token]);
        $reset = $stmt->fetch();

        if ($reset) {
            // Actualizar contraseña
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE tb_usersys SET password = ? WHERE correo = ?");
            $stmt->execute([$hashed_password, $email]);

            // Eliminar token usado
            $stmt = $pdo->prepare("DELETE FROM password_resets WHERE correo = ? AND token = ?");
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

<body>
    <h2>Restablecer contraseña</h2>
    <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p class="message"><?php echo $success; ?></p>
    <?php else: ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="password">Nueva contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirmar contraseña:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit">Restablecer contraseña</button>
        </form>
    <?php endif; ?>
</body>
</html>