<?php
require_once __DIR__.'/conexion.php'; if(autenticado()){ header('Location: '.URL_BASE.'/inicio.php'); exit; }
$error=''; if($_SERVER['REQUEST_METHOD']==='POST'){ $nombre=trim($_POST['nombre']??''); $email=trim($_POST['email']??''); $pass=$_POST['clave']??'';
  if(!$nombre||!$email||!$pass) $error='Completá todos los campos';
  else{ $hash=password_hash($pass,PASSWORD_DEFAULT);
    try{ $pdo->prepare("INSERT INTO usuarios (nombre,email,clave_hash) VALUES (?,?,?)")->execute([$nombre,$email,$hash]); header('Location: '.URL_BASE.'/ingresar.php'); exit; }
    catch(Exception $e){ $error='El correo ya está registrado'; }
  }
}
include __DIR__.'/parciales/encabezado.php'; ?>
<div class="contenedor auth"><h1>Registrarse</h1><?php if($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<form method="post"><label>Nombre</label><input name="nombre" required><label>Email</label><input type="email" name="email" required><label>Contraseña</label><input type="password" name="clave" required><button class="btn" type="submit">Crear cuenta</button></form></div>
<?php include __DIR__.'/parciales/pie.php'; ?>