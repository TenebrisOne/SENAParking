<?php

session_start();

if (!isset($_SESSION['rol'])) {
    header("location: ../login.php");
    exit();
}


require_once '../../backend/config/conexion.php';
require_once '../../backend/models/UsuarioSistemaModel.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard_admin.php?mensaje=ID inválido");
    exit;
}

$id = $_GET['id'];
$model = new Usuario($conn);
$usuario = $model->obtenerUsuarioSPorId($id);

if (!$usuario) {
    header("Location: dashboard_admin.php?mensaje=Usuario no encontrado");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <meta name="author" content="AdsoDeveloperSolutions801">
    <meta name="course" content="ADSO 2873801">
    <title>Editar Usuarios del Sistema | SENAParking</title>
    <link href="../public/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="x-icon" href="../public/images/favicon.ico">
    <link rel="stylesheet" href="../public/css/sityles_views.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <!-- Contenedor donde se insertará el header -->
    <div id="header-container"></div>

    <!-- Logo SENA -->
    <img src="../public/images/logo_sena.png" alt="Logo SENA"
        style="position: absolute; top: 100px; right: 70px; width: 100px;">

    <!--Carros decorativos-->
    <img src="../public/images/backgrounds/Image.png" alt="Auto decorativo izquierdo" class="car-left">
    <img src="../public/images/backgrounds/Image.png" alt="Auto decorativo inferior izquierdo" class="car-bottom-left">

    <!-- Flecha de retroceso -->
    <div class="col-2 col-md-1 text-start">
        <div class="back-arrow" onclick="goBack()">
            <i class="fas fa-arrow-left"></i>
            <a class="nav-link" href="/SENAParking/frontend/views/dashboard_admin.php"></a>
        </div>
    </div>

    <!-- Formulario -->
    <div class="register-container">
        <h2>
            <center>Editar Usuario Sistema</center>
        </h2><br>
        <form class="formulario" method="POST" action="/SENAParking/backend/controllers/UsuarioSistemaController.php">
            <input type="hidden" name="id_userSys" value="<?= htmlspecialchars($usuario['id_userSys']) ?>">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombres:</label>
                    <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($usuario['nombres_sys']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="apellido" class="form-label">Apellidos:</label>
                    <input type="text" class="form-control" name="apellido" value="<?= htmlspecialchars($usuario['apellidos_sys']) ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="tipdoc" class="form-label">Tipo de Documento:</label>
                    <select class="form-select" name="tipdoc" required>
                        <option value="">Seleccione el tipo de documento</option>
                        <option value="cedula_ciudadania" <?= $usuario['tipo_documento'] == 'cedula_ciudadania' ? 'selected' : '' ?>>Cédula de Ciudadanía</option>
                        <option value="cedula_extranjeria" <?= $usuario['tipo_documento'] == 'cedula_extranjeria' ? 'selected' : '' ?>>Cédula de Extranjería</option>
                        <option value="pasaporte" <?= $usuario['tipo_documento'] == 'pasaporte' ? 'selected' : '' ?>>Pasaporte</option>
                        <option value="otro" <?= $usuario['tipo_documento'] == 'otro' ? 'selected' : '' ?>>Otros</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="cedula" class="form-label">Documento:</label>
                    <input type="text" class="form-control" name="documento" value="<?= htmlspecialchars($usuario['numero_documento']) ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="rol" class="form-label">Rol:</label>
                    <select class="form-select" name="rol" required>
                        <option value="">Selecciona el rol</option>
                        <option value="1" <?= $usuario['id_rol'] == '1' ? 'selected' : '' ?>>Administrador</option>
                        <option value="2" <?= $usuario['id_rol'] == '2' ? 'selected' : '' ?>>Supervisor</option>
                        <option value="3" <?= $usuario['id_rol'] == '3' ? 'selected' : '' ?>>Guarda de Seguridad</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Correo Electrónico:</label>
                    <input type="email" class="form-control" name="correo" value="<?= htmlspecialchars($usuario['correo']) ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="numero" class="form-label">Número de Contacto:</label>
                    <input type="text" class="form-control" name="numero" value="<?= htmlspecialchars($usuario['numero_contacto']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="usuario" class="form-label">Nombre de Usuario:</label>
                    <input type="text" class="form-control" name="usuario" value="<?= htmlspecialchars($usuario['username']) ?>" required>
                </div>
            </div>

            <!--<div class="row mb-3">
                <div class="col-md-6">
                    <label for="contrasena" class="form-label">Contraseña:</label>
                    <input type="password" class="form-control" name="contrasena" value="<?= htmlspecialchars($usuario['password']) ?>" required>
                </div>
            </div>-->

                <button type="submit" class="btn btn-success">Guardar cambios</button>
                <a href="dashboard_admin.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <!-- Cargar el header-->
    <script src="./../public/js/scriptsDOM.js"></script>

    <!-- script para que cuando se cierre la sesion refresque la ventana -->
    <script src="../public/js/ref_cierre.js"></script>

</body>

</html>