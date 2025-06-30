<?php
class Usuario {
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function registrarUsuario($nombres_sys, $apellidos_sys, $tipo_documento, $numero_documento, $id_rol, $correo, $numero_contacto, $username, $password, $estado = 'activo')
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO tb_usersys 
                (nombres_sys, apellidos_sys, tipo_documento, numero_documento, id_rol, correo, numero_contacto, username, password, estado) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssssssssss", $nombres_sys, $apellidos_sys, $tipo_documento, $numero_documento, $id_rol, $correo, $numero_contacto, $username, $passwordHash, $estado);
        
        return $stmt->execute();
    }

    public function obtenerUsuarios()
    {
        $sql = "SELECT id_userSys, nombres_sys, apellidos_sys, id_rol, username, correo, estado FROM tb_usersys";
        $result = $this->conexion->query($sql);

        $usuarios = [];
        while ($fila = $result->fetch_assoc()) {
            $usuarios[] = $fila;
        }

        return $usuarios;
    }

    public function cambiarEstadoUsuario($id, $estado)
    {
        $sql = "UPDATE tb_usersys SET estado = ? WHERE id_userSys = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $estado, $id);

        return $stmt->execute();
    }
}
?>
