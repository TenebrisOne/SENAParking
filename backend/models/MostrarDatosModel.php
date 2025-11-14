<?php
require_once __DIR__ . '/../config/conexion.php';

class MostrarDatosModel
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Totales
    public function contarUsuariosSistema()
    {
        $sql = "SELECT COUNT(*) AS total FROM tb_usersys";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
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
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function contarAccesosHoy()
    {
        $sql = "SELECT COUNT(*) AS total FROM tb_accesos WHERE tipoAccionAcc = 'ingreso' AND DATE(fechaHoraAcc) = CURDATE()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function contarSalidasHoy()
    {
        $sql = "SELECT COUNT(*) AS total FROM tb_accesos WHERE tipoAccionAcc = 'salida' AND DATE(fechaHoraAcc) = CURDATE()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // Reportes dinámicos
    public function obtenerUsuariosSistema()
    {
        $sql = "SELECT id_userSys AS Usuario, nombresUsys AS Nombres, apellidosUsys AS Apellidos, numeroDocumentoUsys FROM tb_usersys";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerVehiculosParqueadero()
    {
        $sql = "
            SELECT 
                CONCAT(userpark.nombresUpark, ' ', userpark.apellidosUpark) AS propietario,
                vehiculo.placa,
                vehiculo.tarjeta_propiedad AS Tarjeta__de_Propiedad_o_Serial,
                vehiculo.tipo,
                vehiculo.modelo,
                vehiculo.color,
                userpark.edificio AS Centro_formación,
                MAX(accesos.fechaHoraAcc) AS ultima_entrada
            FROM tb_accesos acessos
            INNER JOIN tb_vehiculos vehiculo ON acceso.id_vehiculo = vehiculo.id_vehiculo
            INNER JOIN tb_userpark userpark ON vehiculo.id_userPark = userpark.id_userPark
            WHERE acceso.tipoAccionAcc = 'ingreso'
            AND DATE(accesos.fechaHoraAcc) = CURDATE()
            AND accesos.id_vehiculo NOT IN (
                SELECT id_vehiculo
                FROM tb_accesos
                WHERE tipo_accion = 'salida'
                AND DATE(fechaHoraAcc) = CURDATE()
            )
            GROUP BY a.id_vehiculo
            ORDER BY ultima_entrada DESC
        ";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obtenerAccesosHoy()
    {
        $sql = "SELECT 
        CONCAT(usersys.nombresUsys, ' ', usersys.apellidosUsys) AS Usuario_Que_Registra_Acceso,
        vehiculo.tipo AS Vehículo,
        acceso.fechaHoraAcc AS Fecha_y_Hora,
        a.tipoAccionAcc AS Tipo_de_Acción
        FROM tb_accesos acceso
        INNER JOIN tb_usersys usersys ON a.id_userSys = u.id_userSys
        INNER JOIN tb_vehiculos vehiculo ON a.id_vehiculo = v.id_vehiculo
        WHERE acceso.tipoAccionAcc = 'ingreso'
        AND DATE(acceso.fechaHoraAcc) = CURDATE()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerSalidasHoy()
    {
        $sql = "SELECT 
        CONCAT(usersys.nombresUsys, ' ', usersys.apellidosUsys) AS Usuario_Que_Registra_Salida,
        vehiculo.tipo AS Vehículo,
        acceso.fechaHoraAcc AS Fecha_y_Hora,
        acceso.tipoAccionAcc AS Tipo_de_Acción
        FROM tb_accesos acceso
        INNER JOIN tb_usersys usersys ON a.id_userSys = u.id_userSys
        INNER JOIN tb_vehiculos vehiculo ON a.id_vehiculo = v.id_vehiculo
        WHERE acceso.tipoAccionAcc = 'salida'
        AND DATE(acceso.fechaHoraAcc) = CURDATE()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // CÓDIGO AGREGADO POR CRISTIAN 👀⚠️🚧
    // 🔹 NUEVA FUNCIÓN INTEGRADA: Actividades recientes
    public function obtenerActividadesRecientes($limite = 7)
    {
        $sql = "SELECT 
                actividad.id_userSys AS UsuarioID,
                CONCAT(usersys.nombresUsys, ' ', usersys.apellidosUsys) AS Usuario,
                actividad.accionActi AS Accion,
                actividad.fechaHoraActi AS Fecha
            FROM tb_actividades actividad
            INNER JOIN tb_usersys usersys ON actividad.id_userSys = usersys.id_userSys WHERE rolUsys = 'guardia'
            ORDER BY actividad.fechaHoraActi DESC
            LIMIT :limite";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                ORDER BY vehiculo.id_vehiculo DESC
                ";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
