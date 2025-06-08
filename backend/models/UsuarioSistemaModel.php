<?php
require_once '../config/conexion.php'; // ✅ Ajustamos la ruta de conexión

class Usuario {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Método para registrar usuarios con las columnas correctas
    public function registrarUsuario($nombres_sys, $apellidos_sys, $tipo_documento, $numero_documento, $id_rol, $correo, $numero_contacto, $username, $password) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO tb_usersys (nombres_sys, apellidos_sys, tipo_documento, numero_documento, id_rol, correo, numero_contacto, username, password) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssisssss", $nombres_sys, $apellidos_sys, $tipo_documento, $numero_documento, $id_rol, $correo, $numero_contacto, $username, $passwordHash);
            return $stmt->execute();
        } else {
            return false;
        }
    }

    // Método para obtener los usuarios con todas las columnas necesarias
    public function obtenerUsuarios() {
        $sql = "SELECT id_userSys, nombres_sys, id_rol, username, correo, estado FROM tb_usersys"; // ✅ Incluimos estado
        $resultado = $this->conexion->query($sql);

        if ($resultado->num_rows > 0) {
            $usuarios = array();
            while ($fila = $resultado->fetch_assoc()) {
                $usuarios[] = $fila;
            }
            header('Content-Type: application/json'); // ✅ Formato JSON
            echo json_encode($usuarios);
            exit();
        } else {
            header('Content-Type: application/json');
            echo json_encode([]);
            exit();
        }
    }

    // Método para cambiar el estado del usuario (activo o inactivo)
    public function cambiarEstadoUsuario($id_userSys, $nuevoEstado) {
        $sql = "UPDATE tb_usersys SET estado = ? WHERE id_userSys = ?";
        $stmt = $this->conexion->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("si", $nuevoEstado, $id_userSys);
            return $stmt->execute();
        } else {
            return false;
        }
    }
}

// ✅ Manejo de solicitudes POST fuera de la clase
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $datos = json_decode(file_get_contents("php://input"), true);

    if (isset($datos["id_userSys"]) && isset($datos["estado"])) {
        $id_userSys = $datos["id_userSys"];
        $nuevoEstado = $datos["estado"];

        require_once '../config/conexion.php'; // ✅ Asegurar conexión aquí
        $usuarioModel = new Usuario($conn);
        $resultado = $usuarioModel->cambiarEstadoUsuario($id_userSys, $nuevoEstado);

        echo json_encode(["success" => $resultado]);
        exit();
    } else {
        echo json_encode(["success" => false, "error" => "Datos incompletos"]);
        exit();
    }
}

// ✅ Manejo de solicitudes GET para obtener usuarios
require_once '../config/conexion.php';
$usuarioModel = new Usuario($conn);

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    echo json_encode($usuarioModel->obtenerUsuarios());
    exit();
}
?>
