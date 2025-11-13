<?php
require_once __DIR__.'/conexion.php'; include __DIR__.'/parciales/encabezado.php';
$p=$pdo->query("SELECT pr.id_producto, pr.nombre, pr.modelo, pr.precio, pr.envio, dp.descripcion, dp.especificaciones
                FROM productos pr LEFT JOIN detalle_productos dp ON dp.id_producto=pr.id_producto LIMIT 1")->fetch();
?>
<section class="contenedor detalle-producto">
  <div class="media"><img src="<?= URL_BASE ?>/recursos/alarma.png" alt="Alarma"></div>
  <div class="info">
    <h1><?= htmlspecialchars($p['nombre']) ?></h1>
    <div class="suave">Modelo: <?= htmlspecialchars($p['modelo']) ?></div>
    <div class="precio">$<?= number_format($p['precio'],0,',','.') ?></div>
    <p><?= nl2br(htmlspecialchars($p['descripcion'])) ?></p>
    <details class="especificaciones"><summary>Especificaciones</summary>
      <pre><?= htmlspecialchars($p['especificaciones']) ?></pre>
    </details>
    <p class="suave"><?= nl2br(htmlspecialchars($p['envio'])) ?></p>
    <form method="post" action="<?= URL_BASE ?>/a_carrito.php">
      <input type="hidden" name="id_producto" value="<?= (int)$p['id_producto'] ?>">
      <label>Cantidad <input type="number" name="cantidad" value="1" min="1" max="10" required></label>
      <?php if (autenticado()): ?><button class="btn" type="submit">Agregar al carrito</button>
      <?php else: ?><a class="btn" href="<?= URL_BASE ?>/ingresar.php">Ingresá para comprar</a><?php endif; ?>
    </form>
  </div>
</section>
<section class="contenedor"><h2>Reseñas del producto</h2>
  <?php
    $rs=$pdo->prepare("SELECT r.puntuacion, r.comentario, u.nombre usuario, r.creado_en
                       FROM resenas r JOIN usuarios u ON u.id_usuario=r.id_usuario
                       WHERE r.tipo='producto' AND r.id_producto=? ORDER BY r.creado_en DESC");
    $rs->execute([$p['id_producto']]); $filas=$rs->fetchAll();
    $avg=$pdo->prepare("SELECT AVG(puntuacion) prom, COUNT(*) c FROM resenas WHERE tipo='producto' AND id_producto=?");
    $avg->execute([$p['id_producto']]); $a=$avg->fetch(); $prom = $a and $a['c']>0 ? round($a['prom'],1) : null;
  ?>
<?php
// 1) Calcular primero
$stmt = $pdo->prepare("
  SELECT COUNT(*) AS cantidad,
         ROUND(AVG(puntuacion), 1) AS promedio
  FROM resenas
  WHERE tipo = 'producto' AND id_producto = ?
");
$stmt->execute([$p['id_producto']]);
$data = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['cantidad' => 0, 'promedio' => null];

$cantidad = (int)$data['cantidad'];
$promedio = $cantidad > 0 ? number_format((float)$data['promedio'], 1) : null;
?>

<!-- 2) Recién ahora imprimir -->
<div class="suave">
  Promedio:
  <?= $cantidad > 0 ? ($promedio . ' / 5') : 'Sin calificaciones' ?>
</div>

  <?php if(!$filas) echo '<p class="suave">Aún no hay reseñas.</p>'; else { echo '<div class="lista-resenas">';
    foreach($filas as $row){ echo '<div class="resena"><div class="estrellas" data-estrellas="'.(int)$row['puntuacion'].'"></div>
    <div><strong>'.htmlspecialchars($row['usuario']).'</strong><span class="suave"> '.htmlspecialchars(date('d/m/Y', strtotime($row['creado_en']))).'</span></div>
    <p>'.htmlspecialchars($row['comentario']).'</p></div>'; } echo '</div>'; } ?>
  <div class="form-resena"><h3>Dejá tu reseña</h3>
    <?php if(!autenticado()): ?><p class="suave">Necesitás iniciar sesión para publicar.</p><?php else: ?>
    <form method="post" action="<?= URL_BASE ?>/enviar_resena.php">
      <input type="hidden" name="tipo" value="producto">
      <input type="hidden" name="id_producto" value="<?= (int)$p['id_producto'] ?>">
      <label>Calificación
        <select name="puntuacion" required><option value="">Elegí</option><option>5</option><option>4</option><option>3</option><option>2</option><option>1</option></select>
      </label>
      <label>Comentario <textarea name="comentario" required maxlength="500"></textarea></label>
      <button class="btn" type="submit">Publicar</button>
    </form><?php endif; ?>
  </div>
</section>
<?php include __DIR__.'/parciales/pie.php'; ?>