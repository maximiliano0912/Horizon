<?php $titulo = 'Panel'; require __DIR__ . '/_nav.php';
$pend = (int)$pdo->query('SELECT COUNT(*) FROM pedidos WHERE estado="pendiente"')->fetchColumn();
$cobrar = (float)$pdo->query('SELECT COALESCE(SUM(total),0) FROM ventas WHERE estado_cobro="pendiente"')->fetchColumn();
$prod = (int)$pdo->query('SELECT COUNT(*) FROM productos')->fetchColumn();
?>
<h1>Panel de ventas</h1>
<div class="grid">
  <div class="card"><div class="muted">Pedidos pendientes de entrega</div><h2><?= $pend ?></h2><a class="btn" href="pedidos.php">Ver pedidos</a></div>
  <div class="card"><div class="muted">Total a cobrar</div><h2><?= money($cobrar) ?></h2><a class="btn" href="cuenta.php">Estado de cuenta</a></div>
  <div class="card"><div class="muted">Productos cargados</div><h2><?= $prod ?></h2><a class="btn" href="productos.php">Ver / cargar</a></div>
</div></div></body></html>
