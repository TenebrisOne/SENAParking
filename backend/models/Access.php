<?php
class Access
{
    private $conn;
    private $table_name = "tb_accesos";

    public $id_acceso;
    public $id_vehiculo;
    public $id_userSys;
    public $tipo_accion;
    
    public $fecha_hora;
    public $espacio_asignado;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  (id_vehiculo, id_userSys, tipoAccionAcc, fechaHoraAcc, espacioAsignadoAcc)
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            die("Error al preparar la consulta: " . $this->conn->error);
        }

        // Vincular los parámetros
        $stmt->bind_param(
            "iissi", // tipos: int, int, string, string, int
            $this->id_vehiculo,
            $this->id_userSys,
            $this->tipo_accion,
            $this->fecha_hora,
            $this->espacio_asignado
        );

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            die("Error al ejecutar la consulta: " . $stmt->error);
        }
    }
}




class GetPlaca
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getPlacaPorValor($placa)
    {
        $query = "SELECT placa FROM tb_vehiculos WHERE placa = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            error_log("Error al preparar la consulta: " . $this->conn->error);
            return null;
        }

        $stmt->bind_param("s", $placa); // "s" = string

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            return $row ? $row['placa'] : null;
        } else {
            error_log("Error al ejecutar la consulta: " . $stmt->error);
            return null;
        }
    }
}
?>

