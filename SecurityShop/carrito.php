<?php
require_once __DIR__.'/conexion.php'; include __DIR__.'/parciales/encabezado.php';
if(!autenticado()){ echo '<div class="contenedor"><p class="suave">Iniciá sesión para ver el carrito.</p></div>'; include __DIR__.'/parciales/pie.php'; exit; }
$cid=$pdo->prepare("SELECT id_carrito FROM carritos WHERE id_usuario=? AND estado='abierto'"); $cid->execute([$_SESSION['id_usuario']]); $cid=$cid->fetchColumn();
$items=[];$total=0;
if($cid){
  $q=$pdo->prepare("SELECT ic.id_producto, ic.cantidad, pr.nombre, pr.precio FROM items_carrito ic JOIN productos pr ON pr.id_producto=ic.id_producto WHERE ic.id_carrito=?");
  $q->execute([$cid]); $items=$q->fetchAll(); 
  for($i=0;$i<count($items);$i++){ $total += $items[$i]['precio']*$items[$i]['cantidad']; }
}
?>
<div class="contenedor"><h1>Carrito</h1>
<?php if(!$items): ?><p class="suave">Tu carrito está vacío.</p>
<?php else: ?>
<table class="tabla"><thead><tr><th>Producto</th><th>Cant.</th><th>Precio</th><th>Subtotal</th></tr></thead><tbody>
<?php foreach($items as $it): ?><tr>
<td><?= htmlspecialchars($it['nombre']) ?></td><td><?= (int)$it['cantidad'] ?></td>
<td>$<?= number_format($it['precio'],0,',','.') ?></td><td>$<?= number_format($it['precio']*$it['cantidad'],0,',','.') ?></td>
</tr><?php endforeach; ?></tbody></table>
<div class="total"><strong>Total:</strong> $<?= number_format($total,0,',','.') ?></div>
<a class="btn" href="<?= URL_BASE ?>/pagar.php">Proceder al pago</a>
<?php endif; ?></div>
<?php include __DIR__.'/parciales/pie.php'; ?>