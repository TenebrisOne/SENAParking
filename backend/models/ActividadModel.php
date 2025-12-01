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
    $stmt = $this->conn->prepare("INSERT INTO tb_actividades (id_userSys, accionActi) VALUES (?, ?)");

    if ($stmt === false) {
        throw new Exception("Error al preparar la consulta de actividad: " . $this->conn->error);
    }

    $stmt->bind_param("is", $id_userSys, $accion); // "i" para integer, "s" para string

    if (!$stmt->execute()) {
        throw new Exception("Error al ejecutar la consulta de actividad: " . $stmt->error);
    }

    $stmt->close();
}
}


?>
