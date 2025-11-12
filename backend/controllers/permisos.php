<?php
session_start();

if (isset($_SESSION['rol'])) {
    
    switch($_SESSION['rol']) {
        
        case'1':
            $agregar_veh = true;
            $agregar_usersys = true;
            $agregar_userpark = true;
            $agregar_acceso = true;
            $edit_veh = true;
            $edit_usersys = true;
            $edit_userpark = true;
            $desabilitar_veh = true;
            $desabilitar_usersys = true;
            $desabilitar_userapark = true;
            $reportes = true;
            break;
        case'2':
            $agregar_veh = true;
            $agregar_usersys = true;
            $agregar_userpark = true;
            $agregar_acceso = true;
            $edit_veh = true;
            $edit_usersys = true;
            $edit_userpark = true;
            $desabilitar_veh = true;
            $desabilitar_usersys = true;
            $desabilitar_userapark = true;
            $reportes = false;
            break;
        case'3':
            $agregar_veh = true;
            $agregar_usersys = false;
            $agregar_userpark = true;
            $agregar_acceso = true;
            $edit_veh = false;
            $edit_usersys = false;
            $edit_userpark = false;
            $desabilitar_veh = false;
            $desabilitar_usersys = false;
            $desabilitar_userapark = false;
            $reportes = false;
            break;
    }
}

?>