<?php
require_once __DIR__.'/conexion.php'; if(!autenticado()){ header('Location: '.URL_BASE.'/ingresar.php'); exit; }
$tipo=$_POST['tipo']??'sitio'; $puntuacion=(int)($_POST['puntuacion']??0); $comentario=trim($_POST['comentario']??''); $idp=isset($_POST['id_producto'])?(int)$_POST['id_producto']:null;
if($puntuacion<1||$puntuacion>5||!$comentario) die('Datos inválidos');
$st=$pdo->prepare("INSERT INTO resenas (id_usuario,id_producto,tipo,puntuacion,comentario) VALUES (?,?,?,?,?)");
$st->execute([$_SESSION['id_usuario'],$idp,$tipo,$puntuacion,$comentario]);
header('Location: '.URL_BASE.($tipo==='producto'?'/producto.php':'/inicio.php'));