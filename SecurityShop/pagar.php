<?php
require_once __DIR__.'/conexion.php';
if(!autenticado()){ header('Location: '.URL_BASE.'/ingresar.php'); exit; }
$cid=$pdo->prepare("SELECT id_carrito FROM carritos WHERE id_usuario=? AND estado='abierto'"); $cid->execute([$_SESSION['id_usuario']]); $cid=$cid->fetchColumn();
if(!$cid){ header('Location: '.URL_BASE.'/carrito.php'); exit; }
$its=$pdo->prepare("SELECT ic.id_producto, ic.cantidad, pr.precio FROM items_carrito ic JOIN productos pr ON pr.id_producto=ic.id_producto WHERE ic.id_carrito=?");
$its->execute([$cid]); $its=$its->fetchAll(); $total=0; foreach($its as $i){ $total += $i['precio']*$i['cantidad']; }
if($_SERVER['REQUEST_METHOD']==='POST'){
  foreach(['tarjeta','dni','email','nombre','direccion'] as $r){ if(empty($_POST[$r])) die('Faltan campos'); }
  $pdo->beginTransaction();
  $pdo->prepare("INSERT INTO pedidos (id_usuario,total,estado) VALUES (?,?, 'pagado')")->execute([$_SESSION['id_usuario'],$total]);
  $idp=$pdo->lastInsertId();
  $ins=$pdo->prepare("INSERT INTO items_pedido (id_pedido,id_producto,cantidad,precio) VALUES (?,?,?,?)");
  foreach($its as $i){ $ins->execute([$idp,$i['id_producto'],$i['cantidad'],$i['precio']]); }
  $pdo->prepare("UPDATE carritos SET estado='cerrado' WHERE id_carrito=?")->execute([$cid]);
  $pdo->commit();
  header('Location: '.URL_BASE.'/exito.php?id_pedido='.$idp); exit;
}
include __DIR__.'/parciales/encabezado.php';
?>
<div class="contenedor"><h1>Pago</h1>
<div class="pago"><div><h3>Resumen</h3><ul><?php foreach($its as $i): ?><li><?= (int)$i['cantidad'] ?> x $<?= number_format($i['precio'],0,',','.') ?></li><?php endforeach; ?></ul>
<div class="total"><strong>Total:</strong> $<?= number_format($total,0,',','.') ?></div></div>
<form method="post" class="form-pago" onsubmit="return validarPago(this)">
<label>Tarjeta</label><input name="tarjeta" maxlength="19" placeholder="4111 1111 1111 1111" required>
<label>DNI</label><input name="dni" maxlength="12" required>
<label>Email</label><input type="email" name="email" required>
<label>Nombre y Apellido</label><input name="nombre" required>
<label>Dirección</label><input name="direccion" required>
<button class="btn" type="submit">Pagar</button>
</form></div></div>
<?php include __DIR__.'/parciales/pie.php'; ?>