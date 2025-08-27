<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Pedidos</h3>
  <a href="index.php?c=pedido&a=registrar" class="btn btn-success">Nuevo pedido</a>
</div>

<div class="card">
  <div class="table-responsive">
    <table class="table table-sm table-hover mb-0">
      <thead class="table-light">
        <tr>
          <th>ID</th><th>Fecha</th><th>Cliente</th><th>Local salida</th>
          <th>Canal</th><th>Estado</th><th class="text-end">Total Neto</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($data as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['id_pedido']) ?></td>
          <td><?= htmlspecialchars($r['fecha_pedido']) ?></td>
          <td><?= htmlspecialchars($r['cliente']) ?></td>
          <td><?= htmlspecialchars($r['local_salida']) ?></td>
          <td><?= htmlspecialchars($r['canal_venta']) ?></td>
          <td><span class="badge bg-secondary"><?= htmlspecialchars($r['estado']) ?></span></td>
          <td class="text-end">S/ <?= number_format((float)$r['total_neto'], 2) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>
