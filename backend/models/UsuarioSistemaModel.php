<?php
class Usuario
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // ========================================
    // REGISTRAR USUARIO SISTEMA
    // ========================================
    public function registrarUsuario($nombres_sys, $apellidos_sys, $tipo_documento, $numero_documento, $id_rol, $correo, $numero_contacto, $username, $password, $estado = 'activo')
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO tb_usersys 
                (nombresUsys, apellidosUsys, tipoDocumentoUsys, numeroDocumentoUsys, rolUsys, correoUsys, numeroContactoUsys, usernameUsys, passwordUsys, estadoUsys) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssssssssss", $nombres_sys, $apellidos_sys, $tipo_documento, $numero_documento, $id_rol, $correo, $numero_contacto, $username, $passwordHash, $estado);

        try {
            $stmt->execute();
            return true;
        } catch (mysqli_sql_exception $e) {

            // Código 1062 = Duplicate entry
            if ($e->getCode() == 1062) {
                return "duplicado";
            }

            return false;
        }
    }

    // ========================================
    public function obtenerUsuarios()
    {
        $sql = "SELECT id_userSys, nombresUsys, apellidosUsys, rolUsys, usernameUsys, correoUsys, estadoUsys 
                FROM tb_usersys";

        $result = $this->conexion->query($sql);

        $usuarios = [];
        while ($fila = $result->fetch_assoc()) {
            $usuarios[] = $fila;
        }

        return $usuarios;
    }

    // ========================================
    public function cambiarEstadoUsuario($id, $estado)
    {
        $sql = "UPDATE tb_usersys SET estadoUsys = ? WHERE id_userSys = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $estado, $id);

        return $stmt->execute();
    }

    // ========================================
    // OBTENER USUARIO POR ID (Arreglado)
    // ========================================
    public function obtenerUsuarioSPorId($id)
    {
        $sql = "SELECT * FROM tb_usersys WHERE id_userSys = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);

        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc(); // ← Devuelve info real del usuario
    }

    // ========================================
    // EDITAR USUARIO
    // ========================================
    public function actualizarUsuarioS($id, $nombres_sys, $apellidos_sys, $tipo_documento, $numero_documento, $id_rol, $correo, $numero_contacto, $username)
    {
        $sql = "UPDATE tb_usersys 
                SET rolUsys = ?, tipoDocumentoUsys = ?, numeroDocumentoUsys = ?, 
                    nombresUsys = ?, apellidosUsys = ?, correoUsys = ?, 
                    numeroContactoUsys = ?, usernameUsys = ?
                WHERE id_userSys = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param(
            "ssssssssi",
            $id_rol,
            $tipo_documento,
            $numero_documento,
            $nombres_sys,
            $apellidos_sys,
            $correo,
            $numero_contacto,
            $username,
            $id
        );

        try {
            $stmt->execute();
            return true;
        } catch (mysqli_sql_exception $e) {

            if ($e->getCode() == 1062) {
                return "duplicado";
            }

            return false;
        }
    }
}
