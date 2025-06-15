<?php
require_once '../config/conexion.php';
require_once '../models/UsuarioSistemaModel.php';

// Crear conexión
$usuarioModel = new Usuario($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $tipdoc = $_POST["tipdoc"];
    $documento = $_POST["documento"];
    $rol = $_POST["rol"];
    $correo = $_POST["correo"];
    $numero = $_POST["numero"];
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];

    $resultado = $usuarioModel->registrarUsuario($nombre,$apellido,$tipdoc,$documento,$rol,$correo,$numero,$usuario,$contrasena);

    if ($resultado === true) {
        echo "Usuario registrado correctamente.";
    } else {
        echo $resultado;
    }
}
?>

