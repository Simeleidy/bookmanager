<?php
declare(strict_types=1);

/**
 * Modelo Titulo: encapsula los datos y la lógica de la tabla `titulos`
 * (con relación a `publicadores`). Consultas con sentencias preparadas.
 */
class Titulo
{
    public const TIPOS = ['General', 'Novela', 'Clásico', 'Técnico', 'Infantil', 'Biografía'];

    private PDO $db;

    private string $titulo = '';
    private string $tipo = 'General';
    private int $id_pub = 0;
    private string $precio = '';
    private int $ventas_ytd = 0;
    private string $fecha_pub = '';
    private string $notas = '';
    private array $errores = [];

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getErrores(): array
    {
        return $this->errores;
    }

    private function cargar(array $d): void
    {
        $this->titulo     = trim((string) ($d['titulo'] ?? ''));
        $this->tipo       = trim((string) ($d['tipo'] ?? 'General'));
        $this->id_pub     = (int) ($d['id_pub'] ?? 0);
        $this->precio     = str_replace(',', '.', trim((string) ($d['precio'] ?? '')));
        $this->ventas_ytd = (int) ($d['ventas_ytd'] ?? 0);
        $this->fecha_pub  = trim((string) ($d['fecha_pub'] ?? ''));
        $this->notas      = trim((string) ($d['notas'] ?? ''));
    }

    private function validar(): bool
    {
        $this->errores = [];

        if ($this->titulo === '') {
            $this->errores['titulo'] = 'El título no puede estar vacío.';
        } elseif (strlen($this->titulo) > 120) {
            $this->errores['titulo'] = 'El título no puede superar los 120 caracteres.';
        }
        if (!in_array($this->tipo, self::TIPOS, true)) {
            $this->errores['tipo'] = 'Selecciona un tipo válido.';
        }
        if ($this->id_pub <= 0 || !$this->existePublicador($this->id_pub)) {
            $this->errores['id_pub'] = 'Selecciona una editorial válida.';
        }
        if ($this->precio === '' || !is_numeric($this->precio)) {
            $this->errores['precio'] = 'El precio es obligatorio y debe ser numérico.';
        } elseif ((float) $this->precio < 0) {
            $this->errores['precio'] = 'El precio no puede ser negativo.';
        } elseif ((float) $this->precio > 999999.99) {
            $this->errores['precio'] = 'El precio es demasiado alto.';
        }
        if ($this->ventas_ytd < 0) {
            $this->errores['ventas_ytd'] = 'Las ventas no pueden ser negativas.';
        }
        if ($this->fecha_pub !== '') {
            $f = DateTime::createFromFormat('Y-m-d', $this->fecha_pub);
            if (!$f || $f->format('Y-m-d') !== $this->fecha_pub) {
                $this->errores['fecha_pub'] = 'Fecha inválida.';
            }
        }
        if (strlen($this->notas) > 255) {
            $this->errores['notas'] = 'Las notas no pueden superar los 255 caracteres.';
        }
        return $this->errores === [];
    }

    /** Editoriales para el <select> del formulario. */
    public function getPublicadores(): array
    {
        $stmt = $this->db->prepare('SELECT id_pub, nombre_pub FROM publicadores ORDER BY nombre_pub ASC');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function existePublicador(int $id): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM publicadores WHERE id_pub = :id');
        $stmt->execute([':id' => $id]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function getAll(): array
    {
        $stmt = $this->db->prepare(
            'SELECT t.*, p.nombre_pub
               FROM titulos t
               INNER JOIN publicadores p ON p.id_pub = t.id_pub
              ORDER BY t.titulo ASC'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT t.*, p.nombre_pub
               FROM titulos t
               INNER JOIN publicadores p ON p.id_pub = t.id_pub
              WHERE t.id_titulo = :id'
        );
        $stmt->execute([':id' => $id]);
        $fila = $stmt->fetch();
        return $fila ?: null;
    }

    public function contar(): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM titulos');
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
            'INSERT INTO titulos (titulo, tipo, id_pub, precio, ventas_ytd, fecha_pub, notas)
             VALUES (:titulo, :tipo, :id_pub, :precio, :ventas, :fecha, :notas)'
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
            'UPDATE titulos
                SET titulo = :titulo, tipo = :tipo, id_pub = :id_pub, precio = :precio,
                    ventas_ytd = :ventas, fecha_pub = :fecha, notas = :notas
              WHERE id_titulo = :id'
        );
        return $stmt->execute($this->parametros() + [':id' => $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM titulos WHERE id_titulo = :id');
        return $stmt->execute([':id' => $id]);
    }

    private function parametros(): array
    {
        return [
            ':titulo' => $this->titulo,
            ':tipo'   => $this->tipo,
            ':id_pub' => $this->id_pub,
            ':precio' => number_format((float) $this->precio, 2, '.', ''),
            ':ventas' => $this->ventas_ytd,
            ':fecha'  => $this->fecha_pub ?: null,
            ':notas'  => $this->notas ?: null,
        ];
    }
}
