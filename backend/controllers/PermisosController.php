<?php
// backend/controllers/PermisosController.php (o inclúyelo en tus paneles de control)

function hasPermission($required_role_id) {
    if (!isset($_SESSION['id_rol'])) {
        return false; // Usuario no ha iniciado sesión
    }
    $user_role_id = $_SESSION['id_rol'];

    // Ejemplo: El Rol 1 (Administrador) tiene todos los permisos
    if ($user_role_id == 1) {
        return true;
    }

    // Para otros roles, verifica si su rol coincide con el rol requerido
    return ($user_role_id == $required_role_id);

    // Una lógica de permisos más compleja podría implicar una tabla de base de datos
    // que mapee roles a permisos específicos (ej. 'can_view_reports', 'can_edit_users')
}

function requirePermission($required_role_id, $redirect_page = '/SENAParking/index.html') {
    if (!hasPermission($required_role_id)) {
        // Opcionalmente, establece un mensaje
        $_SESSION['message'] = "No tienes permiso para acceder a esta página.";
        header("Location: " . $redirect_page);
        exit();
    }
}
?>