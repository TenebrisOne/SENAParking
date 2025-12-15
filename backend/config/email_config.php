<?php
/**
 * Configuración de Email para el sistema SENAPARKING
 * 
 * INSTRUCCIONES PARA CONFIGURAR:
 * 
 * Para Gmail:
 * 1. Ve a tu cuenta de Google
 * 2. Activa la verificación en 2 pasos
 * 3. Genera una contraseña de aplicación en: https://myaccount.google.com/apppasswords
 * 4. Reemplaza 'your_email@gmail.com' con tu email real
 * 5. Reemplaza 'your_app_password' con la contraseña de aplicación generada
 * 
 * Para otros proveedores SMTP:
 * - Actualiza las propiedades SMTP_HOST, SMTP_PORT y SMTP_SECURE según tu proveedor
 */

class EmailConfig {
    // Configuración SMTP
    const SMTP_HOST = 'smtp.gmail.com';
    const SMTP_PORT = 587;
    const SMTP_SECURE = 'tls'; // 'tls' o 'ssl'
    const SMTP_AUTH = true;
    
    // Credenciales - CAMBIAR ESTOS VALORES
    const SMTP_USERNAME = 'your_email@gmail.com'; // Tu email aquí
    const SMTP_PASSWORD = 'your_app_password';    // Tu contraseña de aplicación aquí
    
    // Información del remitente
    const FROM_EMAIL = 'your_email@gmail.com';     // Email del remitente
    const FROM_NAME = 'SENA Parking - Sistema';    // Nombre del remitente
    
    // URL base de la aplicación - CAMBIAR SEGÚN TU INSTALACIÓN
    // Ejemplos:
    // - Desarrollo local: 'http://localhost/SENAParking'
    // - Producción: 'https://tudominio.com'
    const APP_URL = 'http://localhost/SENAParking';
    
    /**
     * Obtiene una instancia configurada de PHPMailer
     * @return PHPMailer
     */
    public static function getMailer() {
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        
        try {
            // Configuración del servidor
            $mail->isSMTP();
            $mail->Host = self::SMTP_HOST;
            $mail->SMTPAuth = self::SMTP_AUTH;
            $mail->Username = self::SMTP_USERNAME;
            $mail->Password = self::SMTP_PASSWORD;
            $mail->SMTPSecure = self::SMTP_SECURE;
            $mail->Port = self::SMTP_PORT;
            
            // Configuración de caracteres
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            
            // Remitente
            $mail->setFrom(self::FROM_EMAIL, self::FROM_NAME);
            
            // Configuración HTML
            $mail->isHTML(true);
            
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            error_log("Error al configurar PHPMailer: " . $e->getMessage());
        }
        
        return $mail;
    }
    
    /**
     * Genera URL de reset de contraseña
     * @param string $email
     * @param string $token
     * @return string
     */
    public static function getResetPasswordUrl($email, $token) {
        return self::APP_URL . '/reset_password.php?correo=' . urlencode($email) . '&token=' . urlencode($token);
    }
}
?>
