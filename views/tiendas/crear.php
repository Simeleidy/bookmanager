<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Tienda.php';
require_once __DIR__ . '/../../includes/helpers.php';

$db = (new Database())->getConnection();
$tiendaModel = new Tienda($db);
$datos = [];
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = $_POST;
    if ($tiendaModel->create($_POST)) {
        flash('success', 'Tienda creada correctamente.');
        redirigir('index.php');
    }
    $errores = $tiendaModel->getErrores();
}

$base = '../../';
$pagina = 'tiendas';
$titulo_pagina = 'Nueva tienda';
require __DIR__ . '/../../includes/header.php';
?>
<h1 class="h3 mb-3">Nueva tienda</h1>
<?php $accion = 'Guardar tienda'; $esNuevo = true; require __DIR__ . '/_form.php'; ?>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
