<?php
// Cabecera común de las páginas "de panel" (pago, mis pedidos, productos, ventas):
// mismo diseño del sitio, logo y usuario visible.

function panel_head(string $titulo, string $base = ''): void { ?>
<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= h($titulo) ?> | HORIZON</title>
<link rel="icon" href="<?= $base ?>img/Logo2.png">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<link rel="stylesheet" href="<?= $base ?>css/panel.css"></head>
<body>
<?php }

// Barra superior para el cliente
function panel_top_cliente(): void { ?>
<div class="top">
  <a href="index.php" class="logo-link"><img src="img/Logo2.png" alt="HORIZON" class="logo-img"></a>
  <a href="index.php">Inicio</a><a href="catalogo.php">Paquetes</a><a href="productos.php">Productos</a><a href="carrito.php"><i class="fa-solid fa-cart-shopping"></i> Carrito</a>
  <span class="sp"></span>
  <?php if (isset($_SESSION['id_cliente'])): ?>
    <a href="mis_pedidos.php">Mis pedidos</a>
    <span class="user"><i class="fa-regular fa-circle-user"></i> <?= h($_SESSION['usuario'] ?? $_SESSION['nombre'] ?? '') ?></span>
    <a href="logout.php">Cerrar sesión</a>
  <?php else: ?>
    <a href="login.php">Iniciar sesión</a><a href="registro.php">Crear cuenta</a>
  <?php endif; ?>
</div>
<?php }
