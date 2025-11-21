<?php
class Vehicle {
    private $conn;
    private $table_name = "tb_vehiculos";
    private $user_park_table = "tb_userPark"; 

    public $id_vehiculo;
    public $id_userPark;
    public $placaVeh;
    public $tarjetaPropiedadVeh;
    public $tipoVeh;
    public $modeloVeh;
    public $colorVeh;
    public $nombresUpark;
    public $apellidosUpark; 

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para leer vehículos, incluyendo la información del propietario
    public function read($search_term = "") {
        $query = "SELECT 
                    vehiculo.id_vehiculo, 
                    vehiculo.id_userPark, 
                    vehiculo.placaVeh, 
                    vehiculo.tarjetaPropiedadVeh,
                    vehiculo.tipoVeh, 
                    vehiculo.modeloVeh, 
                    vehiculo.colorVeh, 
                    userpark.nombresUpark,    
                    userpark.apellidosUpark   
                  FROM 
                    " . $this->table_name . " vehiculo
                  JOIN 
                    " . $this->user_park_table . " userpark
                  ON 
                    vehiculo.id_userPark = userpark.id_userPark"; 
        
        if (!empty($search_term)) {
            
            $query .= " WHERE 
                            userpark.nombresUpark LIKE :search_term 
                            OR userpark.apellidosUpark LIKE :search_term 
                            OR vehiculo.placaVeh LIKE :search_term";
        }
        
        $query .= " ORDER BY vehiculo.placaVeh ASC";

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