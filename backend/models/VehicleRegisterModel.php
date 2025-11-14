<?php
include_once '../config/conexion.php'; 

class Vehicle {
    private $conn;
    private $table_name = "tb_vehiculos";
    private $user_park_table = "tb_userPark";

    public $id_vehiculo; // Asegúrate de que esta propiedad exista
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

    public function readOne() {
      $query = "SELECT 
                  vehiculo.id_vehiculo, 
                  vehiculo.id_userPark, 
                  vehiculo.placa,
                  vehiculo.tarjeta_propiedad,
                  vehiculo.tipo, 
                  vehiculo.modelo, 
                  vehiculo.color, 
                  userpark.nombresUpark,    
                  userpark.apellidosUpark   
                FROM 
                  " . $this->table_name . " vehiculo
                JOIN 
                  " . $this->user_park_table . " userpark 
                ON 
                  vehiculo.id_userPark = userpark.id_userPark
                WHERE 
                  vehiculo.id_vehiculo = :id_vehiculo
                LIMIT 0,1"; 

      $stmt = $this->conn->prepare($query);
      // Asegúrate de que $this->id_vehiculo esté correctamente seteado antes de llamar a readOne()
      $stmt->bindParam(':id_vehiculo', $this->id_vehiculo); 
      $stmt->execute();

      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($row) {
          $this->id_userPark = $row['id_userPark'];
          $this->placa = $row['placaVeh'];
          $this->tarjeta_propiedad = $row['tarjetaPropiedadVeh'];
          $this->tipo = $row['tipoVeh'];
          $this->modelo = $row['modeloVeh'];
          $this->color = $row['colorVeh'];
          $this->nombres_park = $row['nombresUpark'];
          $this->apellidos_park = $row['apellidosUpark'];
          return true;
      }
      return false;
  }

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
  
  public function placaExists() {
    $query = "SELECT id_vehiculo FROM " . $this->table_name . " WHERE placaVeh = :placa LIMIT 0,1";
    
    $stmt = $this->conn->prepare($query);
    $this->placa = htmlspecialchars(strip_tags($this->placa)); // Limpiar la placa
    $stmt->bindParam(':placa', $this->placa);
    $stmt->execute();

    $num = $stmt->rowCount(); // Cuenta las filas encontradas

    // Si se encuentra una fila, significa que la placa ya existe
    return $num > 0;
}

public function create() {
  // *** CAMBIO AQUÍ: Verificar si la placa ya existe antes de intentar crear ***
  if ($this->placaExists()) {
      return false; // Retorna false si la placa ya está en uso
  }

  $query = "INSERT INTO " . $this->table_name . "
            SET
              id_userPark=:id_userPark,
              placaVeh=:placa,
              tarjetaPropiedadVeh=:tarjeta_propiedad,
              tipoVeh=:tipo,
              modeloVeh=:modelo,
              colorVeh=:color";

  $stmt = $this->conn->prepare($query);

  $this->id_userPark = htmlspecialchars(strip_tags($this->id_userPark));
  $this->placa = htmlspecialchars(strip_tags($this->placa));
  $this->tarjeta_propiedad = htmlspecialchars(strip_tags($this->tarjeta_propiedad));
  $this->tipo = htmlspecialchars(strip_tags($this->tipo));
  $this->modelo = htmlspecialchars(strip_tags($this->modelo));
  $this->color = htmlspecialchars(strip_tags($this->color));

  $stmt->bindParam(":id_userPark", $this->id_userPark);
  $stmt->bindParam(":placa", $this->placa);
  $stmt->bindParam(":tarjeta_propiedad", $this->tarjeta_propiedad);
  $stmt->bindParam(":tipo", $this->tipo);
  $stmt->bindParam(":modelo", $this->modelo);
  $stmt->bindParam(":color", $this->color);

  // La línea 89 es $stmt->execute() en tu traceback
  if ($stmt->execute()) { 
      return true;
  }
  return false;
}


// NUEVO MÉTODO: Actualizar un registro de vehículo existente
public function update() {
  // PRIMERO: Verificar si la placa ya existe para *OTRO* vehículo
  $query_check_placa = "SELECT id_vehiculo FROM " . $this->table_name . " 
                        WHERE placaVeh = :placa AND id_vehiculo != :id_vehiculo 
                        LIMIT 0,1";
  
  $stmt_check_placa = $this->conn->prepare($query_check_placa);
  
  // Limpiar datos para la verificación
  $this->placa = htmlspecialchars(strip_tags($this->placa));
  $this->id_vehiculo = htmlspecialchars(strip_tags($this->id_vehiculo)); // Asegúrate de limpiar también el ID
  
  $stmt_check_placa->bindParam(':placa', $this->placa);
  $stmt_check_placa->bindParam(':id_vehiculo', $this->id_vehiculo);
  $stmt_check_placa->execute();

  // Si se encuentra una fila, significa que la placa ya existe para un vehículo DIFERENTE
  if ($stmt_check_placa->rowCount() > 0) {
      // Puedes registrar esto para depuración en el log de errores de PHP
      error_log("Intento de actualización con placa duplicada ('" . $this->placa . "') para otro vehículo ID:" . $this->id_vehiculo);
      return false; // Indica que la actualización falló debido a placa duplicada
  }

  // SI LA PLACA ES ÚNICA (O ES LA MISMA PLACA DEL VEHÍCULO QUE SE ESTÁ EDITANDO), PROCEDER CON LA ACTUALIZACIÓN
  $query = "UPDATE " . $this->table_name . "
            SET
              id_userPark=:id_userPark,
              placaVeh=:placa,
              tarjetaPropiedadVeh=:tarjeta_propiedad,
              tipoVeh=:tipo,
              modeloVeh=:modelo,
              colorVeh=:color
            WHERE
              id_vehiculo = :id_vehiculo"; // Usamos el ID para la condición WHERE

  $stmt = $this->conn->prepare($query);

  // Limpiar datos para la actualización final (si no se hizo ya)
  $this->id_userPark = htmlspecialchars(strip_tags($this->id_userPark));
  // La placa y el id_vehiculo ya están limpios de la verificación anterior
  $this->tarjeta_propiedad = htmlspecialchars(strip_tags($this->tarjeta_propiedad));
  $this->tipo = htmlspecialchars(strip_tags($this->tipo));
  $this->modelo = htmlspecialchars(strip_tags($this->modelo));
  $this->color = htmlspecialchars(strip_tags($this->color));

  // Vincular valores
  $stmt->bindParam(":id_userPark", $this->id_userPark);
  $stmt->bindParam(":placa", $this->placa);
  $stmt->bindParam(":tarjeta_propiedad", $this->tarjeta_propiedad);
  $stmt->bindParam(":tipo", $this->tipo);
  $stmt->bindParam(":modelo", $this->modelo);
  $stmt->bindParam(":color", $this->color);
  $stmt->bindParam(':id_vehiculo', $this->id_vehiculo);

  try {
      if ($stmt->execute()) {
          return true;
      }
  } catch (PDOException $e) {
      // Capturar y registrar otras excepciones PDO que no sean de duplicidad (ej. problemas de conexión)
      error_log("Error PDO al actualizar vehículo (ID: " . $this->id_vehiculo . "): " . $e->getMessage());
  }
  return false;
}
}



?>