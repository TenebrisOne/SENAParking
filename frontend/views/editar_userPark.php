<?php
session_start();

if (!isset($_SESSION['rol'])) {
    header("location: ../../login.php");
    exit();
}



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
    <link rel="stylesheet" href="../public/css/styles_validaciones.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <div id="header-container"></div>

    <img src="../public/images/logo_sena.png" alt="Logo SENA"
        style="position: absolute; top: 100px; right: 70px; width: 100px;">

    <!-- Flecha de retroceso -->
    <div class="col-2 col-md-1 text-start">
        <div class="back-arrow" onclick="goBack()">
            <i class="fas fa-arrow-left"></i>
            <a class="nav-link" href="../frontend/views/dashboard_admin.php"></a>
        </div>
    </div>

    <div class="register-container">
        <h2>
            <center>Editar Usuario Parqueadero</center>
        </h2><br>
        <form class="formulario" id="formulario">
            <input type="hidden" name="id_userPark" id="id_userPark" value="<?= htmlspecialchars($usuario['id_userPark']) ?>">

            <div class="row mb-3">
                <div class="col-md-6" id="grupo__nombre">
                    <label class="form-label">Nombres:</label>
                    <input type="text" class="form-control formulario__input" name="nombre" id="nombre"
                        placeholder="Ingrese sus nombres" value="<?= htmlspecialchars($usuario['nombresUpark']) ?>" required>
                    <p class="formulario__input-error">El nombre tiene que ser de 4 a 16 dígitos y solo debe contener
                        letras.</p>
                </div>
                <div class="col-md-6" id="grupo__apellido">
                    <label class="form-label">Apellidos:</label>
                    <input type="text" class="form-control formulario__input" name="apellido" id="apellido"
                        placeholder="Ingrese sus apellidos" value="<?= htmlspecialchars($usuario['apellidosUpark']) ?>" required>
                    <p class="formulario__input-error">El apellido tiene que ser de 4 a 16 dígitos y solo puede contener
                        letras.</p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Tipo de documento:</label>
                    <select class="form-select" name="tipdoc" id="tipdoc" required>
                        <option value="">Seleccione tipo de documento</option>
                        <option value="cedula_ciudadania" <?= $usuario['tipoDocumentoUpark'] == 'cedula_ciudadania' ? 'selected' : '' ?>>Cédula de Ciudadanía</option>
                        <option value="cedula_extranjeria" <?= $usuario['tipoDocumentoUpark'] == 'cedula_extranjeria' ? 'selected' : '' ?>>Cédula de Extranjería</option>
                        <option value="otro" <?= $usuario['tipoDocumentoUpark'] == 'otro' ? 'selected' : '' ?>>Pasaporte</option>
                        <option value="tarjeta_identidad" <?= $usuario['tipoDocumentoUpark'] == 'tarjeta_identidad' ? 'selected' : '' ?>>Tarjeta Identidad</option>
                    </select>
                </div>
                <div class="col-md-6" id="grupo__documento">
                    <label class="form-label">Documento:</label>
                    <input type="text" class="form-control formulario__input" name="documento" id="documento"
                        placeholder="Ingrese su documento" value="<?= htmlspecialchars($usuario['numeroDocumentoUpark']) ?>" required>
                    <p class="formulario__input-error">El documento tiene que ser de 6 a 10 dígitos y solo puede
                        contener numeros.</p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6" id="grupo__numero">
                    <label class="form-label">Teléfono:</label>
                    <input type="text" class="form-control formulario__input" name="numero" id="numero"
                        placeholder="Ingrese número de contacto" value="<?= htmlspecialchars($usuario['numeroContactoUpark']) ?>" required>
                    <p class="formulario__input-error">El numero de contacto tiene que ser de 7 a 14 digitos y solo
                        puede contener numeros.</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tipo de Usuario:</label>
                    <select class="form-select" name="tipo_usuario" id="tipo_usuario" required>
                        <option value="">Seleccione tipo</option>
                        <option value="servidor_público" <?= $usuario['tipoUserUpark'] == 'servidor_público' ? 'selected' : '' ?>>Servidor público</option>
                        <option value="contratista" <?= $usuario['tipoUserUpark'] == 'contratista' ? 'selected' : '' ?>>Contratista</option>
                        <option value="trabajador_oficial" <?= $usuario['tipoUserUpark'] == 'trabajador_oficial' ? 'selected' : '' ?>>Trabajador Oficial</option>
                        <option value="visitante_autorizado" <?= $usuario['tipoUserUpark'] == 'visitante_autorizado' ? 'selected' : '' ?>>Visitante Autorizado</option>
                        <option value="aprendiz" <?= $usuario['tipoUserUpark'] == 'aprendiz' ? 'selected' : '' ?>>Aprendiz</option>
                        <option value="instructor" <?= $usuario['tipoUserUpark'] == 'instructor' ? 'selected' : '' ?>>Instructor</option>
                    </select>
                    <p class="formulario__input-error">Ingrese un tipo de usuario</p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6" id="grupo__edificio">
                    <label class="form-label">Centro:</label>
                    <select class="form-select" name="edificio" id="edificio" required>
                        <option value="">Seleccione centro</option>
                        <option value="CMD" <?= $usuario['edificioUpark'] == 'CMD' ? 'selected' : '' ?>>Centro de Diseño y Metrología</option>
                        <option value="CGI" <?= $usuario['edificioUpark'] == 'CGI' ? 'selected' : '' ?>>Centro de Gestión Industrial</option>
                        <option value="CENIGRAF" <?= $usuario['edificioUpark'] == 'CENIGRAF' ? 'selected' : '' ?>>Cenigraf</option>
                    </select>
                    <p class="formulario__input-error">Por favor seleccione un edificio.</p>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Guardar cambios</button>
            <a href="dashboard_admin.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="../public/js/scriptsDOM.js"></script>
    <script src="../public/js/Validacion_edituserpark.js"></script>

    <!-- script para que cuando se cierre la sesion refresque la ventana -->
    <script src="../public/js/ref_cierre.js"></script>
</body>

</html>