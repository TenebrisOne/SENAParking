<?php


require_once('../../backend/config/conexion.php');
require_once('../../backend/models/UsuarioSistemaModel.php');

$usuarioModel = new Usuario($conn);
$usuarios = $usuarioModel->obtenerUsuarios();
$roles = ['admin' => "Administrador", 'supervisor' => "Supervisor", 'guardia' => "Guardia de Seguridad"];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=
    , initial-scale=1.0">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- Enlace al archivo de estilos personalizados -->
    <link rel="stylesheet" href="../public/css/styles_dashboard.css">
    <!-- DataTables con Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
</head>

<body>
    <table class="table table-striped" id="usuariosTable">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <?php if ($_SESSION['id_userSys'] != $usuario['id_userSys']): ?>
                    <?php if ($_SESSION['rol'] == 'supervisor'): ?>
                        <?php if ($usuario['rolUsys'] == 'guardia'): ?>
                            <tr>
                                <td><?= htmlspecialchars($usuario['nombresUsys'] . ' ' . $usuario['apellidosUsys']) ?></td>
                                <td>
                                    <?php
                                    $roles = ['admin' => "Administrador", 'supervisor' => "Supervisor", 'guardia' => "Guardia de Seguridad"];
                                    echo $roles[$usuario['rolUsys']] ?? 'Desconocido';
                                    ?>
                                </td>
                                <td>
                                    <form action="../../backend/controllers/UsuarioSistemaController.php" method="POST">
                                        <input type="hidden" name="id_userSys" value="<?= $usuario['id_userSys'] ?>">
                                        <input type="hidden" name="estado" value="<?= $usuario['estadoUsys'] === 'activo' ? 'inactivo' : 'activo' ?>">
                                        <!-- Botón Editar -->
                                        <a href="editar_userSys.php?id=<?= $usuario['id_userSys'] ?>" class="btn btn-editar btn-sm">
                                            Editar
                                        </a>
                                        <label class="switch">
                                            <input type="checkbox" onchange="this.form.submit()" <?= $usuario['estadoUsys'] === 'activo' ? 'checked' : '' ?>>
                                            <span class="slider round"></span>
                                        </label>
                                    </form>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if ($_SESSION['rol'] != 'supervisor'): ?>
                        <tr>
                            <td><?= htmlspecialchars($usuario['nombresUsys'] . ' ' . $usuario['apellidosUsys']) ?></td>
                            <td>
                                <?php
                                $roles = ['admin' => "Administrador", 'supervisor' => "Supervisor", 'guardia' => "Guardia de Seguridad"];
                                echo $roles[$usuario['rolUsys']] ?? 'Desconocido';
                                ?>
                            </td>
                            <td>
                                <form action="../../backend/controllers/UsuarioSistemaController.php" method="POST">
                                    <input type="hidden" name="id_userSys" value="<?= $usuario['id_userSys'] ?>">
                                    <input type="hidden" name="estado" value="<?= $usuario['estadoUsys'] === 'activo' ? 'inactivo' : 'activo' ?>">
                                    <!-- Botón Editar -->
                                    <a href="editar_userSys.php?id=<?= $usuario['id_userSys'] ?>" class="btn btn-editar btn-sm">
                                        Editar
                                    </a>
                                    <label class="switch">
                                        <input type="checkbox" onchange="this.form.submit()" <?= $usuario['estadoUsys'] === 'activo' ? 'checked' : '' ?>>
                                        <span class="slider round"></span>
                                    </label>
                                </form>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- jQuery (requerido por DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $('#usuariosTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            "paging": true,
            "searching": true,
            "info": false,
            "lengthChange": false,
            "pageLength": 5
        });
    </script>
</body>

</html>