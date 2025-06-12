<?php
class UsuarioParqueadero {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function registrarUsuario($tipo_user, $tipo_documento, $numero_documento, $nombres, $apellidos, $edificio, $tarjeta_propiedad, $numero_contacto) {
        $sql = "INSERT INTO tb_userpark (tipo_user,tipo_documento,numero_documento,nombres_park,apellidos_park,edificio,tarjeta_propiedad,numero_contacto) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssssssss", $tipo_user, $tipo_documento, $numero_documento, $nombres, $apellidos, $edificio, $tarjeta_propiedad, $numero_contacto);
            $resultado = $stmt->execute();

            if ($resultado) {
                echo json_encode(["success" => true, "message" => "Usuario registrado correctamente"]);
            } else {
                echo json_encode(["success" => false, "message" => "Error al registrar usuario"]);
            }
            exit();
        } else {
            echo json_encode(["success" => false, "message" => "Error en la consulta SQL"]);
            exit();
        }
    }
}
