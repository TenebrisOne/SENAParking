<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // solo se ejecuta si no hay sesión activa
}

require_once('../config/conexion.php');
require_once('../models/UsuarioParqueaderoModel.php');
require_once('../models/ActividadModel.php');

$actividadModel = new ActividadModel($conn);
$usuarioPark = new UsuarioParqueadero($conn);

// ✅ REGISTRAR USUARIO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre']) && !isset($_POST['id_userPark'])) {
    $nombre     = $_POST['nombre'];
    $apellido   = $_POST['apellido'];
    $tipdoc     = $_POST['tipdoc'];
    $documento  = $_POST['documento'];
    $numero     = $_POST['numero'];
    $tipo_user  = $_POST['tipo_usuario'];
    $edificio   = $_POST['edificio'];
    $estado     = 'activo';

    $registrado = $usuarioPark->registrarUsuario($tipo_user, $tipdoc, $documento, $nombre, $apellido, $edificio, $numero, $estado);

    if ($registrado) {
        $actividadModel->registrarActividad($_SESSION['id_userSys'], 'Se registró a ' . $nombre . ' ' . $apellido . ' como usuario del parqueadero');
        echo ("Registro de usuario exitosamente");
        exit;
    } else {
        echo ("Error al registrar usuario");
        exit;
    }
}

// ✅ CAMBIAR ESTADO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['estado'])) {
    $id = $_POST['id'];
    $estado = $_POST['estado']  === 'activo' ? 'activo' : 'inactivo';


    if ($usuarioPark->cambiarEstado($id, $estado)) {
        $actividadModel->registrarActividad($_SESSION['id_userSys'], 'Se cambió el estado del usuario del parqueadero ' . $id . ' a ' . $estado);
        header("Location: ../../frontend/views/dashboard_admin.php?mensaje=Estado del usuario actualizado");
    } else {
        header("Location: ../../frontend/views/dashboard_admin.php?mensaje=Error al cambiar estado");
    }
    exit;
}
// ✅ ACTUALIZAR USUARIO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_userPark'])) {
    $id         = $_POST['id_userPark'];
    $nombre     = $_POST['nombre'];
    $apellido   = $_POST['apellido'];
    $tipdoc     = $_POST['tipdoc'];
    $documento  = $_POST['documento'];
    $numero     = $_POST['numero'];
    $tipo_user  = $_POST['tipo_usuario'];
    $edificio   = $_POST['edificio'];

    $resultado = $usuarioPark->actualizarUsuario($id, $tipo_user, $tipdoc, $documento, $nombre, $apellido, $edificio, $numero);

    if ($resultado) {
        $actividadModel->registrarActividad($_SESSION['id_userSys'], 'Modificación realizada en el perfil del usuario del parqueadero: ' . $nombre . ' ' . $apellido);

        echo ("Usuario editado exitosamente");
        exit;
    } else {
        echo ("Error al editar usuario");
        exit;
    }
}
