<?php
// Habilitar errores para depuración (eliminar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ya no se requiere header("Content-Type: application/json; charset=UTF-8"); si no se devuelve JSON
// Ya no se requieren los headers CORS si no se está haciendo una API pura
// header("Access-Control-Allow-Methods: POST");
// header("Access-Control-Max-Age: 3600");
// header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/conexion.php';
include_once '../models/Access.php';

$database = new Database();
$db = $database->getConnection();

$access = new Access($db);

// --- INICIO DE LA MODIFICACIÓN PARA id_userSys ---

$id_userSys = null;
// Intenta obtener un id_userSys existente de la tabla tb_usersys
try {
    $query_user = "SELECT id_userSys FROM tb_usersys LIMIT 1"; // Selecciona el primer usuario
    $stmt_user = $db->prepare($query_user);
    $stmt_user->execute();
    $row_user = $stmt_user->fetch(PDO::FETCH_ASSOC);

    if ($row_user) {
        $id_userSys = $row_user['id_userSys'];
    } else {
        // Manejar el caso donde no hay usuarios en tb_usersys
        echo '<script type="text/javascript">';
        echo 'alert("Error: No se encontró ningún usuario de sistema (tb_usersys) para asignar el acceso. Por favor, crea al menos un usuario en la tabla tb_usersys.");';
        echo 'window.history.back();';
        echo '</script>';
        exit();
    }
} catch (PDOException $e) {
    echo '<script type="text/javascript">';
    echo 'alert("Error al intentar obtener id_userSys: ' . $e->getMessage() . '");';
    echo 'window.history.back();';
    echo '</script>';
    exit();
}

// --- FIN DE LA MODIFICACIÓN PARA id_userSys ---

// Se asume que los datos vienen de un formulario POST o de una solicitud AJAX con FormData
$id_vehiculo = isset($_POST['id_vehiculo']) ? $_POST['id_vehiculo'] : null;
$tipo_accion = isset($_POST['tipo_accion']) ? $_POST['tipo_accion'] : null;


if (!empty($id_vehiculo) && !empty($tipo_accion)) {
    $access->id_vehiculo = $id_vehiculo;
    $access->tipo_accion = $tipo_accion;
    $access->fecha_hora = date('Y-m-d H:i:s');
    $access->id_userSys = $id_userSys; // Asignamos el id_userSys obtenido
    $access->espacio_asignado = rand(1, 200); // Esto puede seguir siendo aleatorio si no es una FK

    if ($access->create()) {
        $message = "Acceso registrado exitosamente para el vehículo ID: " . $id_vehiculo . ". Tipo: " . $tipo_accion . ". Espacio asignado: " . $access->espacio_asignado . ". Fecha: " . $access->fecha_hora;
        echo '<script type="text/javascript">';
        echo 'alert("' . $message . '");';
        echo 'window.location.href="../../frontend/views/crud_vehiculos.html";';
        echo '</script>';
        exit();
    } else {
        echo '<script type="text/javascript">';
        echo 'alert("Error: No se pudo registrar el acceso.");';
        echo 'window.history.back();';
        echo '</script>';
        exit();
    }
} else {
    echo '<script type="text/javascript">';
    echo 'alert("Error: Datos incompletos para registrar el acceso.");';
    echo 'window.history.back();';
    echo '</script>';
    exit();
}
?>