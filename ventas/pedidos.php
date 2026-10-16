<?php $titulo = 'Pedidos'; require __DIR__ . '/_nav.php';
$msg = $err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nro = (int)($_POST['pedido'] ?? 0);
    $accion = $_POST['accion'] ?? '';
    if (!csrf_ok($_POST['csrf'] ?? null)) { $err = 'Sesión inválida.'; }
    else {
        try {
            $pdo->beginTransaction();
            $s = $pdo->prepare('SELECT * FROM pedidos WHERE nro_pedido=? AND estado="pendiente" FOR UPDATE');
            $s->execute([$nro]); $p = $s->fetch();
            if (!$p) throw new RuntimeException('El pedido no existe o ya no está pendiente.');
            if ($accion === 'entregar') {
                // 2.4: pasar a las tablas históricas y eliminar de pendientes
                $pdo->prepare('INSERT INTO historico_pedidos (nro_pedido, id_cliente, fecha, fecha_entrega, total) VALUES (?,?,?,NOW(),?)')
                    ->execute([$p['nro_pedido'], $p['id_cliente'], $p['fecha'], $p['total']]);
                $pdo->prepare('INSERT INTO historico_detalle (nro_pedido, cod_producto, cantidad, precio)
                               SELECT nro_pedido, cod_producto, cantidad, precio FROM detalle_pedido WHERE nro_pedido=?')->execute([$nro]);
                $pdo->prepare('DELETE FROM pedidos WHERE nro_pedido=?')->execute([$nro]); // detalle_pedido se borra en cascada
                $msg = "Pedido #$nro entregado y archivado en el histórico.";
            } elseif ($accion === 'anular') {
                $pdo->prepare('UPDATE pedidos SET estado="anulado" WHERE nro_pedido=?')->execute([$nro]);
                $pdo->prepare('UPDATE ventas SET estado_cobro="rechazado" WHERE nro_pedido=? AND estado_cobro="pendiente"')->execute([$nro]);
                $msg = "Pedido #$nro anulado.";
            }
            $pdo->commit();
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $err = $e instanceof RuntimeException ? $e->getMessage() : 'No se pudo completar la operación.';
        }
    }
}
$ver = ($_GET['ver'] ?? 'pendiente') === 'anulado' ? 'anulado' : 'pendiente';
$s = $pdo->prepare('SELECT p.nro_pedido, p.fecha, p.total, p.estado, c.nombre, c.apellido, v.estado_cobro
  FROM pedidos p JOIN clientes c ON c.id_cliente=p.id_cliente LEFT JOIN ventas v ON v.nro_pedido=p.nro_pedido
  WHERE p.estado=? ORDER BY p.fecha');
$s->execute([$ver]); $pedidos = $s->fetchAll();
$det = $pdo->prepare('SELECT d.cod_producto, pr.descripcion, d.cantidad, d.metodo_viaje, d.fecha_viaje, d.extras FROM detalle_pedido d JOIN productos pr ON pr.cod_producto=d.cod_producto WHERE d.nro_pedido=?');
?>
<h1>Pedidos</h1>
<p><a href="?ver=pendiente">Pendientes</a> · <a href="?ver=anulado">Anulados</a></p>
<?php if ($msg): ?><p class="ok"><?= h($msg) ?></p><?php endif; ?><?php if ($err): ?><p class="err"><?= h($err) ?></p><?php endif; ?>
<?php if (!$pedidos): ?><p>No hay pedidos <?= $ver === 'anulado' ? 'anulados' : 'pendientes' ?>.</p><?php endif; ?>
<?php foreach ($pedidos as $p): $det->execute([$p['nro_pedido']]); ?>
<div class="card"><h3>Pedido #<?= (int)$p['nro_pedido'] ?> — <?= h($p['apellido'] . ', ' . $p['nombre']) ?>
<span class="badge <?= h($p['estado']) ?>"><?= h($p['estado']) ?></span> <span class="badge <?= h($p['estado_cobro']) ?>">cobro: <?= h($p['estado_cobro']) ?></span></h3>
<p class="muted"><?= h($p['fecha']) ?> · Total <?= money($p['total']) ?></p>
<ul><?php foreach ($det->fetchAll() as $l): ?><?php $op = [];
  if (!empty($l['metodo_viaje']) && isset(METODOS_VIAJE[$l['metodo_viaje']])) $op[] = METODOS_VIAJE[$l['metodo_viaje']]['nombre'];
  if (!empty($l['fecha_viaje'])) $op[] = 'Salida: ' . date('d/m/Y', strtotime($l['fecha_viaje']));
  if (!empty($l['extras'])) $op[] = 'Extras: ' . $l['extras']; ?>
<li><?= (int)$l['cantidad'] ?> pers. × <?= h($l['cod_producto']) ?> — <?= h($l['descripcion']) ?><?php if ($op): ?><div class="opciones"><?= h(implode(' · ', $op)) ?></div><?php endif; ?></li><?php endforeach; ?></ul>
<?php if ($p['estado'] === 'pendiente'): ?>
<form method="post" style="display:inline"><input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>"><input type="hidden" name="pedido" value="<?= (int)$p['nro_pedido'] ?>">
<button class="btn green" name="accion" value="entregar" onclick="return confirm('¿Registrar la entrega del pedido?')">Entregar</button>
<button class="btn red" name="accion" value="anular" onclick="return confirm('¿Anular el pedido?')">Anular</button></form>
<?php endif; ?></div>
<?php endforeach; ?>
</div></body></html>
