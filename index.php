<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Tienda.php';
require_once __DIR__ . '/models/Titulo.php';

$db = (new Database())->getConnection();
$tiendaModel = new Tienda($db);
$tituloModel = new Titulo($db);

$totalTiendas = $tiendaModel->contar();
$totalTitulos = $tituloModel->contar();
$totalEditoriales = count($tituloModel->getPublicadores());
$recientes = array_slice($tituloModel->getAll(), 0, 5);

$base = '';
$pagina = 'inicio';
$titulo_pagina = 'Inicio';
require __DIR__ . '/includes/header.php';
?>
<h1 class="h3 mb-4">Panel de BookManager</h1>
<div class="row g-3 mb-4">
  <div class="col-md-4"><div class="card tarjeta-dato shadow-sm"><div class="card-body">
    <div class="text-muted">Tiendas registradas</div><div class="display-6"><?= $totalTiendas ?></div></div></div></div>
  <div class="col-md-4"><div class="card tarjeta-dato shadow-sm"><div class="card-body">
    <div class="text-muted">Títulos en inventario</div><div class="display-6"><?= $totalTitulos ?></div></div></div></div>
  <div class="col-md-4"><div class="card tarjeta-dato shadow-sm"><div class="card-body">
    <div class="text-muted">Editoriales</div><div class="display-6"><?= $totalEditoriales ?></div></div></div></div>
</div>
<div class="card shadow-sm">
  <div class="card-header bg-white fw-semibold">Títulos del catálogo</div>
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light"><tr><th>Título</th><th>Editorial</th><th class="text-end">Precio</th></tr></thead>
      <tbody>
      <?php foreach ($recientes as $t): ?>
        <tr><td><?= e($t['titulo']) ?></td><td><?= e($t['nombre_pub']) ?></td><td class="text-end"><?= $t['precio'] !== null ? '$' . number_format((float) $t['precio'], 2) : '—' ?></td></tr>
      <?php endforeach; ?>
      <?php if (!$recientes): ?><tr><td colspan="3" class="text-center text-muted py-4">Aún no hay títulos. Crea el primero desde la sección Títulos.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
