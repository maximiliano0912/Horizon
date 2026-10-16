<?php
// Cliente: lista de productos (formato lista, sin imágenes) con botón para agregar al carrito
require __DIR__ . '/config/helpers.php';
$productos = $pdo->query('SELECT cod_producto, descripcion, precio_unitario, moneda FROM productos ORDER BY cod_producto')->fetchAll();
?>
<?php panel_head('Productos'); ?>
<?php panel_top_cliente(); ?>
<div class="wrap">
<a class="back-link" href="index.php"><i class="fa-solid fa-arrow-left"></i> Volver al inicio</a>
<h1>Lista de productos</h1>
<table><tr><th>Código</th><th>Descripción</th><th>Precio</th><th></th></tr>
<?php foreach ($productos as $p):
  $pre = ($p['moneda'] === 'ARS' ? '$' : $p['moneda'] . ' ') . number_format($p['precio_unitario'], 0, ',', '.'); ?>
<tr><td><?= h($p['cod_producto']) ?></td><td><?= h($p['descripcion']) ?></td><td><?= h($pre) ?></td>
<td><button class="btn" data-cod="<?= h($p['cod_producto']) ?>" data-nom="<?= h($p['descripcion']) ?>" data-pre="<?= h($p['precio_unitario']) ?>" data-txt="<?= h($pre) ?>">Agregar</button></td></tr>
<?php endforeach; ?></table></div>
<script src="js/carrito.js"></script>
<script>
document.querySelectorAll('button[data-cod]').forEach(b => b.addEventListener('click', () => {
  agregarAlCarrito({codigo: b.dataset.cod, nombre: b.dataset.nom, ubicacion: '', descripcion: '',
    precio: parseFloat(b.dataset.pre), precioTexto: b.dataset.txt, detalles: []}, null);
}));
</script></body></html>
