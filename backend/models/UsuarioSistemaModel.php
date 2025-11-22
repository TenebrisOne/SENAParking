<?php
class Usuario
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function registrarUsuario($nombres_sys, $apellidos_sys, $tipo_documento, $numero_documento, $id_rol, $correo, $numero_contacto, $username, $password, $estado = 'activo')
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO tb_usersys 
                (nombresUsys, apellidosUsys, tipoDocumentoUsys, numeroDocumentoUsys, rolUsys, correoUsys, numeroContactoUsys, usernameUsys, passwordUsys, estadoUsys) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssssssssss", $nombres_sys, $apellidos_sys, $tipo_documento, $numero_documento, $id_rol, $correo, $numero_contacto, $username, $passwordHash, $estado);

        return $stmt->execute();
    }

    public function obtenerUsuarios()
    {
        $sql = "SELECT id_userSys, nombresUsys, apellidosUsys, rolUsys, usernameUsys, correoUsys, estadoUsys FROM tb_usersys";
        $result = $this->conexion->query($sql);

        $usuarios = [];
        while ($fila = $result->fetch_assoc()) {
            $usuarios[] = $fila;
        }

        return $usuarios;
    }
    
    public function cambiarEstadoUsuario($id, $estado)
    {
        $sql = "UPDATE tb_usersys SET estadoUsys = ? WHERE id_userSys = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $estado, $id);

        return $stmt->execute();
    }

    // Obtener usuario por ID (para edición)
    public function obtenerUsuarioSPorId($id)
    {
        $sql = "SELECT * FROM tb_usersys WHERE id_userSys = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Editar usuario
    public function actualizarUsuarioS($id, $nombres_sys, $apellidos_sys, $tipo_documento, $numero_documento, $id_rol, $correo, $numero_contacto, $username)
    {
        $sql = "UPDATE tb_usersys SET rolUsys = ?, tipoDocumentoUsys = ?, numeroDocumentoUsys = ?, nombresUsys = ?, apellidosUsys = ?, correoUsys = ?, numeroContactoUsys = ?, usernameUsys = ?
                    WHERE id_userSys = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssssssssi", $id_rol, $tipo_documento, $numero_documento, $nombres_sys, $apellidos_sys, $correo, $numero_contacto, $username, $id);
        return $stmt->execute();
    }
}
