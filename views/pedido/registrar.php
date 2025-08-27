<?php include __DIR__ . '/../layout/header.php'; ?>
<h3 class="mb-3">Nuevo pedido</h3>

<form method="post" action="index.php?c=pedido&a=registrar" id="frmPedido">
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Cliente</label>
      <select name="id_cliente" class="form-select" required>
        <option value="">-- Seleccione --</option>
        <?php foreach ($clientes as $c): ?>
          <option value="<?= $c['id_cliente'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label">Local salida</label>
      <select name="id_local_salida" class="form-select" required>
        <?php foreach ($locales as $l): ?>
          <option value="<?= $l['id_local'] ?>"><?= htmlspecialchars($l['nombre']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label">Canal</label>
      <select name="canal_venta" class="form-select" required>
        <option value="TELEFONO">Teléfono</option>
        <option value="PRESENCIAL">Presencial</option>
      </select>
    </div>

    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <span>Detalle</span>
          <button type="button" class="btn btn-sm btn-primary" id="btnAdd">Agregar ítem</button>
        </div>
        <div class="table-responsive">
          <table class="table table-sm mb-0" id="tbDetalle">
            <thead class="table-light">
              <tr>
                <th style="min-width:260px">Producto</th>
                <th class="text-end">Cantidad</th>
                <th class="text-end">P. Unit</th>
                <th class="text-end">Desc.</th>
                <th class="text-end">Subtotal</th>
                <th></th>
              </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
              <tr>
                <th colspan="4" class="text-end">Total Bruto</th>
                <th class="text-end"><span id="spBruto">0.00</span></th>
                <th></th>
              </tr>
              <tr>
                <th colspan="4" class="text-end">Total Descuento</th>
                <th class="text-end"><span id="spDesc">0.00</span></th>
                <th></th>
              </tr>
              <tr>
                <th colspan="4" class="text-end">Total Neto</th>
                <th class="text-end"><span id="spNeto">0.00</span></th>
                <th></th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>

    <div class="col-12">
      <label class="form-label">Observación</label>
      <input type="text" name="observacion" class="form-control">
    </div>

    <input type="hidden" name="items_json" id="items_json">
    <input type="hidden" name="total_bruto" id="total_bruto" value="0">
    <input type="hidden" name="total_descuento" id="total_descuento" value="0">
    <input type="hidden" name="total_neto" id="total_neto" value="0">

    <div class="col-12 d-flex gap-2">
      <button class="btn btn-success" type="submit">Guardar</button>
      <a class="btn btn-outline-secondary" href="index.php?c=pedido&a=index">Cancelar</a>
    </div>
  </div>
</form>

<script>
const productos = <?= json_encode($productos, JSON_UNESCAPED_UNICODE) ?>;
const tbBody = document.querySelector('#tbDetalle tbody');
const btnAdd = document.getElementById('btnAdd');

function fmt(n){ return (Number(n)||0).toFixed(2); }
function recompute(){
  let bruto=0, desc=0, neto=0, items=[];
  document.querySelectorAll('#tbDetalle tbody tr').forEach(tr=>{
    const idp = tr.querySelector('.id_producto').value;
    const qty = parseFloat(tr.querySelector('.cantidad').value||0);
    const pu  = parseFloat(tr.querySelector('.precio_unit').value||0);
    const ds  = parseFloat(tr.querySelector('.descuento').value||0);
    const st  = (qty*pu);
    const stn = st - ds;
    tr.querySelector('.subtotal').textContent = fmt(stn);
    bruto += st; desc += ds; neto += stn;
    items.push({id_producto: +idp, cantidad: qty, precio_unit: pu, descuento: ds, subtotal: stn});
  });
  document.getElementById('spBruto').textContent = fmt(bruto);
  document.getElementById('spDesc').textContent  = fmt(desc);
  document.getElementById('spNeto').textContent  = fmt(neto);
  document.getElementById('total_bruto').value = fmt(bruto);
  document.getElementById('total_descuento').value = fmt(desc);
  document.getElementById('total_neto').value = fmt(neto);
  document.getElementById('items_json').value = JSON.stringify(items);
}

btnAdd.addEventListener('click', ()=>{
  const tr = document.createElement('tr');
  const opts = productos.map(p=>`<option value="${p.id_producto}" data-precio="${p.precio_mayorista}">
    [${p.sku}] ${p.nombre}</option>`).join('');
  tr.innerHTML = `
    <td><select class="form-select id_producto">${opts}</select></td>
    <td><input type="number" min="0" step="0.01" class="form-control text-end cantidad" value="1"></td>
    <td><input type="number" min="0" step="0.01" class="form-control text-end precio_unit" value="0"></td>
    <td><input type="number" min="0" step="0.01" class="form-control text-end descuento" value="0"></td>
    <td class="text-end subtotal">0.00</td>
    <td><button type="button" class="btn btn-sm btn-outline-danger btnDel">&times;</button></td>`;
  tbBody.appendChild(tr);
  const sel = tr.querySelector('.id_producto');
  const pSel = productos.find(p=>p.id_producto==sel.value);
  tr.querySelector('.precio_unit').value = pSel ? pSel.precio_mayorista : 0;
  tr.addEventListener('input', recompute);
  tr.querySelector('.btnDel').addEventListener('click', ()=>{ tr.remove(); recompute(); });
  recompute();
});

document.getElementById('frmPedido').addEventListener('submit', (e)=>{
  if (tbBody.children.length===0) {
    e.preventDefault();
    alert('Agrega al menos un ítem.');
  }
});
</script>
<?php include __DIR__ . '/../layout/footer.php'; ?>
