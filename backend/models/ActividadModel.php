<?php
class ActividadModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function registrarActividad($id_userSys, $accion)
    {
        try {
            $stmt = $this->conn->prepare("INSERT INTO tb_actividades (id_userSys, accionActi) VALUES (?, ?)");

            if ($stmt === false) {
                error_log("ActividadModel Error: " . $this->conn->error);
                return false;
            }

            $stmt->bind_param("is", $id_userSys, $accion);

            if (!$stmt->execute()) {
                error_log("ActividadModel Execute Error: " . $stmt->error);
                return false;
            }

            $stmt->close();
            return true;
        } catch (Exception $e) {
            error_log("ActividadModel Exception: " . $e->getMessage());
            return false;
        }
    }
}


?>
