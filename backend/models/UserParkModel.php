<?php
include_once '../config/conexion.php'; 

class UserPark {
    private $conn;
    private $table_name = "tb_userPark";

    public $id_userPark;
    public $nombres_park;
    public $apellidos_park;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        $query = "SELECT id_userPark, nombresUpark, apellidosUpark FROM " . $this->table_name . " ORDER BY nombresUpark ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>