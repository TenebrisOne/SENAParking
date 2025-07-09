<?php
// backend/models/Access.php

class Access {
    private $conn;
    private $table_name = "tb_accesos";
    private $vehicle_table = "tb_vehiculos"; // Necesitamos unir con vehículos para obtener la placa

    public $id_acceso;
    public $id_vehiculo;
    public $id_userSys;
    public $tipo_accion;
    public $fecha_hora;
    public $espacio_asignado;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener estadísticas de ingresos/salidas para un usuario específico
    public function getUserAccessStats($id_userPark) {
        // Necesitamos unir tb_accesos con tb_vehiculos para filtrar por id_userPark
        $query = "SELECT
                    SUM(CASE WHEN a.tipo_accion = 'ingreso' THEN 1 ELSE 0 END) as ingresos,
                    SUM(CASE WHEN a.tipo_accion = 'salida' THEN 1 ELSE 0 END) as salidas
                  FROM
                    " . $this->table_name . " a
                  JOIN
                    " . $this->vehicle_table . " v ON a.id_vehiculo = v.id_vehiculo
                  WHERE
                    v.id_userPark = :id_userPark";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_userPark', $id_userPark, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row; // Devuelve un array con 'ingresos' y 'salidas'
    }

    // Obtener la lista detallada de accesos para un usuario
    public function getUserAccesses($id_userPark) {
        $query = "SELECT
                    a.fecha_hora,
                    a.tipo_accion,
                    v.placa,
                    a.espacio_asignado
                  FROM
                    " . $this->table_name . " a
                  JOIN
                    " . $this->vehicle_table . " v ON a.id_vehiculo = v.id_vehiculo
                  WHERE
                    v.id_userPark = :id_userPark
                  ORDER BY
                    a.fecha_hora DESC"; // Más reciente primero

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_userPark', $id_userPark, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt; // Devuelve el PDOStatement
    }

