<?php
require_once('../../backend/config/conexion.php');
require_once('../../backend/models/UsuarioSistemaModel.php');

$usuarioModel = new Usuario($conn);
$usuarios = $usuarioModel->obtenerUsuarios();
$roles = [1 => "Administrador", 2 => "Supervisor", 3 => "Guardia de Seguridad"];
?>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Usuario</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= htmlspecialchars($usuario['username']) ?></td>
                <td>
                    <?php
                    $roles = [1 => "Administrador", 2 => "Supervisor", 3 => "Guardia de Seguridad"];
                    echo $roles[$usuario['id_rol']] ?? 'Desconocido';
                    ?>
                </td>
                <td>
                    <form action="../../backend/controllers/UsuarioSistemaController.php" method="POST">
                        <input type="hidden" name="id_userSys" value="<?= $usuario['id_userSys'] ?>">
                        <input type="hidden" name="estado" value="<?= $usuario['estado'] === 'activo' ? 'inactivo' : 'activo' ?>">
                        <label class="switch">
                            <input type="checkbox" onchange="this.form.submit()" <?= $usuario['estado'] === 'activo' ? 'checked' : '' ?>>
                            <span class="slider round"></span>
                        </label>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>