<?php
class Vehicle {
    private $conn;
    private $table_name = "tb_vehiculos";
    private $user_park_table = "tb_userPark"; 

    public $id_vehiculo;
    public $id_userPark;
    public $placa;
    public $tarjeta_propiedad;
    public $tipo;
    public $modelo;
    public $color;
    public $nombres_park;
    public $apellidos_park; 

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para leer vehículos, incluyendo la información del propietario
    public function read($search_term = "") {
        $query = "SELECT 
                    v.id_vehiculo, 
                    v.id_userPark, 
                    v.placa, 
                    v.tarjeta_propiedad,
                    v.tipo, 
                    v.modelo, 
                    v.color, 
                    up.nombres_park,    
                    up.apellidos_park   
                  FROM 
                    " . $this->table_name . " v
                  JOIN 
                    " . $this->user_park_table . " up 
                  ON 
                    v.id_userPark = up.id_userPark"; 
        
        if (!empty($search_term)) {
            
            $query .= " WHERE 
                            up.nombres_park LIKE :search_term 
                            OR up.apellidos_park LIKE :search_term 
                            OR v.placa LIKE :search_term";
        }
        
        $query .= " ORDER BY v.placa ASC";

        $stmt = $this->conn->prepare($query);

        if (!empty($search_term)) {
            $search_term = "%{$search_term}%";
            $stmt->bindParam(":search_term", $search_term);
        }

        $stmt->execute();
        return $stmt;
    }
}
?>