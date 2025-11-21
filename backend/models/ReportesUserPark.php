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
                    tipoUserUPark,
                    tipoDocumentoUpark,
                    numeroDocumentoUpark,
                    nombresUpark,
                    apellidosUpark,
                    edificioUpark,
                    numeroContactoUpark
                  FROM
                    " . $this->table_name . " ";

        $conditions = [];
        $params = [];

        if ($searchTerm) {
            $conditions[] = "(nombresUpark LIKE :searchTerm OR apellidosUpark LIKE :searchTerm OR numeroDocumentoUpark LIKE :searchTerm)";
            $params[':searchTerm'] = '%' . $searchTerm . '%'; // Para búsqueda parcial
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $query .= " ORDER BY nombresUpark ASC LIMIT :limit OFFSET :offset";

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
            $conditions[] = "(nombresUpark LIKE :searchTerm OR apellidosUpark LIKE :searchTerm OR numeroDocumentoUpark LIKE :searchTerm)";
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
                    tipoUserUpark,
                    tipoDocumentoUpark,
                    numeroDocumentoUpark,
                    nombresUpark,
                    apellidosUpark,
                    edificioUpark,
                    numeroContactoUpark
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
            $this->tipo_user = $row['tipoUserUpark'];
            $this->tipo_documento = $row['tipoDocumentoUpark'];
            $this->numero_documento = $row['numeroDocumentoUpark'];
            $this->nombres_park = $row['nombresUpark'];
            $this->apellidos_park = $row['apellidosUpark'];
            $this->edificio = $row['edificioUpark'];
            $this->numero_contacto = $row['numeroContactoUpark'];
            return true;
        }
        return false;
    }
}
?>