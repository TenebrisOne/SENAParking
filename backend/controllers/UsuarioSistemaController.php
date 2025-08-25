<?php
require_once('../config/conexion.php');
require_once('../models/UsuarioSistemaModel.php');


$usuarioModel = new Usuario($conn);

// 🟢 Registro de usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre']) && !isset($_POST['id_userSys'])) {
    $registro = $usuarioModel->registrarUsuario(
        $_POST['nombre'],
        $_POST['apellido'],
        $_POST['tipdoc'],
        $_POST['documento'],
        $_POST['rol'],
        $_POST['correo'],
        $_POST['numero'],
        $_POST['usuario'],
        $_POST['contrasena']
    );

    if ($registro) {
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
        echo ("Usuario actualizado exitosamente");
        exit;
    } else {
        echo ("Error al actualizar usuario");
        exit;
    }
}
