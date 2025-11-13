<?php
require_once __DIR__.'/conexion.php'; include __DIR__.'/parciales/encabezado.php';
$modelos=$pdo->query("SELECT id_producto, modelo FROM productos")->fetchAll(); $ok=false;
if($_SERVER['REQUEST_METHOD']==='POST'){ if(!autenticado()){ header('Location: '.URL_BASE.'/ingresar.php'); exit; }
  $tipo=$_POST['tipo_servicio']??''; $modelo=(int)($_POST['id_modelo']??0); $nombre=trim($_POST['nombre_apellido']??''); $tel=trim($_POST['telefono']??''); $dir=trim($_POST['direccion']??'');
  if($tipo&&$modelo&&$nombre&&$tel&&$dir){ $pdo->prepare("INSERT INTO ordenes_servicio (id_usuario,tipo_servicio,id_producto,nombre_apellido,telefono,direccion) VALUES (?,?,?,?,?,?)")
      ->execute([$_SESSION['id_usuario']??None,$tipo,$modelo,$nombre,$tel,$dir]); $ok=true; }
}
?>
<div class="contenedor"><h1>Servicios</h1>
<div class="grid-servicios"><div class="tarjeta-servicio"><h3>Mantenimiento</h3><p>Chequeo completo y calibración.</p></div><div class="tarjeta-servicio"><h3>Desinstalación</h3><p>Retiro y desconexión segura.</p></div></div>
<h2>Solicitar servicio</h2><?php if($ok): ?><p class="ok">Solicitud enviada.</p><?php endif; ?>
<form method="post" class="form-servicio">
<label>Tipo de servicio<select name="tipo_servicio" required><option value="">Elegí</option><option value="mantenimiento">Mantenimiento</option><option value="desinstalacion">Desinstalación</option></select></label>
<label>Modelo de alarma<select name="id_modelo" required><option value="">Elegí</option><?php foreach($modelos as $m): ?><option value="<?= (int)$m['id_producto'] ?>"><?= htmlspecialchars($m['modelo']) ?></option><?php endforeach; ?></select></label>
<label>Nombre y apellido<input name="nombre_apellido" required></label><label>Teléfono<input name="telefono" required></label><label>Dirección<input name="direccion" required></label><button class="btn" type="submit">Solicitar</button>
</form></div><?php include __DIR__.'/parciales/pie.php'; ?>