<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST["correo"];
    $password = $_POST["password"];

    $conn = new mysqli("localhost", "root", "", "senaparking_db");

    require_once __DIR__ . '/./backend/models/ActividadModel.php';
    $actividadModel = new ActividadModel($conn);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT * FROM tb_userSys WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

        if (password_verify($password, $usuario["password"])) {
            $_SESSION['correo'] = $correo;
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['id_rol'];

            $actividadModel->registrarActividad($usuario['id_userSys'], 'Inicio de sesion');

            switch ($usuario['id_rol']) {
                case '1':
                    header("Location: /SENAParking/frontend/views/dashboard_admin.php");
                    exit();
                case '2':
                    header("Location: /SENAParking/frontend/views/dashboard_supervisor.php");
                    exit();
                case '3':
                    header("Location: /SENAParking/frontend/views/dashboard_guardia.php");
                    exit();
            }
        } else {
            $error = "Contraseña incorrecta";
        }
    } else {
        $error = "Usuario no encontrado";
    }

    $conn->close();
}
