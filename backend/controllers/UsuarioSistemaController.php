<?php
require_once('../config/conexion.php');
require_once('../models/UsuarioSistemaModel.php');
require_once('../models/ActividadModel.php'); // Asegúrate de incluir esto si usas $actividadModel

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Crear instancias de los modelos
$usuarioModel = new Usuario($conn);
$actividadModel = new ActividadModel($conn);

// 🟢 Registro de usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre']) && !isset($_POST['id_userSys'])) {

    // Guardar los datos en variables para reusarlos y mayor claridad
    $nombre     = $_POST['nombre'];
    $apellido   = $_POST['apellido'];
    $tipdoc     = $_POST['tipdoc'];
    $documento  = $_POST['documento'];
    $rol        = $_POST['rol'];
    $correo     = $_POST['correo'];
    $numero     = $_POST['numero'];
    $usuario    = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    $registro = $usuarioModel->registrarUsuario(
        $nombre,
        $apellido,
        $tipdoc,
        $documento,
        $rol,
        $correo,
        $numero,
        $usuario,
        $contrasena
    );

    if ($registro) {
        $actividadModel->registrarActividad(['id_userSys'], 'Se registró a ' . $nombre . ' ' . $apellido . ' como usuario del sistema');

        echo ("Registro de usuario exitosamente");
    } else {
        echo ("Error al registrar usuario");
    }
    exit;
}

// 🟢 Cambio de estado del usuario (activo/inactivo)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_userSys'], $_POST['estado'])) {
    $estado = $_POST['estado'] === 'activo' ? 'activo' : 'inactivo';
    $id = intval($_POST['id_userSys']);

    if ($usuarioModel->cambiarEstadoUsuario($id, $estado)) {
        $actividadModel->registrarActividad(['id_userSys'], 'Se cambió el estado del usuario del sistema ' . $id . ' a ' . $estado);
        header("Location: ../../frontend/views/dashboard_admin.php?mensaje=Estado del usuario actualizado");
    } else {
        header("Location: ../../frontend/views/dashboard_admin.php?mensaje=Error al cambiar estado");
    }
    exit;
}

// ✅ ACTUALIZAR USUARIO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_userSys'])) {
    $id         = $_POST['id_userSys'];
    $nombre     = $_POST['nombre'];
    $apellido   = $_POST['apellido'];
    $tipdoc     = $_POST['tipdoc'];
    $documento  = $_POST['documento'];
    $numero     = $_POST['numero'];
    $id_rol  = $_POST['rol'];
    $correo   = $_POST['correo'];
    $username  = $_POST['usuario'];


    $resultado = $usuarioModel->actualizarUsuarioS($id, $nombre, $apellido, $tipdoc, $documento, $id_rol, $correo, $numero, $username);

    if ($resultado) {
        $actividadModel->registrarActividad(['id_userSys'], 'Modificación realizada en el perfil del usuario del sistema: ' . $nombre . ' ' . $apellido);
        echo ("Usuario actualizado exitosamente");
        exit;
    } else {
        echo ("Error al actualizar usuario");
        exit;
    }
}
