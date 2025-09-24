<?php
require_once __DIR__ . '/../models/MostrarDatosModel.php';
$modelo = new MostrarDatosModel();

// Totales
$totalUsuariosSistema = $modelo->contarUsuariosSistema();
$totalUsuariosParqueadero = $modelo->contarUsuariosParqueadero();
$accesosHoy = $modelo->contarAccesosHoy();
$salidasHoy = $modelo->contarSalidasHoy();

$actividades = $modelo->obtenerActividadesRecientes(); // puedes pasar un número si quieres otro límite


// Reporte dinámico
$tipo = $_POST['tipo'] ?? '';
switch ($tipo) {
    case 'usuarios_sistema':
        $titulo = "Usuarios del Sistema";
        $tabla = $modelo->obtenerUsuariosSistema();
        break;
    case 'usuarios_parqueadero':
        $titulo = "Usuarios del Parqueadero";
        $tabla = $modelo->obtenerUsuariosParqueadero();
        break;
    case 'accesos_hoy':
        $titulo = "Accesos del Día";
        $tabla = $modelo->obtenerAccesosHoy();
        break;
    case 'salidas_hoy':
        $titulo = "Salidas del Día";
        $tabla = $modelo->obtenerSalidasHoy();
        break;
    default:
        $titulo = "";
        $tabla = [];
        break;
}



?>


