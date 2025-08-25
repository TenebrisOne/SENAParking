<?php
require_once __DIR__ . '/../config/conexion.php';

class MostrarDatosModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Totales
    public function contarUsuariosSistema() {
        $sql = "SELECT COUNT(*) AS total FROM tb_usersys";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function contarUsuariosParqueadero() {
        $sql = "SELECT COUNT(*) AS total FROM tb_userpark";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function contarAccesosHoy() {
        $sql = "SELECT COUNT(*) AS total FROM tb_accesos WHERE tipo_accion = 'ingreso' AND DATE(fecha_hora) = CURDATE()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function contarSalidasHoy() {
        $sql = "SELECT COUNT(*) AS total FROM tb_accesos WHERE tipo_accion = 'salida' AND DATE(fecha_hora) = CURDATE()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // Reportes dinámicos
    public function obtenerUsuariosSistema() {
        $sql = "SELECT id_userSys AS Usuario, nombres_sys AS Nombres, apellidos_sys AS Apellidos, numero_documento FROM tb_usersys";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerUsuariosParqueadero() {
        $sql = "SELECT id_userPark AS Usuario, nombres_park AS Nombres, apellidos_park AS Apellidos, numero_documento, tipo_user, edificio FROM tb_userpark";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerAccesosHoy() {
        $sql = "SELECT id_acceso AS Acceso, id_userSys AS Usuario, id_vehiculo AS Vehículo, fecha_hora, tipo_accion FROM tb_accesos WHERE tipo_accion = 'ingreso' AND DATE(fecha_hora) = CURDATE()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerSalidasHoy() {
        $sql = "SELECT id_acceso AS Acceso, id_userSys AS Usuario, id_vehiculo AS Vehículo, fecha_hora, tipo_accion FROM tb_accesos WHERE tipo_accion = 'salida' AND DATE(fecha_hora) = CURDATE()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
