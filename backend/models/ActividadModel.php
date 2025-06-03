<?php

class ActividadModel
{

    private $conn;

    public function __construct($conn)
    {
        $this ->conn = $conn;
    }

    public function registrarActividad($id_userSys, $accion)
    {
        $stmt = $this->conn->prepare("INSERT INTO tb_actividades (id_userSys, accion) VALUES (?, ?)");
        if ($stmt === false) {
            die("Error al ejecutar la consulta(): " . $this->conn->error);
        }
        $stmt->bind_param("is", $id_userSys, $accion);
        if (!$stmt->execute()) {
            die("Error en execute(): " . $stmt->error);
        }
        
        $stmt->close();
    }
}

?>
