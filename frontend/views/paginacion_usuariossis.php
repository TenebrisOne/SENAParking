<?php
require_once('../../backend/config/conexion.php');
require_once('../../backend/models/UsuarioSistemaModel.php');

$usuarioModel = new Usuario($conn);
$usuariosPorPagina = 5;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$totalUsuarios = $usuarioModel->contarUsuarios();
$totalPaginas = ceil($totalUsuarios / $usuariosPorPagina);

$paginaAnterior = max(1, $paginaActual - 1);
$paginaSiguiente = min($totalPaginas, $paginaActual + 1);
?>

<div class="paginacion-sencilla" style="font-size: 1rem;">
    <?php if ($paginaActual > 1): ?>
        <a href="?pagina=<?= $paginaAnterior ?>" style="text-decoration: none; color: #007bff;">&lt;</a>
    <?php endif; ?>

    <span class="mx-2 text-muted">Página <?= $paginaActual ?> de <?= $totalPaginas ?></span>

    <?php if ($paginaActual < $totalPaginas): ?>
        <a href="?pagina=<?= $paginaSiguiente ?>" style="text-decoration: none; color: #007bff;">&gt;</a>
    <?php endif; ?>
</div>   