    // Métodos para reportes generales (se usarán en la tercera pantalla)
    public function getGeneralAccessStats($startDate = null, $endDate = null) {
        $query = "SELECT
                    SUM(CASE WHEN tipo_accion = 'ingreso' THEN 1 ELSE 0 END) as total_ingresos,
                    SUM(CASE WHEN tipo_accion = 'salida' THEN 1 ELSE 0 END) as total_salidas
                  FROM
                    " . $this->table_name;
        
        $conditions = [];
        $params = [];

        if ($startDate) {
            $conditions[] = "fecha_hora >= :startDate";
            $params[':startDate'] = $startDate;
        }
        if ($endDate) {
            $conditions[] = "fecha_hora <= :endDate";
            $params[':endDate'] = $endDate;
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getVehicleTypeAccessStats($startDate = null, $endDate = null) {
        $query = "SELECT
                    v.tipo,
                    SUM(CASE WHEN a.tipo_accion = 'ingreso' THEN 1 ELSE 0 END) as ingresos,
                    SUM(CASE WHEN a.tipo_accion = 'salida' THEN 1 ELSE 0 END) as salidas
                  FROM
                    " . $this->table_name . " a
                  JOIN
                    " . $this->vehicle_table . " v ON a.id_vehiculo = v.id_vehiculo";
        
        $conditions = [];
        $params = [];

        if ($startDate) {
            $conditions[] = "a.fecha_hora >= :startDate";
            $params[':startDate'] = $startDate;
        }
        if ($endDate) {
            $conditions[] = "a.fecha_hora <= :endDate";
            $params[':endDate'] = $endDate;
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $query .= " GROUP BY v.tipo";

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener el aforo total del parqueadero de la tabla tb_configPark
    public function getParkingCapacity() {
        $query = "SELECT SUM(adelante_carros + adelante_motos + adelante_ciclas + trasera_carros) as total_capacity 
                  FROM tb_configPark LIMIT 0,1"; // Asumiendo que solo hay una fila de configuración
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_capacity'] ?? 0;
    }

    // Obtener la ocupación actual del parqueadero (estimada, vehículos 'dentro')
    // Esto es más complejo ya que implica ver si el último acceso fue un 'ingreso' y no una 'salida'
    // Para simplificar para el reporte, podemos contar ingresos menos salidas en un periodo
    // Sin embargo, para "ocupación total del parqueadero" en reportes generales, se suele referir a un conteo NETO.
    // Una forma simple (pero no perfecta para tiempo real): Contar vehículos que tienen un 'ingreso' y no una 'salida' posterior.
    // Otra forma para el reporte general (más sencilla): Ingresos acumulados - salidas acumuladas en el historial.
    // Para este reporte general, la "ocupación total" será (ingresos - salidas) en el rango, no la ocupación en tiempo real.
    // Si necesitas la ocupación EN TIEMPO REAL, necesitaríamos una lógica más robusta que verifique el último movimiento de CADA vehículo.
    // Para el contexto de "Reportes Generales", "porcentaje de ocupación" se entenderá como (Ingresos netos en el rango / Capacidad).
    // O mejor, el conteo actual de vehículos que están dentro. Esto requiere una subconsulta o vista.

    // Método para obtener el conteo de vehículos actualmente "dentro" del parqueadero
    // Este método es crucial para el cálculo de la ocupación en tiempo real (si eso es lo que se busca)
    // Para el reporte GENERAL, la ocupación se podría entender como (Ingresos - Salidas) / Capacidad, pero eso es engañoso.
    // Vamos a añadir un método para contar los vehículos que tienen un 'ingreso' como su última acción y no tienen una 'salida' posterior
    public function getCurrentOccupancyCount() {
        $query = "SELECT COUNT(DISTINCT a.id_vehiculo) as current_occupancy_count
                  FROM " . $this->table_name . " a
                  WHERE a.tipo_accion = 'ingreso' AND NOT EXISTS (
                      SELECT 1 FROM " . $this->table_name . " a2
                      WHERE a2.id_vehiculo = a.id_vehiculo
                      AND a2.fecha_hora > a.fecha_hora
                      AND a2.tipo_accion = 'salida'
                  ) AND EXISTS ( -- Asegura que es el último ingreso
                      SELECT 1 FROM " . $this->table_name . " a3
                      WHERE a3.id_vehiculo = a.id_vehiculo
                      GROUP BY a3.id_vehiculo
                      HAVING MAX(a3.fecha_hora) = a.fecha_hora
                  )";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['current_occupancy_count'] ?? 0;
    }

    // Métodos para gráficos diarios (para la tercera pantalla)
    public function getDailyAccessStats($startDate, $endDate) {
        $query = "SELECT
                    DATE(fecha_hora) as access_date,
                    SUM(CASE WHEN tipo_accion = 'ingreso' THEN 1 ELSE 0 END) as daily_ingresos,
                    SUM(CASE WHEN tipo_accion = 'salida' THEN 1 ELSE 0 END) as daily_salidas
                  FROM
                    " . $this->table_name . "
                  WHERE
                    fecha_hora >= :startDate AND fecha_hora <= :endDate
                  GROUP BY
                    access_date
                  ORDER BY
                    access_date ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':startDate', $startDate);
        $stmt->bindParam(':endDate', $endDate);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Métodos para gráficos por hora (para la tercera pantalla)
    public function getHourlyAccessStats($startDate, $endDate) {
        $query = "SELECT
                    HOUR(fecha_hora) as access_hour,
                    SUM(CASE WHEN tipo_accion = 'ingreso' THEN 1 ELSE 0 END) as hourly_ingresos,
                    SUM(CASE WHEN tipo_accion = 'salida' THEN 1 ELSE 0 END) as hourly_salidas
                  FROM
                    " . $this->table_name . "
                  WHERE
                    fecha_hora >= :startDate AND fecha_hora <= :endDate
                  GROUP BY
                    access_hour
                  ORDER BY
                    access_hour ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':startDate', $startDate);
        $stmt->bindParam(':endDate', $endDate);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>