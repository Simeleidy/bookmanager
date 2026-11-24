<?php
// Variables esperadas: $datos (array), $errores (array), $accion (texto del botón)
$campo = fn(string $k): string => e((string) ($datos[$k] ?? ''));
$error = fn(string $k): string => isset($errores[$k]) ? '<div class="invalid-feedback">' . e($errores[$k]) . '</div>' : '';
$clase = fn(string $k): string => isset($errores[$k]) ? ' is-invalid' : '';
?>
<form method="post" class="card shadow-sm" novalidate>
  <div class="card-body row g-3">
    <div class="col-md-8">
      <label for="nombre_tienda" class="form-label">Nombre de la tienda *</label>
      <input type="text" id="nombre_tienda" name="nombre_tienda" maxlength="80" class="form-control<?= $clase('nombre_tienda') ?>" value="<?= $campo('nombre_tienda') ?>" required>
      <?= $error('nombre_tienda') ?>
    </div>
    <div class="col-md-4">
      <label for="codigo_postal" class="form-label">Código postal</label>
      <input type="text" id="codigo_postal" name="codigo_postal" maxlength="10" class="form-control<?= $clase('codigo_postal') ?>" value="<?= $campo('codigo_postal') ?>">
      <?= $error('codigo_postal') ?>
    </div>
    <div class="col-12">
      <label for="direccion" class="form-label">Dirección</label>
      <input type="text" id="direccion" name="direccion" maxlength="120" class="form-control<?= $clase('direccion') ?>" value="<?= $campo('direccion') ?>">
      <?= $error('direccion') ?>
    </div>
    <div class="col-md-6">
      <label for="ciudad" class="form-label">Ciudad</label>
      <input type="text" id="ciudad" name="ciudad" maxlength="60" class="form-control" value="<?= $campo('ciudad') ?>">
    </div>
    <div class="col-md-6">
      <label for="estado" class="form-label">Estado / provincia</label>
      <input type="text" id="estado" name="estado" maxlength="60" class="form-control" value="<?= $campo('estado') ?>">
    </div>
  </div>
  <div class="card-footer bg-white d-flex gap-2">
    <button type="submit" class="btn btn-acento"><?= e($accion) ?></button>
    <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
  </div>
</form>
