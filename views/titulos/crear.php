<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Titulo.php';
require_once __DIR__ . '/../../includes/helpers.php';

$db = (new Database())->getConnection();
$tituloModel = new Titulo($db);
$publicadores = $tituloModel->getPublicadores();
$datos = [];
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = $_POST;
    if ($tituloModel->create($_POST)) {
        flash('success', 'Título creado correctamente.');
        redirigir('index.php');
    }
    $errores = $tituloModel->getErrores();
}

$base = '../../';
$pagina = 'titulos';
$titulo_pagina = 'Nuevo título';
require __DIR__ . '/../../includes/header.php';
?>
<h1 class="h3 mb-3">Nuevo título</h1>
<?php $accion = 'Guardar título'; require __DIR__ . '/_form.php'; ?>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
