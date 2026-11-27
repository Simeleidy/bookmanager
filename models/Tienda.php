<?php
declare(strict_types=1);

/**
 * Modelo Tienda: encapsula los datos y la lógica de negocio de la tabla `tiendas`.
 * La clave primaria (id_tienda) es un código de texto de hasta 4 caracteres.
 * Todas las consultas usan sentencias preparadas (PDO).
 */
class Tienda
{
    public const TERMINOS = ['Net 30', 'Net 60'];

    private PDO $db;

    private string $id_tienda = '';
    private string $nombre_tienda = '';
    private string $direcc_tienda = '';
    private string $ciudad = '';
    private string $estado = '';
    private string $pais = '';
    private string $cod_postal = '';
    private string $terminos = 'Net 30';
    private array $errores = [];

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getErrores(): array
    {
        return $this->errores;
    }

    /** Carga y limpia los datos recibidos (por ejemplo $_POST). */
    private function cargar(array $d, ?string $idFijo = null): void
    {
        $this->id_tienda     = $idFijo ?? trim((string) ($d['id_tienda'] ?? ''));
        $this->nombre_tienda = trim((string) ($d['nombre_tienda'] ?? ''));
        $this->direcc_tienda = trim((string) ($d['direcc_tienda'] ?? ''));
        $this->ciudad        = trim((string) ($d['ciudad'] ?? ''));
        $this->estado        = strtoupper(trim((string) ($d['estado'] ?? '')));
        $this->pais          = strtoupper(trim((string) ($d['pais'] ?? '')));
        $this->cod_postal    = trim((string) ($d['cod_postal'] ?? ''));
        $this->terminos      = trim((string) ($d['terminos'] ?? 'Net 30'));
    }

    /** Validaciones básicas en PHP. */
    private function validar(bool $esNueva): bool
    {
        $this->errores = [];

        if ($esNueva) {
            if ($this->id_tienda === '') {
                $this->errores['id_tienda'] = 'El código de la tienda es obligatorio.';
            } elseif (!preg_match('/^[A-Za-z0-9]{1,4}$/', $this->id_tienda)) {
                $this->errores['id_tienda'] = 'El código debe tener de 1 a 4 letras o números.';
            } elseif ($this->existe($this->id_tienda)) {
                $this->errores['id_tienda'] = 'Ya existe una tienda con ese código.';
            }
        }
        if ($this->nombre_tienda === '') {
            $this->errores['nombre_tienda'] = 'El nombre de la tienda no puede estar vacío.';
        } elseif (strlen($this->nombre_tienda) > 40) {
            $this->errores['nombre_tienda'] = 'El nombre no puede superar los 40 caracteres.';
        }
        if (strlen($this->direcc_tienda) > 40) {
            $this->errores['direcc_tienda'] = 'La dirección no puede superar los 40 caracteres.';
        }
        if (strlen($this->ciudad) > 15) {
            $this->errores['ciudad'] = 'La ciudad no puede superar los 15 caracteres.';
        }
        if ($this->estado !== '' && !preg_match('/^[A-Z]{2}$/', $this->estado)) {
            $this->errores['estado'] = 'Use 2 letras (ej. CA).';
        }
        if ($this->pais !== '' && !preg_match('/^[A-Z]{3}$/', $this->pais)) {
            $this->errores['pais'] = 'Use 3 letras (ej. USA).';
        }
        if ($this->cod_postal !== '' && !preg_match('/^[0-9]{5}$/', $this->cod_postal)) {
            $this->errores['cod_postal'] = 'El código postal debe tener 5 dígitos.';
        }
        if (!in_array($this->terminos, self::TERMINOS, true)) {
            $this->errores['terminos'] = 'Selecciona unos términos válidos.';
        }
        return $this->errores === [];
    }

    public function getAll(): array
    {
        $stmt = $this->db->prepare('SELECT * FROM tiendas ORDER BY nombre_tienda ASC');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById(string $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM tiendas WHERE id_tienda = :id');
        $stmt->execute([':id' => $id]);
        $fila = $stmt->fetch();
        return $fila ?: null;
    }

    public function existe(string $id): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM tiendas WHERE id_tienda = :id');
        $stmt->execute([':id' => $id]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function contar(): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM tiendas');
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function create(array $datos): bool
    {
        $this->cargar($datos);
        if (!$this->validar(true)) {
            return false;
        }
        $stmt = $this->db->prepare(
            'INSERT INTO tiendas (id_tienda, nombre_tienda, direcc_tienda, ciudad, estado, pais, cod_postal, terminos)
             VALUES (:id, :nombre, :direccion, :ciudad, :estado, :pais, :cp, :terminos)'
        );
        return $stmt->execute($this->parametros());
    }

    public function update(string $id, array $datos): bool
    {
        $this->cargar($datos, $id); // el código (clave primaria) no se modifica
        if (!$this->validar(false)) {
            return false;
        }
        $stmt = $this->db->prepare(
            'UPDATE tiendas
                SET nombre_tienda = :nombre, direcc_tienda = :direccion, ciudad = :ciudad,
                    estado = :estado, pais = :pais, cod_postal = :cp, terminos = :terminos
              WHERE id_tienda = :id'
        );
        return $stmt->execute($this->parametros());
    }

    /** Elimina la tienda solo si no tiene ventas ni descuentos asociados. */
    public function delete(string $id): bool
    {
        $this->errores = [];
        if ($this->tieneRelaciones($id)) {
            $this->errores['general'] = 'No se puede eliminar la tienda: tiene ventas o descuentos asociados.';
            return false;
        }
        $stmt = $this->db->prepare('DELETE FROM tiendas WHERE id_tienda = :id');
        return $stmt->execute([':id' => $id]);
    }

    private function tieneRelaciones(string $id): bool
    {
        // Nombres de tabla fijos (no provienen del usuario); el valor va como parámetro.
        foreach (['ventas', 'detalle_venta', 'descuento'] as $tabla) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$tabla} WHERE id_tienda = :id");
            $stmt->execute([':id' => $id]);
            if ((int) $stmt->fetchColumn() > 0) {
                return true;
            }
        }
        return false;
    }

    private function parametros(): array
    {
        return [
            ':id'        => $this->id_tienda,
            ':nombre'    => $this->nombre_tienda,
            ':direccion' => $this->direcc_tienda,
            ':ciudad'    => $this->ciudad,
            ':estado'    => $this->estado,
            ':pais'      => $this->pais,
            ':cp'        => $this->cod_postal,
            ':terminos'  => $this->terminos,
        ];
    }
}
