<?php
// Variables esperadas: $datos, $errores, $accion, $publicadores (de Titulo::getPublicadores())
$campo = fn(string $k): string => e((string) ($datos[$k] ?? ''));
$error = fn(string $k): string => isset($errores[$k]) ? '<div class="invalid-feedback">' . e($errores[$k]) . '</div>' : '';
$clase = fn(string $k): string => isset($errores[$k]) ? ' is-invalid' : '';
?>
<form method="post" class="card shadow-sm" novalidate>
  <div class="card-body row g-3">
    <div class="col-md-8">
      <label for="titulo" class="form-label">Título *</label>
      <input type="text" id="titulo" name="titulo" maxlength="120" class="form-control<?= $clase('titulo') ?>" value="<?= $campo('titulo') ?>" required>
      <?= $error('titulo') ?>
    </div>
    <div class="col-md-4">
      <label for="tipo" class="form-label">Tipo *</label>
      <select id="tipo" name="tipo" class="form-select<?= $clase('tipo') ?>">
        <?php foreach (Titulo::TIPOS as $tipo): ?>
          <option value="<?= e($tipo) ?>" <?= ($datos['tipo'] ?? 'General') === $tipo ? 'selected' : '' ?>><?= e($tipo) ?></option>
        <?php endforeach; ?>
      </select>
      <?= $error('tipo') ?>
    </div>
    <div class="col-md-6">
      <label for="id_pub" class="form-label">Editorial *</label>
      <select id="id_pub" name="id_pub" class="form-select<?= $clase('id_pub') ?>" required>
        <option value="">— Selecciona una editorial —</option>
        <?php foreach ($publicadores as $p): ?>
          <option value="<?= (int) $p['id_pub'] ?>" <?= (int) ($datos['id_pub'] ?? 0) === (int) $p['id_pub'] ? 'selected' : '' ?>><?= e($p['nombre_pub']) ?></option>
        <?php endforeach; ?>
      </select>
      <?= $error('id_pub') ?>
    </div>
    <div class="col-md-3">
      <label for="precio" class="form-label">Precio (RD$) *</label>
      <input type="text" inputmode="decimal" id="precio" name="precio" class="form-control<?= $clase('precio') ?>" value="<?= $campo('precio') ?>" required>
      <?= $error('precio') ?>
    </div>
    <div class="col-md-3">
      <label for="ventas_ytd" class="form-label">Ventas del año</label>
      <input type="number" min="0" id="ventas_ytd" name="ventas_ytd" class="form-control<?= $clase('ventas_ytd') ?>" value="<?= $campo('ventas_ytd') !== '' ? $campo('ventas_ytd') : '0' ?>">
      <?= $error('ventas_ytd') ?>
    </div>
    <div class="col-md-4">
      <label for="fecha_pub" class="form-label">Fecha de publicación</label>
      <input type="date" id="fecha_pub" name="fecha_pub" class="form-control<?= $clase('fecha_pub') ?>" value="<?= $campo('fecha_pub') ?>">
      <?= $error('fecha_pub') ?>
    </div>
    <div class="col-md-8">
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
