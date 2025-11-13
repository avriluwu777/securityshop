<?php require_once __DIR__.'/conexion.php'; include __DIR__.'/parciales/encabezado.php'; $id=(int)($_GET['id_pedido']??0); ?>
<div class="contenedor"><h1>Pago con éxito</h1><p class="suave">Gracias por tu compra. Nº de pedido: <?= $id ?></p>
<a class="btn" href="<?= URL_BASE ?>/pedidos.php">Ver mis pedidos</a></div>
<?php include __DIR__.'/parciales/pie.php'; ?>