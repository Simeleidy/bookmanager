<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Titulo.php';
require_once __DIR__ . '/../../includes/helpers.php';

$db = (new Database())->getConnection();
$tituloModel = new Titulo($db);

$id = trim((string) ($_GET['id'] ?? $_POST['id'] ?? ''));
$titulo = $tituloModel->getById($id);
if (!$titulo) {
    flash('danger', 'El título solicitado no existe.');
    redirigir('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($tituloModel->delete($id)) {
        flash('success', 'Título eliminado correctamente.');
    } else {
        flash('danger', $tituloModel->getErrores()['general'] ?? 'No se pudo eliminar el título.');
    }
    redirigir('index.php');
}

$base = '../../';
$pagina = 'titulos';
$titulo_pagina = 'Eliminar título';
require __DIR__ . '/../../includes/header.php';
?>
<h1 class="h3 mb-3">Eliminar título</h1>
<div class="card shadow-sm border-danger">
  <div class="card-body">
    <p class="mb-1">¿Seguro que quieres eliminar <strong><?= e($titulo['titulo']) ?></strong> (código <?= e($titulo['id_titulo']) ?>)?</p>
    <p class="text-muted mb-0">Esta acción no se puede deshacer. Si el título tiene ventas, autores o derechos asociados, no se eliminará.</p>
  </div>
  <form method="post" class="card-footer bg-white d-flex gap-2">
    <input type="hidden" name="id" value="<?= e($id) ?>">
    <button type="submit" class="btn btn-danger">Sí, eliminar</button>
    <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
  </form>
</div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
