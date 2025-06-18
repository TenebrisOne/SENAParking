<?php
require_once '../config/conexion.php';
require_once '../models/UsuarioParqueaderoModel.php';

$modelo = new UsuarioParqueadero($conn);

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

    $resultado = $modelo->registrarUsuario($tipo_user, $tipdoc, $documento, $nombre, $apellido, $edificio, $numero, $estado);
    echo $resultado ? "Usuario registrado correctamente." : "Error al registrar.";
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

    $resultado = $modelo->actualizarUsuario($id, $tipo_user, $tipdoc, $documento, $nombre, $apellido, $edificio, $numero);
    echo $resultado ? "Usuario actualizado correctamente." : "Error al actualizar.";
    exit;
}

// ✅ CAMBIAR ESTADO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && str_contains($_SERVER["CONTENT_TYPE"], "application/json")) {
    $datos = json_decode(file_get_contents("php://input"), true);
    $resultado = $modelo->cambiarEstado($datos['id_userPark'], $datos['estado']);

    echo json_encode([
        'success' => $resultado,
        'message' => $resultado ? "Estado actualizado correctamente" : "Error al actualizar"
    ]);
    exit;
}

// ✅ CONSULTAR POR ID
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $usuario = $modelo->obtenerUsuarioPorId($_GET['id']);
    header('Content-Type: application/json');
    echo json_encode($usuario);
    exit;
}

// ✅ CONSULTAR TODOS
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $usuarios = $modelo->obtenerUsuarios();
    header('Content-Type: application/json');
    echo json_encode($usuarios);
    exit;
}



