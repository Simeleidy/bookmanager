<?php
declare(strict_types=1);

/**
 * Modelo Tienda: encapsula los datos y la lógica de negocio de la tabla `tiendas`.
 * Todas las consultas usan sentencias preparadas (PDO).
 */
class Tienda
{
    private PDO $db;

    private string $nombre_tienda = '';
    private string $direccion = '';
    private string $ciudad = '';
    private string $estado = '';
    private string $codigo_postal = '';
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
    private function cargar(array $d): void
    {
        $this->nombre_tienda = trim((string) ($d['nombre_tienda'] ?? ''));
        $this->direccion     = trim((string) ($d['direccion'] ?? ''));
        $this->ciudad        = trim((string) ($d['ciudad'] ?? ''));
        $this->estado        = trim((string) ($d['estado'] ?? ''));
        $this->codigo_postal = trim((string) ($d['codigo_postal'] ?? ''));
    }

    /** Validaciones básicas en PHP. */
    private function validar(): bool
    {
        $this->errores = [];

        if ($this->nombre_tienda === '') {
            $this->errores['nombre_tienda'] = 'El nombre de la tienda no puede estar vacío.';
        } elseif (strlen($this->nombre_tienda) > 80) {
            $this->errores['nombre_tienda'] = 'El nombre no puede superar los 80 caracteres.';
        }
        if (strlen($this->direccion) > 120) {
            $this->errores['direccion'] = 'La dirección no puede superar los 120 caracteres.';
        }
        if ($this->codigo_postal !== '' && !preg_match('/^[0-9A-Za-z\- ]{3,10}$/', $this->codigo_postal)) {
            $this->errores['codigo_postal'] = 'Código postal inválido (3 a 10 caracteres).';
        }
        return $this->errores === [];
    }

    public function getAll(): array
    {
        $stmt = $this->db->prepare('SELECT * FROM tiendas ORDER BY nombre_tienda ASC');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM tiendas WHERE id_tienda = :id');
        $stmt->execute([':id' => $id]);
        $fila = $stmt->fetch();
        return $fila ?: null;
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
        if (!$this->validar()) {
            return false;
        }
        $stmt = $this->db->prepare(
            'INSERT INTO tiendas (nombre_tienda, direccion, ciudad, estado, codigo_postal)
             VALUES (:nombre, :direccion, :ciudad, :estado, :cp)'
        );
        return $stmt->execute($this->parametros());
    }

    public function update(int $id, array $datos): bool
    {
        $this->cargar($datos);
        if (!$this->validar()) {
            return false;
        }
        $stmt = $this->db->prepare(
            'UPDATE tiendas
                SET nombre_tienda = :nombre, direccion = :direccion, ciudad = :ciudad,
                    estado = :estado, codigo_postal = :cp
              WHERE id_tienda = :id'
        );
        return $stmt->execute($this->parametros() + [':id' => $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM tiendas WHERE id_tienda = :id');
        return $stmt->execute([':id' => $id]);
    }

    private function parametros(): array
    {
        return [
            ':nombre'    => $this->nombre_tienda,
            ':direccion' => $this->direccion ?: null,
            ':ciudad'    => $this->ciudad ?: null,
            ':estado'    => $this->estado ?: null,
            ':cp'        => $this->codigo_postal ?: null,
        ];
    }
}
