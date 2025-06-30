<?php
require_once('../config/conexion.php');
require_once('../models/UsuarioParqueaderoModel.php');

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
        header("Location: ../../frontend/views/reg_userParking.html?mensaje=Usuario registrado exitosamente");
        exit;
    } else {
        header("Location: ../../frontend/views/reg_userParking.html?mensaje=Error al registrar el usuario");
        exit;
    }
}

// ✅ CAMBIAR ESTADO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['estado'])) {
    $id = $_POST['id'];
    $estado = $_POST['estado']  === 'activo' ? 'activo' : 'inactivo';

    if ($usuarioPark->cambiarEstado($id, $estado)) {
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
        header('Location: /SENAParking/frontend/views/dashboard_admin.php?mensaje=Usuario editado correctamente');
        exit;
    } else {
        header('Location: /SENAParking/frontend/views/dashboard_admin.php?mensaje=Error al editar usuario');
        exit;
    }
}


