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
                echo'<script type="text/javascript">
                alert("Usuario registrado con éxito");
                window.location.href="../../frontend/views/dashboard_admin.html";
                </script>';
            } else {
                echo'<script type="text/javascript">
                alert("Error al registrar usuario");
                window.location.href="../../frontend/views/reg_userParking.html";
                </script>';
            }
            exit();
        } else {
            echo json_encode(["success" => false, "message" => "Error en la consulta SQL"]);
            exit();
        }
    }
}
