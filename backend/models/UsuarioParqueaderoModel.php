<?php
class UsuarioParqueadero {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Registrar usuario del parqueadero
    public function registrarUsuario($tipo_user, $tipo_documento, $numero_documento, $nombres, $apellidos, $edificio, $numero_contacto, $estado = 'activo') {
        $sql = "INSERT INTO tb_userpark (tipo_user, tipo_documento, numero_documento, nombres_park, apellidos_park, edificio, numero_contacto, estado)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssssssss", $tipo_user, $tipo_documento, $numero_documento, $nombres, $apellidos, $edificio, $numero_contacto, $estado);
        return $stmt->execute();
    }

    // Obtener todos los usuarios del parqueadero
    public function obtenerUsuarios() {
        $sql = "SELECT * FROM tb_userpark";
        $result = $this->conexion->query($sql);
        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
        return $usuarios;
    }

    public function obtenerUsuariosPaginados($limit, $offset) {
        $sql = "SELECT * FROM tb_userpark ORDER BY id_userPark ASC LIMIT ? OFFSET ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function contarUsuarios() {
        $sql = "SELECT COUNT(*) as total FROM tb_userpark";
        $resultado = $this->conexion->query($sql);
        $fila = $resultado->fetch_assoc();
        return $fila['total'];
    }

    // Cambiar estado (activo/inactivo)
    public function cambiarEstado($id, $estado) {
        $sql = "UPDATE tb_userpark SET estado = ? WHERE id_userPark = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $estado, $id);
        return $stmt->execute();
    }

    // Obtener usuario por ID (para edición)
    public function obtenerUsuarioPorId($id) {
        $sql = "SELECT * FROM tb_userpark WHERE id_userPark = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Editar usuario
    public function actualizarUsuario($id, $tipo_user, $tipo_documento, $numero_documento, $nombres, $apellidos, $edificio, $numero_contacto) {
        $sql = "UPDATE tb_userpark SET tipo_user = ?, tipo_documento = ?, numero_documento = ?, nombres_park = ?, apellidos_park = ?, edificio = ?, numero_contacto = ?
                WHERE id_userPark = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sssssssi", $tipo_user, $tipo_documento, $numero_documento, $nombres, $apellidos, $edificio, $numero_contacto, $id);
        return $stmt->execute();
    }
}






