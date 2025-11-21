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
        die("Error al preparar la consulta: " . $this->conn->error);
    }

    $stmt->bind_param("is", $id_userSys, $accion); // "i" para integer, "s" para string

    if (!$stmt->execute()) {
        die("Error al ejecutar la consulta: " . $stmt->error);
    }

    $stmt->close();
}
}


?>
