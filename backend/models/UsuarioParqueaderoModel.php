<?php
class UsuarioParqueadero {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function registrarUsuario($tipo_user, $tipo_documento, $numero_documento, $nombres, $apellidos, $edificio, $numero_contacto, $estado = 'activo') {
        $sql = "INSERT INTO tb_userpark (tipo_user, tipo_documento, numero_documento, nombres_park, apellidos_park, edificio, numero_contacto, estado)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssssssss", $tipo_user, $tipo_documento, $numero_documento, $nombres, $apellidos, $edificio, $numero_contacto, $estado);
        return $stmt->execute();
    }

    public function obtenerUsuarios() {
        $sql = "SELECT id_userPark, nombres_park, apellidos_park, numero_documento, tipo_user, estado
                FROM tb_userpark ORDER BY id_userPark DESC";
        $result = $this->conexion->query($sql);
        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
        return $usuarios;
    }

    public function cambiarEstado($id, $estado) {
        $stmt = $this->conexion->prepare("UPDATE tb_userpark SET estado = ? WHERE id_userPark = ?");
        $stmt->bind_param("si", $estado, $id);
        return $stmt->execute();
    }
}






