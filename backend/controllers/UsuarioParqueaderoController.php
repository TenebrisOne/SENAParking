<?php
require_once '../config/conexion.php';
require_once '../models/UsuarioParqueaderoModel.php';

$usuarioParkingModel = new UsuarioParqueadero($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre           = $_POST["nombre"] ?? "";
    $apellido         = $_POST["apellido"] ?? "";
    $tipo_documento   = $_POST["tipo_documento"] ?? "";
    $documento        = $_POST["documento"] ?? "";
    $tipo_usuario     = $_POST["tipo_usuario"] ?? "";
    $tarjeta          = $_POST["tarjeta"] ?? "";
    $numero           = $_POST["numero"] ?? "";
    $edificio         = $_POST["edificio"] ?? "";

    $resultado = $usuarioParkingModel->registrarUsuario(
        $tipo_usuario, $tipo_documento, $documento,
        $nombre, $apellido, $edificio, $tarjeta, $numero
    );

    if ($resultado) {
        header("Location: ../views/dashboard_admin.html?registro=exitoso");
        exit();
    } else {
        echo "Error al registrar el usuario.";
    }
}
?>

