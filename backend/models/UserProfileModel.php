<?php
// backend/models/UserProfileModel.php

class UserProfileModel {
    private $conn;
    private $table_name = "tb_usersys"; // Sigue apuntando a la tabla de usuarios
    private $roles_table = "tb_roles"; // Para obtener los roles

    // Propiedades del objeto para el perfil
    public $id_userSys;
    public $id_rol;
    public $nombres;
    public $apellidos;
    public $email;
    public $estado;
    public $fecha_registro;
    // La contraseña y otros datos sensibles no se exponen aquí para edición

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para leer los detalles del perfil de un usuario específico por su ID
    public function readProfileById() {
        $query = "SELECT
                    id_userSys, id_rol, nombres, apellidos, email, estado, fecha_registro
                  FROM
                    " . $this->table_name . "
                  WHERE
                    id_userSys = ?
                  LIMIT
                    0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_userSys);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id_rol = $row['id_rol'];
            $this->nombres = $row['nombres'];
            $this->apellidos = $row['apellidos'];
            $this->email = $row['email'];
            $this->estado = $row['estado'];
            $this->fecha_registro = $row['fecha_registro'];
            return true;
        }
        return false;
    }

    // Método para actualizar los datos del perfil de un usuario
    public function updateProfile() {
        $query = "UPDATE
                    " . $this->table_name . "
                  SET
                    nombres = :nombres,
                    apellidos = :apellidos,
                    email = :email,
                    id_rol = :id_rol,
                    estado = :estado
                  WHERE
                    id_userSys = :id_userSys";

        $stmt = $this->conn->prepare($query);

        // Limpiar y vincular los valores
        $this->nombres = htmlspecialchars(strip_tags($this->nombres));
        $this->apellidos = htmlspecialchars(strip_tags($this->apellidos));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->id_rol = htmlspecialchars(strip_tags($this->id_rol));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->id_userSys = htmlspecialchars(strip_tags($this->id_userSys));

        $stmt->bindParam(':nombres', $this->nombres);
        $stmt->bindParam(':apellidos', $this->apellidos);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':id_rol', $this->id_rol);
        $stmt->bindParam(':estado', $this->estado);
        $stmt->bindParam(':id_userSys', $this->id_userSys);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Método para obtener todos los roles (para el select en el frontend)
    public function getAllRoles() {
        $query = "SELECT id_rol, nombre_rol FROM " . $this->roles_table . " ORDER BY nombre_rol";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>