<?php
class UsuarioParqueadero {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function registrarUsuario($tipo_user, $tipo_documento, $numero_documento, $nombres, $apellidos, $edificio, $tarjeta_propiedad, $numero_contacto, $hora_entrada) {
        $sql = "INSERT INTO tb_userpark (tipo_user,tipo_documento,numero_documento,nombres_park,apellidos_park,edificio,tarjeta_propiedad,numero_contacto,hora_entrada) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssssssss", $tipo_user, $tipo_documento, $numero_documento, $nombres, $apellidos, $edificio, $tarjeta_propiedad, $numero_contacto, $hora_entrada);
            $resultado = $stmt->execute();

            return $resultado;
        } else {
            return "Ocurrió un error al registrar el usuario.";
        }
    }
}
