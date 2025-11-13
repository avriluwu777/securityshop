<?php
require_once __DIR__.'/conexion.php'; if(autenticado()){ header('Location: '.URL_BASE.'/inicio.php'); exit; }
$error=''; if($_SERVER['REQUEST_METHOD']==='POST'){ $email=trim($_POST['email']??''); $pass=$_POST['clave']??'';
  $st=$pdo->prepare("SELECT id_usuario,nombre,clave_hash FROM usuarios WHERE email=?"); $st->execute([$email]); $u=$st->fetch();
  if($u && password_verify($pass,$u['clave_hash'])){ $_SESSION['id_usuario']=$u['id_usuario']; $_SESSION['nombre_usuario']=$u['nombre']; header('Location: '.URL_BASE.'/inicio.php'); exit; }
  else $error='Correo o contraseña incorrectos';
}
include __DIR__.'/parciales/encabezado.php'; ?>
<div class="contenedor auth"><h1>Ingresar</h1><?php if($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<form method="post"><label>Email</label><input type="email" name="email" required><label>Contraseña</label><input type="password" name="clave" required><button class="btn" type="submit">Entrar</button></form></div>
<?php include __DIR__.'/parciales/pie.php'; ?>