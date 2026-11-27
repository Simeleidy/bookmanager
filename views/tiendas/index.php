<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Tienda.php';

$db = (new Database())->getConnection();
$tiendaModel = new Tienda($db);
$tiendas = $tiendaModel->getAll();

$base = '../../';
$pagina = 'tiendas';
$titulo_pagina = 'Tiendas';
require __DIR__ . '/../../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Tiendas</h1>
  <a href="crear.php" class="btn btn-acento"><i class="bi bi-plus-lg me-1"></i>Nueva tienda</a>
</div>
<div class="card shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light">
        <tr><th>Código</th><th>Nombre</th><th>Dirección</th><th>Ciudad</th><th>Estado</th><th>País</th><th>C. postal</th><th>Términos</th><th class="text-end">Acciones</th></tr>
      </thead>
      <tbody>
      <?php foreach ($tiendas as $t): ?>
        <tr>
          <td><?= e($t['id_tienda']) ?></td>
          <td class="fw-semibold"><?= e($t['nombre_tienda']) ?></td>
          <td><?= e($t['direcc_tienda']) ?></td>
          <td><?= e($t['ciudad']) ?></td>
          <td><?= e($t['estado']) ?></td>
          <td><?= e($t['pais']) ?></td>
          <td><?= e($t['cod_postal']) ?></td>
          <td><?= e($t['terminos']) ?></td>
          <td class="text-end text-nowrap">
            <a href="editar.php?id=<?= urlencode($t['id_tienda']) ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i> Editar</a>
            <a href="eliminar.php?id=<?= urlencode($t['id_tienda']) ?>" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Eliminar</a>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$tiendas): ?>
        <tr><td colspan="9" class="text-center text-muted py-4">No hay tiendas registradas. Usa «Nueva tienda» para agregar la primera.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
