<?php $titulo = 'Productos'; require __DIR__ . '/_nav.php';
$msg = $err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cod = strtoupper(trim($_POST['cod_producto'] ?? ''));
    $desc = trim($_POST['descripcion'] ?? '');
    $precio = str_replace(',', '.', trim($_POST['precio_unitario'] ?? ''));
    $mon = $_POST['moneda'] ?? 'ARS';
    if (!csrf_ok($_POST['csrf'] ?? null)) $err = 'Sesión inválida.';
    elseif ($cod === '' || strlen($cod) > 20 || $desc === '' || strlen($desc) > 255) $err = 'Completá código (máx. 20) y descripción (máx. 255).';
    elseif (!is_numeric($precio) || (float)$precio < 0) $err = 'El precio debe ser un número mayor o igual a 0.';
    elseif (!in_array($mon, ['ARS', 'USD', 'EUR'], true)) $err = 'Moneda inválida.';
    else {
        try {
            $pdo->prepare('INSERT INTO productos (cod_producto, descripcion, precio_unitario, moneda) VALUES (?,?,?,?)')
                ->execute([$cod, $desc, $precio, $mon]);
            $msg = "Producto $cod cargado.";
        } catch (PDOException $e) {
            $err = ($e->errorInfo[1] ?? 0) == 1062 ? 'Ya existe un producto con ese código.' : 'No se pudo guardar.';
        }
    }
}
$lista = $pdo->query('SELECT * FROM productos ORDER BY cod_producto')->fetchAll();
?>
<h1>Productos</h1>
<?php if ($msg): ?><p class="ok"><?= h($msg) ?></p><?php endif; ?><?php if ($err): ?><p class="err"><?= h($err) ?></p><?php endif; ?>
<div class="card"><h3>Cargar producto</h3>
<form method="post"><input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>">
<input name="cod_producto" placeholder="Código" maxlength="20" required>
<input name="descripcion" placeholder="Descripción" maxlength="255" size="40" required>
<input name="precio_unitario" placeholder="Precio unitario" required>
<select name="moneda"><option>ARS</option><option>USD</option><option>EUR</option></select>
<button class="btn">Guardar</button></form></div>
<table><tr><th>Código</th><th>Descripción</th><th>Precio unitario</th></tr>
<?php foreach ($lista as $p): ?><tr><td><?= h($p['cod_producto']) ?></td><td><?= h($p['descripcion']) ?></td>
<td><?= h($p['moneda']) ?> <?= number_format($p['precio_unitario'], 2, ',', '.') ?></td></tr><?php endforeach; ?></table>
</div></body></html>
