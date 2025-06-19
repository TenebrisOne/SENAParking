<?php
// Este archivo es para actualizar los numeros en tiempo real en el dashboar_admin.html //

header("Content-Type: application/json");
include '../config/conexion.php'; // Ajusta si tu archivo conexión está en otro lado

// Consulta: Usuarios del sistema
$sqlUsuariosSistema = "SELECT COUNT(*) AS total FROM tb_usersys";
$resUsuariosSistema = mysqli_query($conn, $sqlUsuariosSistema);
$usuariosSistema = mysqli_fetch_assoc($resUsuariosSistema)['total'];

// Consulta: Usuarios del parqueadero
$sqlUsuariosPark = "SELECT COUNT(*) AS total FROM tb_userpark";
$resUsuariosPark = mysqli_query($conn, $sqlUsuariosPark);
$usuariosParqueadero = mysqli_fetch_assoc($resUsuariosPark)['total'];

// Consulta: Ingresos de hoy
$sqlIngresosHoy = "SELECT COUNT(*) AS total FROM tb_accesos WHERE tipo_accion='ingreso' AND DATE(fecha_hora) = CURDATE()";
$resIngresosHoy = mysqli_query($conn, $sqlIngresosHoy);
$ingresosHoy = mysqli_fetch_assoc($resIngresosHoy)['total'];

// Consulta: Salidas de hoy
$sqlSalidasHoy = "SELECT COUNT(*) AS total FROM tb_accesos WHERE tipo_accion='salida' AND DATE(fecha_hora) = CURDATE()";
$resSalidasHoy = mysqli_query($conn, $sqlSalidasHoy);
$salidasHoy = mysqli_fetch_assoc($resSalidasHoy)['total'];

// Consulta: Capacidad total configurada (opcional)
$sqlCapacidad = "SELECT adelante_carros + adelante_motos + adelante_ciclas + trasera_carros AS capacidad_total FROM tb_configpark LIMIT 1";
$resCapacidad = mysqli_query($conn, $sqlCapacidad);
$capacidadTotal = mysqli_fetch_assoc($resCapacidad)['capacidad_total'] ?? 0;

// Salida JSON
echo json_encode([
  'usuariosSistema' => $usuariosSistema,
  'usuariosParqueadero' => $usuariosParqueadero,
  'ingresosHoy' => $ingresosHoy,
  'salidasHoy' => $salidasHoy,
  'capacidadTotal' => $capacidadTotal
]);
