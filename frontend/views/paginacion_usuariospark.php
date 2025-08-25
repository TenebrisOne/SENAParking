<?php
require_once('../../backend/config/conexion.php');
require_once('../../backend/models/UsuarioParqueaderoModel.php');

$usuarioParqModel = new UsuarioParqueadero($conn);

// Configuración de paginación para parqueadero
$usuariosParqPorPagina = 5;
$paginaParqActual = isset($_GET['pagina_parq']) ? (int)$_GET['pagina_parq'] : 1;
$totalUsuariosParq = $usuarioParqModel->contarUsuarios();
$totalPaginasParq = ceil($totalUsuariosParq / $usuariosParqPorPagina);

$paginaAnterior = max(1, $paginaParqActual - 1);
$paginaSiguiente = min($totalPaginasParq, $paginaParqActual + 1);
?>

<div class="paginacion-sencilla" style="font-size: 1rem;">
    <?php if ($paginaParqActual > 1): ?>
        <a href="?pagina_parq=<?= $paginaAnterior ?>" style="text-decoration: none; color: #007bff;">&lt;</a>
    <?php endif; ?>
    <span class="mx-2 text-muted">Página <?= $paginaParqActual ?> de <?= $totalPaginasParq ?></span>
    <?php if ($paginaParqActual < $totalPaginasParq): ?>
        <a href="?pagina_parq=<?= $paginaSiguiente ?>" style="text-decoration: none; color: #007bff;">&gt;</a>
    <?php endif; ?>
</div>

