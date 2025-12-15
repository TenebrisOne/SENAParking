<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require_once __DIR__ . '/backend/config/email_config.php';

session_start();

$host = 'localhost';
$dbname = 'senaparking_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("set names utf8mb4");
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Corrección: El formulario envía 'correo', no 'email'
    $email = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);

    // Verificar si el correo existe - CORRECCIÓN: usar correoUsys
    $stmt = $pdo->prepare("SELECT * FROM tb_usersys WHERE correoUsys = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generar token único
        $token = bin2hex(random_bytes(32));

        // Guardar token en la base de datos - CORRECCIÓN: usar correoUsys
        $stmt = $pdo->prepare("INSERT INTO password_resets (correoUsys, token) VALUES (?, ?)");
        $stmt->execute([$email, $token]);

        // Enviar correo con PHPMailer usando configuración centralizada
        $mail = EmailConfig::getMailer();
        try {
            // Destinatario
            $mail->addAddress($email);

            // Contenido
            $mail->Subject = 'Restablecer tu contraseña - SENA Parking';
            $resetLink = EmailConfig::getResetPasswordUrl($email, $token);
            
            $mail->Body = "
                <html>
                <body style='font-family: Arial, sans-serif;'>
                    <h2>Recuperación de Contraseña</h2>
                    <p>Has solicitado restablecer tu contraseña en el sistema SENA Parking.</p>
                    <p>Haz clic en el siguiente enlace para crear una nueva contraseña:</p>
                    <p><a href='$resetLink' style='background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>Restablecer Contraseña</a></p>
                    <p>Este enlace expirará en 1 hora.</p>
                    <p>Si no solicitaste este cambio, ignora este correo.</p>
                    <hr>
                    <p style='font-size: 12px; color: #666;'>SENA Parking - Sistema de Gestión de Parqueadero</p>
                </body>
                </html>
            ";
            $mail->AltBody = "Copia y pega este enlace en tu navegador: $resetLink\n\nEste enlace expirará en 1 hora.";

            $mail->send();
            $_SESSION['message'] = "Se ha enviado un enlace de restablecimiento a tu correo.";
        } catch (Exception $e) {
            $_SESSION['error'] = "No se pudo enviar el correo. Error: {$mail->ErrorInfo}";
            error_log("Error enviando email de reset: " . $mail->ErrorInfo);
        }
    } else {
        $_SESSION['error'] = "El correo no está registrado.";
    }

    header("Location: forgot_password.php");
    exit;
}
?>