<?php
session_start();
class login
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function validar_login($correo, $password)
    {
        $sql = "SELECT * FROM tb_userSys WHERE correo = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc();

            if (password_verify($password, $usuario["password"])) {

                if ($usuario['estado'] === 'activo') {
                    $_SESSION['correo'] = $correo;
                    $_SESSION['nombre'] = $usuario['nombres_sys'];
                    $_SESSION['rol'] = $usuario['id_rol'];
                    $_SESSION['id_userSys'] = $usuario['id_userSys'];
                    return "activo"; // Login exitoso
                } else {
                    return "inactivo"; // Usuario inactivo
                }
            } else {
                return "errocontra"; // Contraseña incorrecta
            }
        } else {
            return "Nousuario"; // Usuario no encontrado
        }
    }
}
