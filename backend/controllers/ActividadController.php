<?php 

require_once '../config/conexion.php';
require_once '../models/UsuarioActividadModel.php';

//Acá registramos quien consulta los reportes individuales
$conn = new mysqli("localhost", "root", "", "senaparking_db");
    
require_once __DIR__ . '/./backend/models/ActividadModel.php';
        $actividadModel = new ActividadModel($conn);

    // ✅ Aquí usamos el modelo para registrar la actividad
    $actividadModel->registrarActividad($usuario['id_userSys'], 'Consulta de reportes individuales');




//Acá registramos quien consulta los reportes generales

$conn = new mysqli("localhost", "root", "", "senaparking_db");
    
require_once __DIR__ . '/./backend/models/ActividadModel.php';
        $actividadModel = new ActividadModel($conn);

    // ✅ Aquí usamos el modelo para registrar la actividad
    $actividadModel->registrarActividad($usuario['id_userSys'], 'Consulta de reportes generales');




//Acá registramos quien crea un vehículo 

$conn = new mysqli("localhost", "root", "", "senaparking_db");
    
require_once __DIR__ . '/./backend/models/ActividadModel.php';
        $actividadModel = new ActividadModel($conn);

    // ✅ Aquí usamos el modelo para registrar la actividad
    $actividadModel->registrarActividad($usuario['id_userSys'], 'Registro de vehiculo');




//Acá registramos quien crea un userSys 

$conn = new mysqli("localhost", "root", "", "senaparking_db");
    
require_once __DIR__ . '/./backend/models/ActividadModel.php';
        $actividadModel = new ActividadModel($conn);

    // ✅ Aquí usamos el modelo para registrar la actividad
    $actividadModel->registrarActividad($usuario['id_userSys'], 'Registro de usuario del sistema');




//Acá registramos quien crea un userPark

$conn = new mysqli("localhost", "root", "", "senaparking_db");
    
require_once __DIR__ . '/./backend/models/ActividadModel.php';
        $actividadModel = new ActividadModel($conn);

    // ✅ Aquí usamos el modelo para registrar la actividad
    $actividadModel->registrarActividad($usuario['id_userSys'], 'Registro de un usuario del parqueadero');




//Acá registramos quien edita un userSystem

$conn = new mysqli("localhost", "root", "", "senaparking_db");
    
require_once __DIR__ . '/./backend/models/ActividadModel.php';
        $actividadModel = new ActividadModel($conn);

    // ✅ Aquí usamos el modelo para registrar la actividad
    $actividadModel->registrarActividad($usuario['id_userSys'], 'Edicion de un usuario del sistema');




//Acá registramos quien edita un vehículo

$conn = new mysqli("localhost", "root", "", "senaparking_db");
    
require_once __DIR__ . '/./backend/models/ActividadModel.php';
        $actividadModel = new ActividadModel($conn);

    // ✅ Aquí usamos el modelo para registrar la actividad
    $actividadModel->registrarActividad($usuario['id_userSys'], 'Edicion de un vehiculo');



?>

