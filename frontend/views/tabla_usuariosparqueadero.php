<?php
require_once('../../backend/config/conexion.php');
require_once('../../backend/models/UsuarioParqueaderoModel.php');

$modelo = new UsuarioParqueadero($conn);
$usuarios = $modelo->obtenerUsuarios();
?>

<table class="table table-striped">
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