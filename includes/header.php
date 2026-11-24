<?php
declare(strict_types=1);
require_once __DIR__ . '/helpers.php';

// Cada página define $base (ruta hacia la raíz) y $pagina (sección activa) antes de incluir este archivo.
$base   = $base ?? '';
$pagina = $pagina ?? '';
$titulo_pagina = $titulo_pagina ?? 'BookManager';
$flash = tomarFlash();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($titulo_pagina) ?> · BookManager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="<?= $base ?>assets/css/estilo.css" rel="stylesheet">
</head>
<body>
<nav class="sidebar">
  <div class="marca"><i class="bi bi-book-half me-2"></i>BookManager</div>
  <ul class="nav flex-column mt-3">
    <li class="nav-item"><a class="nav-link <?= $pagina === 'inicio' ? 'active' : '' ?>" href="<?= $base ?>index.php"><i class="bi bi-house-door me-2"></i>Inicio</a></li>
    <li class="nav-item"><a class="nav-link <?= $pagina === 'tiendas' ? 'active' : '' ?>" href="<?= $base ?>views/tiendas/index.php"><i class="bi bi-shop me-2"></i>Tiendas</a></li>
    <li class="nav-item"><a class="nav-link <?= $pagina === 'titulos' ? 'active' : '' ?>" href="<?= $base ?>views/titulos/index.php"><i class="bi bi-journal-bookmark me-2"></i>Títulos</a></li>
  </ul>
</nav>
<main class="contenido">
<?php if ($flash): ?>
  <div class="alert alert-<?= e($flash['tipo']) ?> alert-dismissible fade show" role="alert">
    <?= e($flash['mensaje']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
  </div>
<?php endif; ?>
