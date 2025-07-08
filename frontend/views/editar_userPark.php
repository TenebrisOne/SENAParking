<?php
require_once '../../backend/config/conexion.php';
require_once '../../backend/models/UsuarioParqueaderoModel.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard_admin.php?mensaje=ID inválido");
    exit;
}

$id = $_GET['id'];
$modelo = new UsuarioParqueadero($conn);
$usuario = $modelo->obtenerUsuarioPorId($id);

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
    <title>Editar Usuario Parqueadero | SENAParking</title>
    <link href="/SENAParking/frontend/public/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="x-icon" href="../public/images/favicon.ico">
    <link rel="stylesheet" href="../public/css/sityles_views.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <div id="header-container"></div>

    <img src="../public/images/logo_sena.png" alt="Logo SENA"
        style="position: absolute; top: 100px; right: 70px; width: 100px;">

    <div class="register-container">
        <h2><center>Editar Usuario Parqueadero</center></h2>
        <form class="formulario" method="POST" action="/SENAParking/backend/controllers/UsuarioParqueaderoController.php">
            <input type="hidden" name="id_userPark" value="<?= htmlspecialchars($usuario['id_userPark']) ?>">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nombres:</label>
                    <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($usuario['nombres_park']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Apellidos:</label>
                    <input type="text" class="form-control" name="apellido" value="<?= htmlspecialchars($usuario['apellidos_park']) ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Tipo de documento:</label>
                    <select class="form-select" name="tipdoc" required>
                        <option value="">Seleccione tipo de documento</option>
                        <option value="cedula_ciudadania" <?= $usuario['tipo_documento'] == 'cedula_ciudadania' ? 'selected' : '' ?>>Cédula de Ciudadanía</option>
                        <option value="cedula_extranjeria" <?= $usuario['tipo_documento'] == 'cedula_extranjeria' ? 'selected' : '' ?>>Cédula de Extranjería</option>
                        <option value="otro" <?= $usuario['tipo_documento'] == 'otro' ? 'selected' : '' ?>>Pasaporte</option>
                        <option value="tarjeta_identidad" <?= $usuario['tipo_documento'] == 'tarjeta_identidad' ? 'selected' : '' ?>>Tarjeta Identidad</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Documento:</label>
                    <input type="text" class="form-control" name="documento" value="<?= htmlspecialchars($usuario['numero_documento']) ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Teléfono:</label>
                    <input type="text" class="form-control" name="numero" value="<?= htmlspecialchars($usuario['numero_contacto']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tipo de Usuario:</label>
                    <select class="form-select" name="tipo_usuario" required>
                        <option value="">Seleccione tipo</option>
                        <option value="servidor_público" <?= $usuario['tipo_user'] == 'servidor_público' ? 'selected' : '' ?>>Servidor público</option>
                        <option value="contratista" <?= $usuario['tipo_user'] == 'contratista' ? 'selected' : '' ?>>Contratista</option>
                        <option value="trabajador_oficial" <?= $usuario['tipo_user'] == 'trabajador_oficial' ? 'selected' : '' ?>>Trabajador Oficial</option>
                        <option value="visitante_autorizado" <?= $usuario['tipo_user'] == 'visitante_autorizado' ? 'selected' : '' ?>>Visitante Autorizado</option>
                        <option value="aprendiz" <?= $usuario['tipo_user'] == 'aprendiz' ? 'selected' : '' ?>>Aprendiz</option>
                        <option value="instructor" <?= $usuario['tipo_user'] == 'instructor' ? 'selected' : '' ?>>Instructor</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Centro:</label>
                    <select class="form-select" name="edificio" required>
                        <option value="">Seleccione centro</option>
                        <option value="CMD" <?= $usuario['edificio'] == 'CMD' ? 'selected' : '' ?>>Centro de Diseño y Metrología</option>
                        <option value="CGI" <?= $usuario['edificio'] == 'CGI' ? 'selected' : '' ?>>Centro de Gestión Industrial</option>
                        <option value="CENIGRAF" <?= $usuario['edificio'] == 'CENIGRAF' ? 'selected' : '' ?>>Cenigraf</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Guardar cambios</button>
            <a href="dashboard_admin.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="../public/js/scriptsDOM.js"></script>
</body>
</html>
