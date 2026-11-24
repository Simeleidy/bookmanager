<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Titulo.php';
require_once __DIR__ . '/../../includes/helpers.php';

$db = (new Database())->getConnection();
$tituloModel = new Titulo($db);

$id = (int) ($_GET['id'] ?? 0);
$titulo = $tituloModel->getById($id);
if (!$titulo) {
    flash('danger', 'El título solicitado no existe.');
    redirigir('index.php');
}

$publicadores = $tituloModel->getPublicadores();
$datos = $titulo;
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = $_POST;
    if ($tituloModel->update($id, $_POST)) {
        flash('success', 'Título actualizado correctamente.');
        redirigir('index.php');
    }
    $errores = $tituloModel->getErrores();
}

$base = '../../';
$pagina = 'titulos';
$titulo_pagina = 'Editar título';
require __DIR__ . '/../../includes/header.php';
?>
<h1 class="h3 mb-3">Editar título #<?= $id ?></h1>
<?php $accion = 'Guardar cambios'; require __DIR__ . '/_form.php'; ?>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
