

<style>
  /* ======== Estilos optimizados para tabla compacta ======== */

  .table-responsive {
    margin-top: 10px;
  }

  #tablaDinamica {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
    table-layout: auto; /* deja que las columnas se ajusten al contenido */
  }

  #tablaDinamica th,
  #tablaDinamica td {
    padding: 6px 8px; /* espacio interno mínimo */
    text-align: left;
    vertical-align: middle;
    border: 1px solid #dee2e6;
    white-space: nowrap; /* evita saltos de línea */
  }

  /* Encabezado */
  #tablaDinamica th {
    background-color: #f8f9fa;
    font-weight: 600;
  }

  /* Alternancia de color en filas */
  #tablaDinamica tr:nth-child(even) td {
    background-color: #fafafa;
  }

  #tablaDinamica tbody tr:hover td {
    background-color: #eef6ff;
  }

  /* Ajuste de ancho automático según contenido */
  #tablaDinamica td:nth-child(1) { width: 25%; }  /* Usuario */
  #tablaDinamica td:nth-child(2) { width: 20%; }  /* Vehículo */
  #tablaDinamica td:nth-child(3) { width: 25%; }  /* Fecha */
  #tablaDinamica td:nth-child(4) { width: 15%; }  /* Tipo acción */

  /* Paginación */
  #paginacion {
    display: flex;
    align-items: center;
    justify-content: start;
    gap: 8px;
    margin-top: 10px;
  }

  #paginacion button {
    padding: 4px 10px;
    font-size: 13px;
    border-radius: 5px;
  }

  #infoPagina {
    font-size: 13px;
    color: #555;
  }
</style>

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

