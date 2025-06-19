<?php
require_once '../config/conexion.php';
require_once '../models/UsuarioSistemaModel.php';


$usuarioModel = new Usuario($conn);

// ✅ REGISTRAR USUARIO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre']) && !isset($_POST['id_userSys'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $tipdoc = $_POST['tipdoc'];
    $documento = $_POST['documento'];
    $rol = $_POST['rol'];
    $correo = $_POST['correo'];
    $numero = $_POST['numero'];
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $estado = 'activo';

    $resultado = $usuarioModel->registrarUsuario($nombre,$apellido,$tipdoc,$documento,$rol,$correo,$numero,$usuario,$contrasena, $estado);
    echo $resultado ? "Usuario registrado con éxito" : "Error al registrar usuario.";
    exit;
}

// ✅ CAMBIAR ESTADO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && str_contains($_SERVER["CONTENT_TYPE"], "application/json")) {
    $datos = json_decode(file_get_contents("php://input"), true);
    $resultado = $usuarioModel->cambiarEstadoUsuario($datos['id_userSys'], $datos['estado']);

    echo json_encode([
        'success' => $resultado,
        'message' => $resultado ? "Estado actualizado con éxito" : "Error al cambiar estado"
    ]);
    exit;
}

// ✅ CONSULTAR TODOS
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $usuarios = $usuarioModel->obtenerUsuarios();
    header('Content-Type: application/json');
    echo json_encode($usuarios);
    exit;
}

?>

