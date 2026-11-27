<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Tienda.php';
require_once __DIR__ . '/../../includes/helpers.php';

$db = (new Database())->getConnection();
$tiendaModel = new Tienda($db);

$id = trim((string) ($_GET['id'] ?? $_POST['id'] ?? ''));
$tienda = $tiendaModel->getById($id);
if (!$tienda) {
    flash('danger', 'La tienda solicitada no existe.');
    redirigir('index.php');
}

// La eliminación solo se ejecuta por POST, tras confirmar.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($tiendaModel->delete($id)) {
        flash('success', 'Tienda eliminada correctamente.');
    } else {
        flash('danger', $tiendaModel->getErrores()['general'] ?? 'No se pudo eliminar la tienda.');
    }
    redirigir('index.php');
}

$base = '../../';
$pagina = 'tiendas';
$titulo_pagina = 'Eliminar tienda';
require __DIR__ . '/../../includes/header.php';
?>
<h1 class="h3 mb-3">Eliminar tienda</h1>
<div class="card shadow-sm border-danger">
  <div class="card-body">
    <p class="mb-1">¿Seguro que quieres eliminar <strong><?= e($tienda['nombre_tienda']) ?></strong> (código <?= e($tienda['id_tienda']) ?>)?</p>
    <p class="text-muted mb-0">Esta acción no se puede deshacer. Si la tienda tiene ventas o descuentos asociados, no se eliminará.</p>
  </div>
  <form method="post" class="card-footer bg-white d-flex gap-2">
    <input type="hidden" name="id" value="<?= e($id) ?>">
    <button type="submit" class="btn btn-danger">Sí, eliminar</button>
    <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
  </form>
</div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
