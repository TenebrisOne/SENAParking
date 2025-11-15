<?php


class MostrarDatosModel
{
    private $conn;

    // Constructor recibe la conexión mysqli
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Totales
    public function contarUsuariosSistema()
    {
        $sql = "SELECT COUNT(*) AS total FROM tb_usersys";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function contarVehiculosParqueadero()
    {
        $sql = "
            SELECT COUNT(DISTINCT acceso.id_vehiculo) AS total
            FROM tb_accesos acceso
            WHERE acceso.tipoAccionAcc = 'ingreso'
            AND DATE(acceso.fechaHoraAcc) = CURDATE()
            AND acceso.id_vehiculo NOT IN (
                SELECT id_vehiculo 
                FROM tb_accesos 
                WHERE tipoAccionAcc = 'salida'
                AND DATE(fechaHoraAcc) = CURDATE()
            )
        ";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function contarAccesosHoy()
    {
        $sql = "SELECT COUNT(*) AS total 
                FROM tb_accesos 
                WHERE tipoAccionAcc = 'ingreso' 
                AND DATE(fechaHoraAcc) = CURDATE()";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function contarSalidasHoy()
    {
        $sql = "SELECT COUNT(*) AS total 
                FROM tb_accesos 
                WHERE tipoAccionAcc = 'salida' 
                AND DATE(fechaHoraAcc) = CURDATE()";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    // Reportes dinámicos
    public function obtenerUsuariosSistema()
    {
        $sql = "SELECT id_userSys AS Usuario, nombresUsys AS Nombres, apellidosUsys AS Apellidos, numeroDocumentoUsys As Número_Documento 
                FROM tb_usersys";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerVehiculosParqueadero()
    {
        $sql = "
            SELECT 
                CONCAT(userpark.nombresUpark, ' ', userpark.apellidosUpark) AS propietario,
                vehiculo.placaVeh AS Placa,
                vehiculo.tarjetaPropiedadVeh AS Tarjeta_o_Serial,
                vehiculo.tipoVeh As Tipo_vehículo,
                vehiculo.modeloVeh AS Modelo_vehículo,
                vehiculo.colorVeh AS Color_vehículo,
                userpark.edificioUpark AS Centro_formación,
                MAX(acceso.fechaHoraAcc) AS ultima_entrada
            FROM tb_accesos acceso
            INNER JOIN tb_vehiculos vehiculo ON acceso.id_vehiculo = vehiculo.id_vehiculo
            INNER JOIN tb_userpark userpark ON vehiculo.id_userPark = userpark.id_userPark
            WHERE acceso.tipoAccionAcc = 'ingreso'
            AND DATE(acceso.fechaHoraAcc) = CURDATE()
            AND acceso.id_vehiculo NOT IN (
                SELECT id_vehiculo
                FROM tb_accesos
                WHERE tipoAccionAcc = 'salida'
                AND DATE(fechaHoraAcc) = CURDATE()
            )
            GROUP BY acceso.id_vehiculo
            ORDER BY ultima_entrada DESC
        ";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerAccesosHoy()
    {
        $sql = "SELECT 
                    CONCAT(usersys.nombresUsys, ' ', usersys.apellidosUsys) AS Usuario_Que_Registra_Acceso,
                    vehiculo.tipoVeh AS Vehículo,
                    acceso.fechaHoraAcc AS Fecha_y_Hora,
                    acceso.tipoAccionAcc AS Tipo_de_Acción
                FROM tb_accesos acceso
                INNER JOIN tb_usersys usersys ON acceso.id_userSys = usersys.id_userSys
                INNER JOIN tb_vehiculos vehiculo ON acceso.id_vehiculo = vehiculo.id_vehiculo
                WHERE acceso.tipoAccionAcc = 'ingreso'
                AND DATE(acceso.fechaHoraAcc) = CURDATE()";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerSalidasHoy()
    {
        $sql = "SELECT 
                    CONCAT(usersys.nombresUsys, ' ', usersys.apellidosUsys) AS Usuario_Que_Registra_Salida,
                    vehiculo.tipoVeh AS Vehículo,
                    acceso.fechaHoraAcc AS Fecha_y_Hora,
                    acceso.tipoAccionAcc AS Tipo_de_Acción
                FROM tb_accesos acceso
                INNER JOIN tb_usersys usersys ON acceso.id_userSys = usersys.id_userSys
                INNER JOIN tb_vehiculos vehiculo ON acceso.id_vehiculo = vehiculo.id_vehiculo
                WHERE acceso.tipoAccionAcc = 'salida'
                AND DATE(acceso.fechaHoraAcc) = CURDATE()";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerActividadesRecientes($limite = 7)
    {
        $sql = "SELECT 
                    actividad.id_userSys AS UsuarioID,
                    CONCAT(usersys.nombresUsys, ' ', usersys.apellidosUsys) AS Usuario,
                    actividad.accionActi AS Accion,
                    actividad.fechaHoraActi AS Fecha
                FROM tb_actividades actividad
                INNER JOIN tb_usersys usersys ON actividad.id_userSys = usersys.id_userSys 
                WHERE rolUsys = 'guardia'
                ORDER BY actividad.fechaHoraActi DESC
                LIMIT ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limite);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerVehiculosHoy()
    {
        $sql = "SELECT 
                    vehiculo.id_userPark AS UsuarioID,
                    CONCAT(userpark.nombresUpark, ' ', userpark.apellidosUpark) AS Usuario,
                    vehiculo.placaVeh AS Placa,
                    vehiculo.tipoVeh AS Tipo,
                    vehiculo.modeloVeh AS Modelo,
                    vehiculo.colorVeh AS Color
                FROM tb_vehiculos vehiculo
                INNER JOIN tb_userpark userpark ON vehiculo.id_userPark = userpark.id_userPark 
                ORDER BY vehiculo.id_vehiculo DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
