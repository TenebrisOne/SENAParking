<?php if (!empty($tabla)): ?>
  <h5><?php echo htmlspecialchars($titulo); ?></h5>
  <div class="table-responsive">
    <table class="table table-sm table-bordered" id="tablaDinamica">
      <thead>
        <tr>
          <?php foreach (array_keys($tabla[0]) as $col): ?>
            <th><?php echo ucfirst(str_replace('_', ' ', $col)); ?></th>
          <?php endforeach; ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($tabla as $row): ?>
          <tr>
            <?php foreach ($row as $val): ?>
              <td><?php echo htmlspecialchars($val); ?></td>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <!-- Botones -->
    <div id="paginacion" class="mt-2">
      <button id="btnAnterior" class="btn btn-sm btn-secondary">Anterior</button>
      <button id="btnSiguiente" class="btn btn-sm btn-secondary">Siguiente</button>
      <span id="infoPagina" class="ms-2"></span>
    </div>
  </div>

  <!-- Script de paginación -->
  <script src="../public/js/nextPrev_tb.js"></script>
<?php else: ?>
  <p class="text-muted">Selecciona una tarjeta para mostrar un reporte.</p>
<?php endif; ?>

