<?php
require_once '../config/conexion.php';
require_once '../models/UsuarioSistemaModel.php';

$database = new Database();
$db = $database->getConnection();


$usuarioModel = new Usuario($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $tipdoc = trim($_POST['tipdoc'] ?? '');
    $documento = trim($_POST['documento'] ?? '');
    $rol = trim($_POST['rol'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $numero = trim($_POST['numero'] ?? '');
    $usuario = trim($_POST['usuario'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');

    $resultado = $usuarioModel->registrarUsuario($nombre,$apellido,$tipdoc,$documento,$rol,$correo,$numero,$usuario,$contrasena);

    if ($resultado == true) {
        echo "Usuario registrado correctamente.";
    } else {
        echo $resultado;
    }
}
?>

