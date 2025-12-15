# Configuración de SMTP para Recuperación de Contraseña

## Instrucciones para configurar el email

El sistema de recuperación de contraseña necesita credenciales SMTP para enviar correos electrónicos.

### Archivo de configuración

Edita el archivo: `backend/config/email_config.php`

### Opción 1: Usar Gmail

1. **Habilita la verificación en 2 pasos** en tu cuenta de Google
2. **Genera una contraseña de aplicación**:
   - Ve a https://myaccount.google.com/apppasswords
   - Selecciona "Correo" y "Otro dispositivo personalizado"
   - Copia la contraseña generada (16 caracteres)

3. **Actualiza el archivo `email_config.php`**:
   ```php
   const SMTP_USERNAME = 'tucorreo@gmail.com';
   const SMTP_PASSWORD = 'tu-contraseña-de-aplicación';
   const FROM_EMAIL = 'tucorreo@gmail.com';
   const APP_URL = 'http://localhost/SENAParking'; // o tu dominio en producción
   ```

### Opción 2: Usar otro proveedor SMTP

Si usas otro servicio de correo, actualiza estos valores según tu proveedor:

```php
const SMTP_HOST = 'smtp.tuproveedor.com';
const SMTP_PORT = 587;  // o 465 para SSL
const SMTP_SECURE = 'tls';  // o 'ssl'
const SMTP_USERNAME = 'tu-usuario';
const SMTP_PASSWORD = 'tu-contraseña';
```

**Proveedores populares**:
- **Outlook/Hotmail**: `smtp-mail.outlook.com`, puerto 587, TLS
- **Yahoo**: `smtp.mail.yahoo.com`, puerto 465, SSL
- **Office 365**: `smtp.office365.com`, puerto 587, TLS

### Verificar la configuración

Una vez configurado, prueba el sistema:

1. Ve a `http://localhost/SENAParking/forgot_password.php`
2. Ingresa un correo registrado en el sistema
3. Verifica que llegue el email de recuperación

Si tienes problemas, revisa el log de errores de PHP.

## Solución de problemas comunes

### Error: "SMTP connect() failed"
- Verifica que las credenciales sean correctas
- Asegúrate de que el puerto no esté bloqueado por el firewall
- Si usas Gmail, verifica que hayas creado una contraseña de aplicación

### No llega el correo
- Revisa la carpeta de spam
- Verifica que el correo esté registrado en la base de datos (tabla `tb_usersys`)
- Revisa los logs de PHP para ver errores específicos

### Error: "Could not instantiate mail function"
- Asegúrate de que PHPMailer esté instalado: `composer require phpmailer/phpmailer`
