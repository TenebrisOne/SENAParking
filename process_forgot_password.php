<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Verificar si el correo existe
    $stmt = $pdo->prepare("SELECT * FROM tb_usersys WHERE correo = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generar token único
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Guardar token en la base de datos
        $stmt = $pdo->prepare("INSERT INTO password_resets (correo, token) VALUES (?, ?)");
        $stmt->execute([$email, $token]);

        // Enviar correo con PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@gmail.com';
            $mail->Password = 'your_app_password'; // Usa una contraseña de aplicación
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Destinatario
            $mail->setFrom('your_email@gmail.com', 'Tu App');
            $mail->addAddress($email);

            // Contenido
            $mail->isHTML(true);
            $mail->Subject = 'Restablecer tu contraseña';
            $resetLink = "http://yourdomain.com/reset_password.php?email=" . urlencode($email) . "&token=$token";
            $mail->Body = "Haz clic en el siguiente enlace para restablecer tu contraseña: <a href='$resetLink'>Restablecer contraseña</a>";
            $mail->AltBody = "Copia y pega este enlace en tu navegador: $resetLink";

            $mail->send();
            $_SESSION['message'] = "Se ha enviado un enlace de restablecimiento a tu correo.";
        } catch (Exception $e) {
            $_SESSION['error'] = "No se pudo enviar el correo. Error: {$mail->ErrorInfo}";
        }
    } else {
        $_SESSION['error'] = "El correo no está registrado.";
    }

    header("Location: forgot_password.html");
    exit;
}
?>