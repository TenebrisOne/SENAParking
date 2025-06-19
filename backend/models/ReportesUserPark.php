<?php
// backend/models/UserPark.php

class UserPark {
    private $conn;
    private $table_name = "tb_userPark";

    // Propiedades del objeto
    public $id_userPark;
    public $tipo_user;
    public $tipo_documento;
    public $numero_documento;
    public $nombres_park;
    public $apellidos_park;
    public $edificio;
    public $tarjeta_propiedad;
    public $numero_contacto;

    // Constructor con conexión a BD
    public function __construct($db) {
        $this->conn = $db;
    }

    // Leer todos los usuarios del parqueadero con paginación y búsqueda
    // La búsqueda ahora será por nombres, apellidos o número de documento
    public function readAll($limit, $offset, $searchTerm = '') {
        $query = "SELECT
                    id_userPark,
                    tipo_user,
                    tipo_documento,
                    numero_documento,
                    nombres_park,
                    apellidos_park,
                    edificio,
                    numero_contacto
                  FROM
                    " . $this->table_name . " ";

        $conditions = [];
        $params = [];

        if ($searchTerm) {
            $conditions[] = "(nombres_park LIKE :searchTerm OR apellidos_park LIKE :searchTerm OR numero_documento LIKE :searchTerm)";
            $params[':searchTerm'] = '%' . $searchTerm . '%'; // Para búsqueda parcial
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $query .= " ORDER BY nombres_park ASC LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);

        // Bind parameters for search
        if ($searchTerm) {
            $stmt->bindParam(':searchTerm', $params[':searchTerm']);
        }
        
        // Bind parameters for pagination
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt;
    }

    // Obtener el conteo total de usuarios (para paginación)
    public function countAll($searchTerm = '') {
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . " ";

        $conditions = [];
        $params = [];

        if ($searchTerm) {
            $conditions[] = "(nombres_park LIKE :searchTerm OR apellidos_park LIKE :searchTerm OR numero_documento LIKE :searchTerm)";
            $params[':searchTerm'] = '%' . $searchTerm . '%';
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $stmt = $this->conn->prepare($query);

        if ($searchTerm) {
            $stmt->bindParam(':searchTerm', $params[':searchTerm']);
        }

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_rows'];
    }

    // Leer un solo usuario por ID (para la pantalla de detalles)
    public function readOne() {
        $query = "SELECT
                    id_userPark,
                    tipo_user,
                    tipo_documento,
                    numero_documento,
                    nombres_park,
                    apellidos_park,
                    edificio,
                    tarjeta_propiedad,
                    numero_contacto
                  FROM
                    " . $this->table_name . "
                  WHERE
                    id_userPark = :id_userPark
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_userPark', $this->id_userPark, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id_userPark = $row['id_userPark'];
            $this->tipo_user = $row['tipo_user'];
            $this->tipo_documento = $row['tipo_documento'];
            $this->numero_documento = $row['numero_documento'];
            $this->nombres_park = $row['nombres_park'];
            $this->apellidos_park = $row['apellidos_park'];
            $this->edificio = $row['edificio'];
            $this->tarjeta_propiedad = $row['tarjeta_propiedad'];
            $this->numero_contacto = $row['numero_contacto'];
            return true;
        }
        return false;
    }
}
?>