<?php
// Variables esperadas: $datos (array), $errores (array), $accion (texto del botón), $esNuevo (bool)
$campo = fn(string $k): string => e((string) ($datos[$k] ?? ''));
$error = fn(string $k): string => isset($errores[$k]) ? '<div class="invalid-feedback">' . e($errores[$k]) . '</div>' : '';
$clase = fn(string $k): string => isset($errores[$k]) ? ' is-invalid' : '';
?>
<form method="post" class="card shadow-sm" novalidate>
  <div class="card-body row g-3">
    <div class="col-md-3">
      <label for="id_tienda" class="form-label">Código *</label>
      <input type="text" id="id_tienda" name="id_tienda" maxlength="4" class="form-control<?= $clase('id_tienda') ?>" value="<?= $campo('id_tienda') ?>" <?= $esNuevo ? 'required' : 'readonly' ?>>
      <?= $error('id_tienda') ?>
      <?php if ($esNuevo): ?><div class="form-text">Hasta 4 caracteres (ej. 7066).</div><?php endif; ?>
    </div>
    <div class="col-md-9">
      <label for="nombre_tienda" class="form-label">Nombre de la tienda *</label>
      <input type="text" id="nombre_tienda" name="nombre_tienda" maxlength="40" class="form-control<?= $clase('nombre_tienda') ?>" value="<?= $campo('nombre_tienda') ?>" required>
      <?= $error('nombre_tienda') ?>
    </div>
    <div class="col-12">
      <label for="direcc_tienda" class="form-label">Dirección</label>
      <input type="text" id="direcc_tienda" name="direcc_tienda" maxlength="40" class="form-control<?= $clase('direcc_tienda') ?>" value="<?= $campo('direcc_tienda') ?>">
      <?= $error('direcc_tienda') ?>
    </div>
    <div class="col-md-4">
      <label for="ciudad" class="form-label">Ciudad</label>
      <input type="text" id="ciudad" name="ciudad" maxlength="15" class="form-control<?= $clase('ciudad') ?>" value="<?= $campo('ciudad') ?>">
      <?= $error('ciudad') ?>
    </div>
    <div class="col-md-2">
      <label for="estado" class="form-label">Estado</label>
      <input type="text" id="estado" name="estado" maxlength="2" placeholder="CA" class="form-control<?= $clase('estado') ?>" value="<?= $campo('estado') ?>">
      <?= $error('estado') ?>
    </div>
    <div class="col-md-2">
      <label for="pais" class="form-label">País</label>
      <input type="text" id="pais" name="pais" maxlength="3" placeholder="USA" class="form-control<?= $clase('pais') ?>" value="<?= $campo('pais') ?>">
      <?= $error('pais') ?>
    </div>
    <div class="col-md-2">
      <label for="cod_postal" class="form-label">C. postal</label>
      <input type="text" id="cod_postal" name="cod_postal" maxlength="5" class="form-control<?= $clase('cod_postal') ?>" value="<?= $campo('cod_postal') ?>">
      <?= $error('cod_postal') ?>
    </div>
    <div class="col-md-2">
      <label for="terminos" class="form-label">Términos</label>
      <select id="terminos" name="terminos" class="form-select<?= $clase('terminos') ?>">
        <?php foreach (Tienda::TERMINOS as $t): ?>
          <option value="<?= e($t) ?>" <?= ($datos['terminos'] ?? 'Net 30') === $t ? 'selected' : '' ?>><?= e($t) ?></option>
        <?php endforeach; ?>
      </select>
      <?= $error('terminos') ?>
    </div>
  </div>
  <div class="card-footer bg-white d-flex gap-2">
    <button type="submit" class="btn btn-acento"><?= e($accion) ?></button>
    <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
  </div>
</form>
