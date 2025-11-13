<?php require_once __DIR__.'/../conexion.php'; ?>
<!doctype html><html lang="es"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>SecurityShop</title>
<link rel="stylesheet" href="<?= URL_BASE ?>/estilos/estilo.css?v=1.0">
<script defer src="<?= URL_BASE ?>/scripts/app.js"></script>
</head><body>
<header class="cabecera"><div class="contenedor">
  <a class="marca" href="<?= URL_BASE ?>/inicio.php">Security<span>Shop</span></a>
  <nav class="menu">
    <a href="<?= URL_BASE ?>/inicio.php">Inicio</a>
    <a href="<?= URL_BASE ?>/producto.php">Producto</a>
    <a href="<?= URL_BASE ?>/servicios.php">Servicios</a>
    <?php if (autenticado()): ?><a href="<?= URL_BASE ?>/pedidos.php">Pedidos</a><?php endif; ?>
  </nav>
  <div class="acciones">
    <a class="btn btn-borde" href="<?= URL_BASE ?>/carrito.php">Carrito</a>
    <?php if (autenticado()): ?>
      <span class="hola">¡Hola, <?= nombre_usuario(); ?>!</span>
      <a class="btn" href="<?= URL_BASE ?>/salir.php">Salir</a>
    <?php else: ?>
      <a class="btn" href="<?= URL_BASE ?>/ingresar.php">Ingresar</a>
      <a class="btn btn-borde" href="<?= URL_BASE ?>/registrarse.php">Registrarse</a>
    <?php endif; ?>
  </div>
</div></header>
<main class="principal">
