<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <meta name="author" content="AdsoDeveloperSolutions801">
    <meta name="course" content="ADSO 2873801">
    <title>Editar Usuarios del Sistema | SENAParking</title>
    <link href="/SENAParking/frontend/public/css/bootstrap.min.css" rel="stylesheet">
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

    <div class="register-container">
        <h2><center>Editar Usuario</h2></center>
        <form action="" class="formulario" id="formulario">
            <input type="hidden" id="id_userSys" name="id_userSys"><br>

            <div class="row mb-3">
                <div class="col-md-6" id="grupo__nombre"> 
                    <label class="form-label">Nombres:</label>
                    <input type="text" class="form-control formulario__input" name="nombre" id="editar-nombre">
                </div>
                <div class="col-md-6" id="grupo__apellido"> 
                    <label class="form-label">Apellidos:</label>
                    <input type="text" class="form-control formulario__input" name="apellido" id="editar-apellido">
                </div>
            </div>

            
            <div class="row mb-3">
                <div class="col-md-6" id="grupo__documento"> 
                    <label class="form-label">Documento:</label>
                    <input type="text" class="form-control formulario__input" name="documento" id="editar-documento">
                </div>
                <div class="col-md-6" id="grupo__correo"> 
                    <label class="form-label">Correo:</label>
                    <input type="text" class="form-control formulario__input" name="correo" id="editar-correo">
                </div>
            </div>

                <div class="row mb-3">
                <div class="col-6" id="grupo__numero"> <!-- Columna de 12 espacios-->
                    <label class="form-label">Télefono:</label>
                    <input type="text" class="form-control formulario__input" name="numero" id="editar-numero">
                </div>
                <div class="col-6" id="grupo__usuario"> <!-- Columna de 12 espacios-->
                    <label class="form-label">Usuario:</label>
                    <input type="text" class="form-control formulario__input" name="usuario" id="editar-usuario">
                </div>
            </div>

            <div class="col-md-6" id="grupo__rol"> <!-- Columna de 12 espacios-->
                <label class="form-label">Rol:</label>
                <select class="form-select" name="rol" id="editar-rol">
                    <option value="1">Administrador</option>
                    <option value="2">Supervisor</option>
                    <option value="3">Guarda de Seguridad</option>
                </select>
            </div>
            
            <br>

        <form action="controllers/ActividadController.php" method="GET">
            <input type="hidden" name="accion" value="editar_userSys">
            <button type="submit" class="btn btn-success">Guardar cambios</button>
        </form>
            <button type="button" class="btn btn-confirmar" onclick="window.location.href='dashboard_admin.html'">Cancelar</button>
        </form>
    </div>

    <a href="controllers/ActividadController.php?accion=editar_userSys">Edito a un usuario del sistema</a>   

    <!-- Cargar el header-->
    <script src="./../public/js/scriptsDOM.js"></script>

    <script src="../public/js/editar_usuario.js"></script>

    <!-- script para que cuando se cierre la sesion refresque la ventana -->
    <script src="../public/js/ref_cierre.js"></script>

</body>

</html>