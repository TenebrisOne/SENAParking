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
        $sql = "SELECT COUNT(*) AS total FROM tb_vehiculos";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function contarAccesosHoy()
    {
        $sql = "SELECT COUNT(*) AS total FROM tb_accesos WHERE tipo_accion = 'ingreso' AND DATE(fecha_hora) = CURDATE()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function contarSalidasHoy()
    {
        $sql = "SELECT COUNT(*) AS total FROM tb_accesos WHERE tipo_accion = 'salida' AND DATE(fecha_hora) = CURDATE()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // Reportes dinámicos
    public function obtenerUsuariosSistema()
    {
        $sql = "SELECT id_userSys AS Usuario, nombres_sys AS Nombres, apellidos_sys AS Apellidos, numero_documento FROM tb_usersys";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerVehiculosParqueadero()
    {
        $sql = "SELECT 
        CONCAT(u.nombres_park, ' ', u.apellidos_park) AS propietario,
        v.placa,
        v.tarjeta_propiedad,
        v.tipo,
        v.modelo,
        v.color
    FROM tb_vehiculos v
    INNER JOIN tb_userpark u ON v.id_userPark = u.id_userPark";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerAccesosHoy()
    {
        $sql = "SELECT 
        CONCAT(u.nombres_sys, ' ', u.apellidos_sys) AS Usuario,
        v.tipo AS Vehículo,
        a.fecha_hora,
        a.tipo_accion
        FROM tb_accesos a
        INNER JOIN tb_usersys u ON a.id_userSys = u.id_userSys
        INNER JOIN tb_vehiculos v ON a.id_vehiculo = v.id_vehiculo
        WHERE a.tipo_accion = 'ingreso'
        AND DATE(a.fecha_hora) = CURDATE()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerSalidasHoy()
    {
        $sql = "SELECT 
        CONCAT(u.nombres_sys, ' ', u.apellidos_sys) AS Usuario,
        v.tipo AS Vehículo,
        a.fecha_hora,
        a.tipo_accion
        FROM tb_accesos a
        INNER JOIN tb_usersys u ON a.id_userSys = u.id_userSys
        INNER JOIN tb_vehiculos v ON a.id_vehiculo = v.id_vehiculo
        WHERE a.tipo_accion = 'salida'
        AND DATE(a.fecha_hora) = CURDATE()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // CÓDIGO AGREGADO POR CRISTIAN 👀⚠️🚧
    // 🔹 NUEVA FUNCIÓN INTEGRADA: Actividades recientes
    public function obtenerActividadesRecientes($limite = 7)
    {
        $sql = "SELECT 
                a.id_userSys AS UsuarioID,
                CONCAT(u.nombres_sys, ' ', u.apellidos_sys) AS Usuario,
                a.accion AS Accion,
                a.fecha_hora AS Fecha
            FROM tb_actividades a
            INNER JOIN tb_usersys u ON a.id_userSys = u.id_userSys WHERE id_rol = 3
            ORDER BY a.fecha_hora DESC
            LIMIT :limite";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerVehiculosHoy()
    {
        $sql = "SELECT 
                    a.id_userPark AS UsuarioID,
                    CONCAT(u.nombres_park, ' ', u.apellidos_park) AS Usuario,
                    a.placa AS Placa,
                    a.tipo AS Tipo,
                    a.modelo AS Modelo,
                    a.color AS Color
                FROM tb_vehiculos a
                INNER JOIN tb_userpark u ON a.id_userPark = u.id_userPark 
                ORDER BY a.id_vehiculo DESC
                ";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
