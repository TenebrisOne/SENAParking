<?php

class Usuario {
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // ✅ Registrar usuarios con estado por defecto "activo"
    public function registrarUsuario($nombres_sys, $apellidos_sys, $tipo_documento, $numero_documento, $id_rol, $correo, $numero_contacto, $username, $password, $estado = 'activo')
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO tb_usersys (nombres_sys, apellidos_sys, tipo_documento, numero_documento, id_rol, correo, numero_contacto, username, password, estado) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; // ✅ Estado por defecto: activo
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssssssssss", $nombres_sys, $apellidos_sys, $tipo_documento, $numero_documento, $id_rol, $correo, $numero_contacto, $username, $passwordHash, $estado);
        return  $stmt->execute();
    }

    // ✅ Obtener usuarios y su estado
    public function obtenerUsuarios()
    {
        $sql = "SELECT id_userSys, nombres_sys, id_rol, username, correo, estado FROM tb_usersys";
        $result = $this->conexion->query($sql);
        $usuarios = [];
        while ($fila = $result->fetch_assoc()) {
            $usuarios[] = $fila;
        }
        return $usuarios;
    }

    // ✅ Cambiar estado (habilitar/deshabilitar usuario)
    public function cambiarEstadoUsuario($id, $estado)
    {
        $stmt = $this ->conexion->prepare("UPDATE tb_usersys SET estado = ? WHERE id_userSys = ?");
        $stmt->bind_param("si", $estado, $id);
        return $stmt->execute();
    }
}

