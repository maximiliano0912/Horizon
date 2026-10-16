<?php
// Cliente: ver, modificar y eliminar sus pedidos pendientes
require __DIR__ . '/config/helpers.php';
requiere_cliente();
$idc = (int)$_SESSION['id_cliente'];
$msg = ''; $err = '';

function pedido_propio(PDO $pdo, int $nro, int $idc) {
    $s = $pdo->prepare('SELECT p.nro_pedido, v.estado_cobro FROM pedidos p
                        JOIN ventas v ON v.nro_pedido = p.nro_pedido
                        WHERE p.nro_pedido=? AND p.id_cliente=? AND p.estado="pendiente"');
    $s->execute([$nro, $idc]);
    return $s->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nro = (int)($_POST['pedido'] ?? 0);
    $p = csrf_ok($_POST['csrf'] ?? null) ? pedido_propio($pdo, $nro, $idc) : null;
    if (!$p) {
        $err = 'Pedido no válido.';
    } elseif ($p['estado_cobro'] === 'cobrado') {
        $err = 'Ese pedido ya está pagado. Para cambiarlo, comunicate con ventas.';
    } else {
        try {
            $pdo->beginTransaction();
            if (($_POST['accion'] ?? '') === 'eliminar') {
                $pdo->prepare('DELETE FROM ventas WHERE nro_pedido=? AND estado_cobro<>"cobrado"')->execute([$nro]);
                $pdo->prepare('DELETE FROM pedidos WHERE nro_pedido=?')->execute([$nro]); // borra el detalle en cascada
                $msg = "Pedido #$nro eliminado.";
            } elseif (($_POST['accion'] ?? '') === 'actualizar') {
                $upd = $pdo->prepare('UPDATE detalle_pedido SET cantidad=? WHERE nro_pedido=? AND cod_producto=?');
                $del = $pdo->prepare('DELETE FROM detalle_pedido WHERE nro_pedido=? AND cod_producto=?');
                foreach (($_POST['cant'] ?? []) as $cod => $q) {
                    $q = (int)$q;
                    if ($q <= 0) $del->execute([$nro, $cod]); else $upd->execute([min($q, MAX_PERSONAS), $nro, $cod]);
                }
                $t = $pdo->prepare('SELECT COALESCE(SUM(cantidad*precio),0) FROM detalle_pedido WHERE nro_pedido=?');
                $t->execute([$nro]); $total = (float)$t->fetchColumn();
                if ($total <= 0) {
                    $pdo->prepare('DELETE FROM ventas WHERE nro_pedido=?')->execute([$nro]);
                    $pdo->prepare('DELETE FROM pedidos WHERE nro_pedido=?')->execute([$nro]);
                    $msg = "Pedido #$nro eliminado (quedó sin productos).";
                } else {
                    $pdo->prepare('UPDATE pedidos SET total=? WHERE nro_pedido=?')->execute([$total, $nro]);
                    $pdo->prepare('UPDATE ventas SET total=? WHERE nro_pedido=?')->execute([$total, $nro]);
                    $msg = "Pedido #$nro actualizado.";
                }
            }
            $pdo->commit();
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $err = 'No se pudo completar la operación.';
        }
    }
}

$s = $pdo->prepare('SELECT p.nro_pedido, p.fecha, p.total, v.estado_cobro FROM pedidos p
                    JOIN ventas v ON v.nro_pedido=p.nro_pedido
                    WHERE p.id_cliente=? AND p.estado="pendiente" ORDER BY p.fecha DESC');
$s->execute([$idc]);
$pedidos = $s->fetchAll();
$det = $pdo->prepare('SELECT d.cod_producto, pr.descripcion, d.cantidad, d.precio, d.metodo_viaje, d.fecha_viaje, d.extras FROM detalle_pedido d
                      JOIN productos pr ON pr.cod_producto=d.cod_producto WHERE d.nro_pedido=?');
?>
<?php panel_head('Mis pedidos'); ?>
<?php panel_top_cliente(); ?>
<div class="wrap">
<a class="back-link" href="index.php"><i class="fa-solid fa-arrow-left"></i> Volver al inicio</a>
<h1>Mis pedidos pendientes de entrega</h1>
<?php if ($msg): ?><p class="ok"><?= h($msg) ?></p><?php endif; ?>
<?php if ($err): ?><p class="err"><?= h($err) ?></p><?php endif; ?>
<?php if (!$pedidos): ?><p>No tenés pedidos pendientes. <a href="catalogo.php" style="color:var(--primary-light)">Ver paquetes</a></p><?php endif; ?>
<?php foreach ($pedidos as $p): $det->execute([$p['nro_pedido']]); $lineas = $det->fetchAll(); $pagado = $p['estado_cobro'] === 'cobrado'; ?>
<div class="card">
  <h3>Pedido #<?= (int)$p['nro_pedido'] ?> <span class="badge <?= h($p['estado_cobro']) ?>"><?= $pagado ? 'pagado' : 'pago pendiente' ?></span></h3>
  <p class="muted"><?= h($p['fecha']) ?></p>
  <form method="post">
    <input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>">
    <input type="hidden" name="pedido" value="<?= (int)$p['nro_pedido'] ?>">
    <table><tr><th>Código</th><th>Paquete</th><th>Personas</th><th>Precio p/p</th></tr>
    <?php foreach ($lineas as $l):
      $op = [];
      if (!empty($l['metodo_viaje']) && isset(METODOS_VIAJE[$l['metodo_viaje']])) $op[] = METODOS_VIAJE[$l['metodo_viaje']]['nombre'];
      if (!empty($l['fecha_viaje'])) $op[] = 'Salida: ' . date('d/m/Y', strtotime($l['fecha_viaje']));
      if (!empty($l['extras'])) $op[] = 'Extras: ' . $l['extras']; ?>
      <tr><td><?= h($l['cod_producto']) ?></td><td><?= h($l['descripcion']) ?><div class="opciones"><?= h(implode(' · ', $op)) ?></div></td>
      <td><?php if ($pagado): echo (int)$l['cantidad']; else: ?><input type="number" min="0" max="<?= (int)MAX_PERSONAS ?>" name="cant[<?= h($l['cod_producto']) ?>]" value="<?= (int)$l['cantidad'] ?>"><?php endif; ?></td>
      <td><?= money($l['precio']) ?></td></tr>
    <?php endforeach; ?></table>
    <p><strong>Total: <?= money($p['total']) ?></strong></p>
    <?php if (!$pagado): ?>
      <button class="btn" name="accion" value="actualizar">Guardar cambios</button>
      <a class="btn green" href="pagar.php?pedido=<?= (int)$p['nro_pedido'] ?>">Pagar</a>
      <button class="btn red" name="accion" value="eliminar" onclick="return confirm('¿Eliminar este pedido?')">Eliminar pedido</button>
      <p class="muted">Poné 0 personas para quitar un paquete.</p>
    <?php endif; ?>
  </form>
</div>
<?php endforeach; ?>
</div></body></html>
