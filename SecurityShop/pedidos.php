<?php
require_once __DIR__.'/conexion.php'; if(!autenticado()){ header('Location: '.URL_BASE.'/ingresar.php'); exit; }
include __DIR__.'/parciales/encabezado.php';
$ps=$pdo->prepare("SELECT id_pedido, creado_en, total, estado FROM pedidos WHERE id_usuario=? ORDER BY creado_en DESC");
$ps->execute([$_SESSION['id_usuario']]); $ps=$ps->fetchAll();
?>
<div class="contenedor"><h1>Mis pedidos</h1>
<?php if(!$ps): ?><p class="suave">No tenés pedidos aún.</p>
<?php else: ?><table class="tabla"><thead><tr><th>#</th><th>Fecha</th><th>Total</th><th>Estado</th></tr></thead><tbody>
<?php foreach($ps as $o): ?><tr><td><?= (int)$o['id_pedido'] ?></td>
<td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($o['creado_en']))) ?></td>
<td>$<?= number_format($o['total'],0,',','.') ?></td><td><?= htmlspecialchars($o['estado']) ?></td></tr><?php endforeach; ?>
</tbody></table><?php endif; ?></div>
<?php include __DIR__.'/parciales/pie.php'; ?>