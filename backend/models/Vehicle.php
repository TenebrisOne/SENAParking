<?php
class Vehicle {
    private $conn;
    private $table_name = "tb_vehiculos";
    private $user_park_table = "tb_userPark"; // Nueva propiedad para la tabla de usuarios

    public $id_vehiculo;
    public $id_userPark;
    public $placa;
    public $tipo;
    public $modelo;
    public $color;
    public $nombres_park; // Para el nombre del propietario
    public $apellidos_park; // Para el apellido del propietario

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para leer vehículos, incluyendo la información del propietario
    public function read($search_term = "") {
        $query = "SELECT 
                    v.id_vehiculo, 
                    v.id_userPark, 
                    v.placa, 
                    v.tipo, 
                    v.modelo, 
                    v.color, 
                    up.nombres_park,    -- Añadimos el nombre del propietario
                    up.apellidos_park   -- Añadimos el apellido del propietario
                  FROM 
                    " . $this->table_name . " v
                  JOIN 
                    " . $this->user_park_table . " up 
                  ON 
                    v.id_userPark = up.id_userPark"; // Unimos por id_userPark
        
        if (!empty($search_term)) {
            // La búsqueda ahora será por nombre o apellido del propietario, o por placa
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