<?php

class Usuario {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // ✅ Registrar usuarios con estado por defecto "activo"
    public function registrarUsuario($nombres_sys, $apellidos_sys, $tipo_documento, $numero_documento, $id_rol, $correo, $numero_contacto, $username, $password) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO tb_usersys (nombres_sys, apellidos_sys, tipo_documento, numero_documento, id_rol, correo, numero_contacto, username, password, estado) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'activo')"; // ✅ Estado por defecto: activo

        $stmt = $this->conexion->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssssssss", $nombres_sys, $apellidos_sys, $tipo_documento, $numero_documento, $id_rol, $correo, $numero_contacto, $username, $passwordHash);
            $resultado = $stmt->execute();

            return $resultado;
            
        } else {
            return "Ocurrió un error al registrar el usuario.";
        }
    }

    // ✅ Obtener usuarios y su estado
    public function obtenerUsuarios() {
        $sql = "SELECT id_userSys, nombres_sys, id_rol, username, correo, estado FROM tb_usersys"; 
        $resultado = $this->conexion->query($sql);

        if ($resultado->num_rows > 0) {
            $usuarios = array();
            while ($fila = $resultado->fetch_assoc()) {
                $usuarios[] = $fila;
            }
            header('Content-Type: application/json');
            echo json_encode($usuarios);
            exit();
        } else {
            header('Content-Type: application/json');
            echo json_encode([]);
            exit();
        }
    }

    // ✅ Cambiar estado (habilitar/deshabilitar usuario)
    public function cambiarEstadoUsuario($id_userSys, $nuevoEstado) {
        $sql = "UPDATE tb_usersys SET estado = ? WHERE id_userSys = ?";
        $stmt = $this->conexion->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("si", $nuevoEstado, $id_userSys);
            $resultado = $stmt->execute();

            if ($resultado) {
                echo json_encode(["success" => true, "message" => "Estado actualizado correctamente"]);
            } else {
                echo json_encode(["success" => false, "message" => "Error al cambiar estado"]);
            }
            exit();
        } else {
            echo json_encode(["success" => false, "message" => "Error en la consulta SQL"]);
            exit();
        }
    }
}


// ✅ Procesar cambio de estado desde el dashboard
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_userSys"])) {
    $usuarioModel = new Usuario($conn);
    $usuarioModel->cambiarEstadoUsuario($_POST["id_userSys"], $_POST["estado"]);
}

// ✅ Obtener usuarios para el dashboard
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $usuarioModel = new Usuario($conn);
    echo json_encode($usuarioModel->obtenerUsuarios());
    exit();
}
?>


