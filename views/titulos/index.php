<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Titulo.php';

$db = (new Database())->getConnection();
$tituloModel = new Titulo($db);
$titulos = $tituloModel->getAll();

$base = '../../';
$pagina = 'titulos';
$titulo_pagina = 'Títulos';
require __DIR__ . '/../../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Títulos</h1>
  <a href="crear.php" class="btn btn-acento"><i class="bi bi-plus-lg me-1"></i>Nuevo título</a>
</div>
<div class="card shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light">
        <tr><th>Código</th><th>Título</th><th>Tipo</th><th>Editorial</th><th class="text-end">Precio</th><th class="text-end">Ventas</th><th>Publicación</th><th class="text-end">Acciones</th></tr>
      </thead>
      <tbody>
      <?php foreach ($titulos as $t): ?>
        <tr>
          <td><?= e($t['id_titulo']) ?></td>
          <td class="fw-semibold"><?= e($t['titulo']) ?></td>
          <td><span class="badge text-bg-secondary"><?= e($t['tipo']) ?></span></td>
          <td><?= e($t['nombre_pub']) ?></td>
          <td class="text-end"><?= $t['precio'] !== null ? '$' . number_format((float) $t['precio'], 2) : '—' ?></td>
          <td class="text-end"><?= $t['total_ventas'] !== null ? number_format((int) $t['total_ventas']) : '—' ?></td>
          <td><?= e(substr((string) $t['fecha_pub'], 0, 10)) ?></td>
          <td class="text-end text-nowrap">
            <a href="editar.php?id=<?= urlencode($t['id_titulo']) ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i> Editar</a>
            <a href="eliminar.php?id=<?= urlencode($t['id_titulo']) ?>" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Eliminar</a>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$titulos): ?>
        <tr><td colspan="8" class="text-center text-muted py-4">No hay títulos registrados. Usa «Nuevo título» para agregar el primero.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
