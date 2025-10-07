<?php
require_once __DIR__ . '/../models/MostrarDatosModel.php';
$modelo = new MostrarDatosModel();

// Totales
$totalUsuariosSistema = $modelo->contarUsuariosSistema();
$totalVehiculosParqueadero = $modelo->contarVehiculosParqueadero();
$accesosHoy = $modelo->contarAccesosHoy();
$salidasHoy = $modelo->contarSalidasHoy();

// CÓDIGO AGREGADO POR CRISTIAN 👀⚠️🚧
$actividades = $modelo->obtenerActividadesRecientes(); // puedes pasar un número si quieres otro límite
$vehiculosHoy = $modelo->obtenerVehiculosHoy(); 


// Reporte dinámico
$tipo = $_POST['tipo'] ?? '';
switch ($tipo) {
    case 'usuarios_sistema':
        $titulo = "Usuarios del Sistema";
        $tabla = $modelo->obtenerUsuariosSistema();
        break;
    case 'vehiculos_parqueadero':
        $titulo = "Vehículos del Parqueadero";
        $tabla = $modelo->obtenerVehiculosParqueadero();
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


