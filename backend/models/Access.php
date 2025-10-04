<?php
class Access
{
    private $conn;
    private $table_name = "tb_accesos";

    public $id_acceso;
    public $id_vehiculo;
    public $id_userSys; // <-- ¡Añade esta línea!
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
                SET id_vehiculo=:id_vehiculo, id_userSys=:id_userSys,
                    tipo_accion=:tipo_accion, fecha_hora=:fecha_hora,
                    espacio_asignado=:espacio_asignado";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->id_vehiculo = htmlspecialchars(strip_tags($this->id_vehiculo));
        $this->id_userSys = htmlspecialchars(strip_tags($this->id_userSys));
        $this->tipo_accion = htmlspecialchars(strip_tags($this->tipo_accion));
        $this->fecha_hora = htmlspecialchars(strip_tags($this->fecha_hora));
        $this->espacio_asignado = htmlspecialchars(strip_tags($this->espacio_asignado));

        // Vincular valores
        $stmt->bindParam(":id_vehiculo", $this->id_vehiculo);
        $stmt->bindParam(":id_userSys", $this->id_userSys);
        $stmt->bindParam(":tipo_accion", $this->tipo_accion);
        $stmt->bindParam(":fecha_hora", $this->fecha_hora);
        $stmt->bindParam(":espacio_asignado", $this->espacio_asignado);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}

class GetPlaca {

    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getPlacaPorValor($placa)
    {
        try {
            $query = "SELECT placa FROM tb_vehiculos WHERE placa = :placa";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':placa', $placa, PDO::PARAM_STR);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? $row['placa'] : null;

        } catch (PDOException $e) {
            // Manejo de error: puedes loguearlo o relanzarlo
            error_log("Error al obtener la placa: " . $e->getMessage());
            return null;
        }
    }
}       
?>   
