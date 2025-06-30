<?php if (!empty($tabla)): ?>
<h5><?php echo htmlspecialchars($titulo); ?></h5>
<div class="table-responsive">
  <table class="table table-sm table-bordered">
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
</div>
<?php else: ?>
<p class="text-muted">Selecciona una tarjeta para mostrar un reporte.</p>
<?php endif; ?>
