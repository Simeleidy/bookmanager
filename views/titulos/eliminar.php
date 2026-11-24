<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Titulo.php';
require_once __DIR__ . '/../../includes/helpers.php';

$db = (new Database())->getConnection();
$tituloModel = new Titulo($db);

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$titulo = $tituloModel->getById($id);
if (!$titulo) {
    flash('danger', 'El título solicitado no existe.');
    redirigir('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tituloModel->delete($id);
    flash('success', 'Título eliminado correctamente.');
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
    <p class="mb-1">¿Seguro que quieres eliminar <strong><?= e($titulo['titulo']) ?></strong> (<?= e($titulo['nombre_pub']) ?>)?</p>
    <p class="text-muted mb-0">Esta acción no se puede deshacer.</p>
  </div>
  <form method="post" class="card-footer bg-white d-flex gap-2">
    <input type="hidden" name="id" value="<?= $id ?>">
    <button type="submit" class="btn btn-danger">Sí, eliminar</button>
    <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
  </form>
</div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
