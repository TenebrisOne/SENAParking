<?php
require_once '../config/conexion.php';
require_once '../models/UsuarioParqueaderoModel.php';

$modelo = new UsuarioParqueadero($conn);

// REGISTRO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'])) {
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

// CAMBIO ESTADO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && str_contains($_SERVER["CONTENT_TYPE"], "application/json")) {
    $datos = json_decode(file_get_contents("php://input"), true);
    $resultado = $modelo->cambiarEstado($datos['id_userPark'], $datos['estado']);

    echo json_encode([
        'success' => $resultado,
        'message' => $resultado ? "Estado actualizado" : "Error al actualizar"
    ]);
    exit;
}

// CONSULTA GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $usuarios = $modelo->obtenerUsuarios();
    header('Content-Type: application/json');
    echo json_encode($usuarios);
    exit;
}



