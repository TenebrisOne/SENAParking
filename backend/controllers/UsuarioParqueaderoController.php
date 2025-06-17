<?php
require_once '../config/conexion.php';
require_once '../models/UsuarioParqueaderoModel.php';

$modelo = new UsuarioParqueadero($conn);

// 📌 REGISTRO DE USUARIO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_SERVER["CONTENT_TYPE"])) {
    $nombre     = $_POST['nombre'] ?? '';
    $apellido   = $_POST['apellido'] ?? '';
    $tipdoc     = $_POST['tipdoc'] ?? '';
    $documento  = $_POST['documento'] ?? '';
    $correo     = $_POST['correo'] ?? '';
    $numero     = $_POST['numero'] ?? '';
    $tipo_user  = $_POST['tipo_usuario'] ?? '';
    $edificio   = $_POST['edificio'] ?? '';
    $estado     = 'activo';

    $resultado = $modelo->registrarUsuario($tipo_user, $tipdoc, $documento, $nombre, $apellido, $edificio, $numero, $estado);

    echo $resultado ? "Usuario registrado correctamente." : "Error al registrar.";
    exit;
}

// 📌 CAMBIO DE ESTADO (con JSON)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && str_contains($_SERVER['CONTENT_TYPE'], 'application/json')) {
    $input = json_decode(file_get_contents("php://input"), true);
    $resultado = $modelo->cambiarEstado($input['id_userPark'], $input['estado']);

    echo json_encode([
        'success' => $resultado,
        'message' => $resultado ? "Estado actualizado" : "Error al actualizar estado"
    ]);
    exit;
}

// 📌 CONSULTAR USUARIOS (GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $usuarios = $modelo->obtenerUsuarios();
    header('Content-Type: application/json');
    echo json_encode($usuarios);
    exit;
}


