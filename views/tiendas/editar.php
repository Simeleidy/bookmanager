<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Tienda.php';
require_once __DIR__ . '/../../includes/helpers.php';

$db = (new Database())->getConnection();
$tiendaModel = new Tienda($db);

$id = trim((string) ($_GET['id'] ?? ''));
$tienda = $tiendaModel->getById($id);
if (!$tienda) {
    flash('danger', 'La tienda solicitada no existe.');
    redirigir('index.php');
}

$datos = $tienda;
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = $_POST;
    $datos['id_tienda'] = $id;
    if ($tiendaModel->update($id, $_POST)) {
        flash('success', 'Tienda actualizada correctamente.');
        redirigir('index.php');
    }
    $errores = $tiendaModel->getErrores();
}

$base = '../../';
$pagina = 'tiendas';
$titulo_pagina = 'Editar tienda';
require __DIR__ . '/../../includes/header.php';
?>
<h1 class="h3 mb-3">Editar tienda <?= e($id) ?></h1>
<?php $accion = 'Guardar cambios'; $esNuevo = false; require __DIR__ . '/_form.php'; ?>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
