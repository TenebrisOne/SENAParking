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
        $query = "SELECT id_userPark, nombres_park, apellidos_park FROM " . $this->table_name . " ORDER BY nombres_park ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>