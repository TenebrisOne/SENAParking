<?php
require_once __DIR__ . '/../models/MostrarDatosModel.php';
$modelo = new MostrarDatosModel();

// Totales
$totalUsuariosSistema = $modelo->contarUsuariosSistema();
$totalVehiculos = $modelo->contarVehiculos();
$accesosHoy = $modelo->contarAccesosHoy();
$salidasHoy = $modelo->contarSalidasHoy();

// Reporte dinámico
$tipo = $_POST['tipo'] ?? '';
switch ($tipo) {
    case 'usuarios_sistema':
        $titulo = "Usuarios del Sistema";
        $tabla = $modelo->obtenerUsuariosSistema();
        break;
    case 'vehiculos_parqueadero':
        $titulo = "Vehículos del Parqueadero";
        $tabla = $modelo->obtenerVehiculos();
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


