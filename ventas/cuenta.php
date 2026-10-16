<?php $titulo = 'Estado de cuenta'; require __DIR__ . '/_nav.php';
$orden = ($_GET['orden'] ?? 'fecha') === 'cliente' ? 'cliente' : 'fecha';
$order = $orden === 'cliente' ? 'c.apellido, c.nombre, v.fecha' : 'v.fecha, c.apellido, c.nombre';
$rows = $pdo->query("SELECT v.id_venta, v.nro_pedido, v.fecha, v.total, c.nombre, c.apellido, c.email
  FROM ventas v JOIN clientes c ON c.id_cliente=v.id_cliente
  WHERE v.estado_cobro='pendiente' ORDER BY $order")->fetchAll();
$suma = array_sum(array_column($rows, 'total'));
?>
<h1>Estado de cuenta — facturas a cobrar</h1>
<p>Ordenar por: <a href="?orden=fecha"><?= $orden === 'fecha' ? '<strong>fecha</strong>' : 'fecha' ?></a> ·
<a href="?orden=cliente"><?= $orden === 'cliente' ? '<strong>cliente</strong>' : 'cliente' ?></a></p>
<table><tr><th>Factura</th><th>Pedido</th><th>Fecha</th><th>Cliente</th><th>Total</th></tr>
<?php foreach ($rows as $r): ?><tr><td>#<?= (int)$r['id_venta'] ?></td><td>#<?= (int)$r['nro_pedido'] ?></td><td><?= h($r['fecha']) ?></td>
<td><?= h($r['apellido'] . ', ' . $r['nombre']) ?><br><span class="muted"><?= h($r['email']) ?></span></td><td><?= money($r['total']) ?></td></tr>
<?php endforeach; if (!$rows): ?><tr><td colspan="5">No hay facturas pendientes de cobro.</td></tr><?php endif; ?>
<tr><th colspan="4">Total a cobrar</th><th><?= money($suma) ?></th></tr></table>
</div></body></html>
