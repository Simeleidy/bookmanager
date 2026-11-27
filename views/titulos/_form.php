<?php
// Variables esperadas: $datos, $errores, $accion, $esNuevo, $publicadores (de Titulo::getPublicadores())
$campo = fn(string $k): string => e((string) ($datos[$k] ?? ''));
$error = fn(string $k): string => isset($errores[$k]) ? '<div class="invalid-feedback">' . e($errores[$k]) . '</div>' : '';
$clase = fn(string $k): string => isset($errores[$k]) ? ' is-invalid' : '';
?>
<form method="post" class="card shadow-sm" novalidate>
  <div class="card-body row g-3">
    <div class="col-md-3">
      <label for="id_titulo" class="form-label">Código *</label>
      <input type="text" id="id_titulo" name="id_titulo" maxlength="6" class="form-control<?= $clase('id_titulo') ?>" value="<?= $campo('id_titulo') ?>" <?= $esNuevo ? 'required' : 'readonly' ?>>
      <?= $error('id_titulo') ?>
      <?php if ($esNuevo): ?><div class="form-text">Hasta 6 caracteres (ej. BU1032).</div><?php endif; ?>
    </div>
    <div class="col-md-9">
      <label for="titulo" class="form-label">Título *</label>
      <input type="text" id="titulo" name="titulo" maxlength="60" class="form-control<?= $clase('titulo') ?>" value="<?= $campo('titulo') ?>" required>
      <?= $error('titulo') ?>
    </div>
    <div class="col-md-4">
      <label for="tipo" class="form-label">Tipo *</label>
      <select id="tipo" name="tipo" class="form-select<?= $clase('tipo') ?>">
        <?php foreach (Titulo::TIPOS as $tipo): ?>
          <option value="<?= e($tipo) ?>" <?= ($datos['tipo'] ?? 'UNDECIDED') === $tipo ? 'selected' : '' ?>><?= e($tipo) ?></option>
        <?php endforeach; ?>
      </select>
      <?= $error('tipo') ?>
    </div>
    <div class="col-md-5">
      <label for="id_pub" class="form-label">Editorial *</label>
      <select id="id_pub" name="id_pub" class="form-select<?= $clase('id_pub') ?>" required>
        <option value="">— Selecciona una editorial —</option>
        <?php foreach ($publicadores as $p): ?>
          <option value="<?= e($p['id_pub']) ?>" <?= (string) ($datos['id_pub'] ?? '') === (string) $p['id_pub'] ? 'selected' : '' ?>><?= e($p['nombre_pub']) ?></option>
        <?php endforeach; ?>
      </select>
      <?= $error('id_pub') ?>
    </div>
    <div class="col-md-3">
      <label for="fecha_pub" class="form-label">Fecha de publicación *</label>
      <input type="date" id="fecha_pub" name="fecha_pub" class="form-control<?= $clase('fecha_pub') ?>" value="<?= $campo('fecha_pub') !== '' ? $campo('fecha_pub') : date('Y-m-d') ?>">
      <?= $error('fecha_pub') ?>
    </div>
    <div class="col-md-3">
      <label for="precio" class="form-label">Precio ($)</label>
      <input type="text" inputmode="decimal" id="precio" name="precio" class="form-control<?= $clase('precio') ?>" value="<?= $campo('precio') ?>">
      <?= $error('precio') ?>
    </div>
    <div class="col-md-3">
      <label for="avance" class="form-label">Avance ($)</label>
      <input type="text" inputmode="decimal" id="avance" name="avance" class="form-control<?= $clase('avance') ?>" value="<?= $campo('avance') ?>">
      <?= $error('avance') ?>
    </div>
    <div class="col-md-3">
      <label for="total_ventas" class="form-label">Total de ventas</label>
      <input type="number" min="0" id="total_ventas" name="total_ventas" class="form-control<?= $clase('total_ventas') ?>" value="<?= $campo('total_ventas') ?>">
      <?= $error('total_ventas') ?>
    </div>
    <div class="col-md-3">
      <label for="contrato" class="form-label">Contrato</label>
      <select id="contrato" name="contrato" class="form-select<?= $clase('contrato') ?>">
        <option value="1" <?= (string) ($datos['contrato'] ?? '0') === '1' ? 'selected' : '' ?>>Sí</option>
        <option value="0" <?= (string) ($datos['contrato'] ?? '0') !== '1' ? 'selected' : '' ?>>No</option>
      </select>
      <?= $error('contrato') ?>
    </div>
    <div class="col-12">
      <label for="notas" class="form-label">Notas</label>
      <input type="text" id="notas" name="notas" maxlength="255" class="form-control<?= $clase('notas') ?>" value="<?= $campo('notas') ?>">
      <?= $error('notas') ?>
    </div>
  </div>
  <div class="card-footer bg-white d-flex gap-2">
    <button type="submit" class="btn btn-acento"><?= e($accion) ?></button>
    <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
  </div>
</form>
