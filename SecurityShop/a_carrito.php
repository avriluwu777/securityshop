<?php
require_once __DIR__.'/conexion.php';
if(!autenticado()){ header('Location: '.URL_BASE.'/ingresar.php'); exit; }
if($_SERVER['REQUEST_METHOD']==='POST'){
  $idp=(int)($_POST['id_producto']??0); $cant=max(1,(int)($_POST['cantidad']??1));
  $pdo->prepare("INSERT IGNORE INTO carritos (id_usuario, estado) VALUES (?, 'abierto')")->execute([$_SESSION['id_usuario']]);
  $cid=$pdo->prepare("SELECT id_carrito FROM carritos WHERE id_usuario=? AND estado='abierto'"); $cid->execute([$_SESSION['id_usuario']]); $cid=$cid->fetchColumn();
  $st=$pdo->prepare("INSERT INTO items_carrito (id_carrito,id_producto,cantidad) VALUES (?,?,?)
                     ON DUPLICATE KEY UPDATE cantidad=cantidad+VALUES(cantidad)");
  $st->execute([$cid,$idp,$cant]);
}
header('Location: '.URL_BASE.'/carrito.php');