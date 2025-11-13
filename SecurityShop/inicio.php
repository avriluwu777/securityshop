<?php
require_once __DIR__.'/conexion.php'; include __DIR__.'/parciales/encabezado.php';
$p=$pdo->query("SELECT pr.id_producto, pr.nombre, pr.modelo, pr.precio, pr.envio, dp.descripcion, dp.especificaciones
                FROM productos pr LEFT JOIN detalle_productos dp ON dp.id_producto=pr.id_producto LIMIT 1")->fetch();
?>
<section class="hero"><div class="contenedor">
  <div class="hero-texto">
    <h1>Protegé tu hogar con <span>SecurityShop</span></h1>
    <p>Alarma con sensor PIR, buzzer y panel con teclado y pantalla LCD.</p>
    <a class="btn" href="<?= URL_BASE ?>/producto.php">Ver producto</a>
  </div>
  <div class="hero-tarjeta">
    <div class="tarjeta-producto">
      <div class="img-producto"><img src="<?= URL_BASE ?>/recursos/alarma.png" alt="Alarma"></div>
      <div class="info-producto">
        <h3><?= htmlspecialchars($p['nombre']) ?></h3>
        <p class="suave">Modelo: <?= htmlspecialchars($p['modelo']) ?></p>
        <div class="precio">$<?= number_format($p['precio'],0,',','.') ?></div>
        <a class="btn" href="<?= URL_BASE ?>/producto.php">Ver detalles</a>
      </div>
    </div>
  </div>
</div></section>
<section class="resenas"><div class="contenedor">
  <h2>Reseñas de la página</h2>
  <?php
    $rs=$pdo->query("SELECT r.puntuacion, r.comentario, u.nombre usuario, r.creado_en
                     FROM resenas r JOIN usuarios u ON u.id_usuario=r.id_usuario
                     WHERE r.tipo='sitio' ORDER BY r.creado_en DESC LIMIT 5")->fetchAll();
    if(!$rs) echo '<p class="suave">Aún no hay reseñas.</p>';
    else{ echo '<div class="lista-resenas">';
      foreach($rs as $row){
        echo '<div class="resena"><div class="estrellas" data-estrellas="'.(int)$row['puntuacion'].'"></div>
              <div><strong>'.htmlspecialchars($row['usuario']).'</strong>
              <span class="suave"> '.htmlspecialchars(date('d/m/Y', strtotime($row['creado_en']))).'</span></div>
              <p>'.htmlspecialchars($row['comentario']).'</p></div>';
      } echo '</div>'; }
  ?>
  <div class="form-resena"><h3>Dejá tu reseña</h3>
  <?php if(!autenticado()): ?><p class="suave">Necesitás iniciar sesión para publicar.</p>
  <?php else: ?><form method="post" action="<?= URL_BASE ?>/enviar_resena.php">
    <input type="hidden" name="tipo" value="sitio">
    <label>Calificación
      <select name="puntuacion" required>
        <option value="">Elegí</option><option>5</option><option>4</option><option>3</option><option>2</option><option>1</option>
      </select>
    </label>
    <label>Comentario <textarea name="comentario" required maxlength="500"></textarea></label>
    <button class="btn" type="submit">Publicar</button>
  </form><?php endif; ?></div>
</div></section>
<?php include __DIR__.'/parciales/pie.php'; ?>