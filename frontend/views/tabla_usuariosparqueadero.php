<?php
require_once('../../backend/config/conexion.php');
require_once('../../backend/models/UsuarioParqueaderoModel.php');

$usuarioPark = new UsuarioParqueadero($conn);
$usuarios = $usuarioPark->obtenerUsuarios();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=
    , initial-scale=1.0">
    <title>Document</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- Enlace al archivo de estilos personalizados -->
    <link rel="stylesheet" href="../public/css/styles_dashboard.css">
    <!-- DataTables con Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
</head>

<body>
    <table class="table table-striped" id="usuariosparkTable">
        <thead>
            <tr>
                <th>Nombre Completo</th>
                <th>Documento</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= htmlspecialchars($usuario['nombres_park'] . ' ' . $usuario['apellidos_park']) ?></td>
                    <td><?= htmlspecialchars($usuario['numero_documento']) ?></td>
                    <td>
                        <form action="../../backend/controllers/UsuarioParqueaderoController.php" method="POST">
                            <input type="hidden" name="id" value="<?= $usuario['id_userPark'] ?>">
                            <input type="hidden" name="estado" value="<?= $usuario['estado'] === 'activo' ? 'inactivo' : 'activo' ?>">
                            <!-- Botón Editar -->
                            <a href="editar_userPark.php?id=<?= $usuario['id_userPark'] ?>" class="btn btn-editar btn-sm">
                                Editar
                            </a>
                            <?php if ($_SESSION['rol'] != 3): ?>
                            <label class="switch">
                                <input type="checkbox" onchange="this.form.submit()" <?= $usuario['estado'] === 'activo' ? 'checked' : '' ?>>
                                <span class="slider round"></span>
                            </label>
                            <?php endif; ?>
                        </form>

                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <!-- jQuery (requerido por DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $('#usuariosparkTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            "paging": true,
            "searching": true,
            "info": false,
            "lengthChange": false,
            "pageLength": 4
        });
    </script>
</body>