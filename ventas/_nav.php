<?php
require_once __DIR__ . '/../config/helpers.php';
requiere_staff();
panel_head($titulo ?? 'Ventas', '../');
?>
<div class="top">
  <a href="panel.php" class="logo-link"><img src="../img/Logo2.png" alt="HORIZON" class="logo-img"></a><span class="tag">Ventas</span>
  <a href="panel.php">Panel</a><a href="productos.php">Productos</a><a href="pedidos.php">Pedidos</a><a href="cuenta.php">Estado de cuenta</a>
  <span class="sp"></span>
  <span class="user"><i class="fa-regular fa-circle-user"></i> <?= h($_SESSION['nombre']) ?> (<?= h($_SESSION['rol']) ?>)</span>
  <a href="../logout.php">Salir</a>
</div>
<div class="wrap">
<?php if (($titulo ?? '') === 'Panel'): ?>
  <a class="back-link" href="../index.php"><i class="fa-solid fa-arrow-left"></i> Volver al sitio</a>
<?php else: ?>
  <a class="back-link" href="panel.php"><i class="fa-solid fa-arrow-left"></i> Volver al panel</a>
<?php endif; ?>
