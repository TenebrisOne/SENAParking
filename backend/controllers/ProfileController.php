<?php
// backend/controllers/ProfileController.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Iniciar la sesión PHP
session_start();

include_once '../config/conexion.php';
include_once '../models/UserProfileModel.php';

$database = new Database();
$db = $database->getConnection();

$userProfileModel = new UserProfileModel($db);

$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'GET':
        // **Intentar obtener el ID del usuario de la sesión**
        $id = null;
        if (isset($_SESSION['user_id'])) {
            $id = intval($_SESSION['user_id']);
        } else {
            // **IMPORTANTE: Esta parte es solo para desarrollo y pruebas si no hay login.**
            // En producción, si no hay user_id en sesión, DEBERÍA negarse el acceso o redirigir al login.
            // Para poder probarlo sin login, permitiré que se pase por GET SOLO PARA ESTA DEMOSTRACIÓN.
            // REMOVER ESTA LÍNEA en un entorno de producción.
            $id = isset($_GET['id']) ? intval($_GET['id']) : null;
        }

        if ($id === null || $id <= 0) {
            http_response_code(401); // Unauthorized
            echo json_encode(array("message" => "Acceso no autorizado. Por favor, inicie sesión o proporcione un ID válido (solo para pruebas)."));
            exit();
        }

        $userProfileModel->id_userSys = $id;

        if ($userProfileModel->readProfileById()) {
            $user_arr = array(
                "id_userSys" => $userProfileModel->id_userSys,
                "id_rol" => $userProfileModel->id_rol,
                "nombres" => $userProfileModel->nombres,
                "apellidos" => $userProfileModel->apellidos,
                "email" => $userProfileModel->email,
                "estado" => $userProfileModel->estado,
                "fecha_registro" => $userProfileModel->fecha_registro
            );

            $stmt_roles = $userProfileModel->getAllRoles();
            $roles_arr = [];
            while ($row = $stmt_roles->fetch(PDO::FETCH_ASSOC)) {
                $roles_arr[] = $row;
            }

            http_response_code(200);
            echo json_encode(array(
                "success" => true,
                "user" => $user_arr,
                "roles" => $roles_arr
            ));
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Usuario no encontrado."));
        }
        break;

    case 'PUT':
        // **Intentar obtener el ID del usuario de la sesión para la actualización**
        $id = null;
        if (isset($_SESSION['user_id'])) {
            $id = intval($_SESSION['user_id']);
        } else {
            // **IMPORTANTE: Similar al GET, esto es solo para pruebas.**
            // En producción, el PUT también debería requerir autenticación de sesión.
            $id = isset($_GET['id']) ? intval($_GET['id']) : null;
        }

        if ($id === null || $id <= 0) {
            http_response_code(401); // Unauthorized
            echo json_encode(array("message" => "Acceso no autorizado para actualizar. Por favor, inicie sesión o proporcione un ID válido (solo para pruebas)."));
            exit();
        }

        $data = json_decode(file_get_contents("php://input"));

        if (
            empty($data->nombres) ||
            empty($data->apellidos) ||
            empty($data->email) ||
            empty($data->id_rol) ||
            empty($data->estado)
        ) {
            http_response_code(400);
            echo json_encode(array("message" => "No se pudieron actualizar los datos. Faltan campos."));
            exit();
        }

        $userProfileModel->id_userSys = $id;
        $userProfileModel->nombres = $data->nombres;
        $userProfileModel->apellidos = $data->apellidos;
        $userProfileModel->email = $data->email;
        $userProfileModel->id_rol = $data->id_rol;
        $userProfileModel->estado = $data->estado;

        if ($userProfileModel->updateProfile()) {
            http_response_code(200);
            echo json_encode(array("success" => true, "message" => "Perfil actualizado exitosamente."));
        } else {
            http_response_code(503);
            echo json_encode(array("success" => false, "message" => "No se pudo actualizar el perfil."));
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(array("message" => "Método no permitido."));
        break;
}
?>