<?php
require_once '../config/conexion.php';
require_once '../models/UsuarioParqueaderoModel.php';

$database = new Database();
$db = $database->getConnection();

$usuarioParkingModel = new UsuarioParqueadero($db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $tipdoc = trim($_POST['tipdoc'] ?? '');
    $documento = trim($_POST['documento'] ?? '');
    $tarjeta = trim($_POST['tarjeta'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $numero = trim($_POST['numero'] ?? '');
    $tipuser = trim($_POST['tipuser'] ?? '');
    $hora_entrada = trim($_POST['hora_entrada'] ?? '');
    $centro = trim($_POST['centro'] ?? '');

    $resultado = $usuarioParkingModel->registrarUsuario($tipuser,$tipdoc,$documento,$nombre,$apellido,$centro,$tarjeta,$correo,$numero,$hora_entrada);

    if ($resultado == true) {
        echo $conn;
    } else {
        echo $resultado;
    }
}
?>

