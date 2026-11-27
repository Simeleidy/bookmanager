<?php
declare(strict_types=1);

/**
 * Modelo Titulo: encapsula los datos y la lógica de la tabla `titulos`
 * (con relación a `publicadores`). La clave primaria (id_titulo) es un código
 * de texto de hasta 6 caracteres. Consultas con sentencias preparadas.
 */
class Titulo
{
    public const TIPOS = ['business', 'mod_cook', 'popular_comp', 'psychology', 'trad_cook', 'UNDECIDED'];

    private PDO $db;

    private string $id_titulo = '';
    private string $titulo = '';
    private string $tipo = 'UNDECIDED';
    private string $id_pub = '';
    private string $precio = '';
    private string $avance = '';
    private string $total_ventas = '';
    private string $notas = '';
    private string $fecha_pub = '';
    private string $contrato = '0';
    private array $errores = [];

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getErrores(): array
    {
        return $this->errores;
    }

    private function cargar(array $d, ?string $idFijo = null): void
    {
        $this->id_titulo    = $idFijo ?? trim((string) ($d['id_titulo'] ?? ''));
        $this->titulo       = trim((string) ($d['titulo'] ?? ''));
        $this->tipo         = trim((string) ($d['tipo'] ?? ''));
        $this->id_pub       = trim((string) ($d['id_pub'] ?? ''));
        $this->precio       = str_replace(',', '.', trim((string) ($d['precio'] ?? '')));
        $this->avance       = str_replace(',', '.', trim((string) ($d['avance'] ?? '')));
        $this->total_ventas = trim((string) ($d['total_ventas'] ?? ''));
        $this->notas        = trim((string) ($d['notas'] ?? ''));
        $this->fecha_pub    = trim((string) ($d['fecha_pub'] ?? ''));
        $this->contrato     = trim((string) ($d['contrato'] ?? '0'));
    }

    private function validar(bool $esNuevo): bool
    {
        $this->errores = [];

        if ($esNuevo) {
            if ($this->id_titulo === '') {
                $this->errores['id_titulo'] = 'El código del título es obligatorio.';
            } elseif (!preg_match('/^[A-Za-z0-9]{1,6}$/', $this->id_titulo)) {
                $this->errores['id_titulo'] = 'El código debe tener de 1 a 6 letras o números.';
            } elseif ($this->existe($this->id_titulo)) {
                $this->errores['id_titulo'] = 'Ya existe un título con ese código.';
            }
        }
        if ($this->titulo === '') {
            $this->errores['titulo'] = 'El título no puede estar vacío.';
        } elseif (strlen($this->titulo) > 60) {
            $this->errores['titulo'] = 'El título no puede superar los 60 caracteres.';
        }
        if (!in_array($this->tipo, self::TIPOS, true)) {
            $this->errores['tipo'] = 'Selecciona un tipo válido.';
        }
        if ($this->id_pub === '' || !$this->existePublicador($this->id_pub)) {
            $this->errores['id_pub'] = 'Selecciona una editorial válida.';
        }
        $this->validarMonto('precio', $this->precio, 'El precio');
        $this->validarMonto('avance', $this->avance, 'El avance');
        if ($this->total_ventas !== '' && !ctype_digit($this->total_ventas)) {
            $this->errores['total_ventas'] = 'Las ventas deben ser un número entero no negativo.';
        }
        if (strlen($this->notas) > 255) {
            $this->errores['notas'] = 'Las notas no pueden superar los 255 caracteres.';
        }
        $f = DateTime::createFromFormat('Y-m-d', $this->fecha_pub);
        if ($this->fecha_pub === '' || !$f || $f->format('Y-m-d') !== $this->fecha_pub) {
            $this->errores['fecha_pub'] = 'Indica una fecha de publicación válida.';
        }
        if (!in_array($this->contrato, ['0', '1'], true)) {
            $this->errores['contrato'] = 'Selecciona una opción válida.';
        }
        return $this->errores === [];
    }

    /** Un monto es opcional (NULL en la BD), pero si se indica debe ser numérico y no negativo. */
    private function validarMonto(string $campo, string $valor, string $nombre): void
    {
        if ($valor === '') {
            return;
        }
        if (!is_numeric($valor)) {
            $this->errores[$campo] = "$nombre debe ser un número.";
        } elseif ((float) $valor < 0) {
            $this->errores[$campo] = "$nombre no puede ser negativo.";
        } elseif ((float) $valor > 9999999) {
            $this->errores[$campo] = "$nombre es demasiado alto.";
        }
    }

    /** Editoriales para el <select> del formulario. */
    public function getPublicadores(): array
    {
        $stmt = $this->db->prepare('SELECT id_pub, nombre_pub FROM publicadores ORDER BY nombre_pub ASC');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function existePublicador(string $id): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM publicadores WHERE id_pub = :id');
        $stmt->execute([':id' => $id]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function existe(string $id): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM titulos WHERE id_titulo = :id');
        $stmt->execute([':id' => $id]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function getAll(): array
    {
        $stmt = $this->db->prepare(
            'SELECT t.*, p.nombre_pub
               FROM titulos t
               LEFT JOIN publicadores p ON p.id_pub = t.id_pub
              ORDER BY t.titulo ASC'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById(string $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT t.*, p.nombre_pub
               FROM titulos t
               LEFT JOIN publicadores p ON p.id_pub = t.id_pub
              WHERE t.id_titulo = :id'
        );
        $stmt->execute([':id' => $id]);
        $fila = $stmt->fetch();
        if (!$fila) {
            return null;
        }
        $fila['fecha_pub'] = substr((string) $fila['fecha_pub'], 0, 10); // solo la fecha, para el formulario
        return $fila;
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
        if (!$this->validar(true)) {
            return false;
        }
        $stmt = $this->db->prepare(
            'INSERT INTO titulos (id_titulo, titulo, tipo, id_pub, precio, avance, total_ventas, notas, fecha_pub, contrato)
             VALUES (:id, :titulo, :tipo, :id_pub, :precio, :avance, :ventas, :notas, :fecha, :contrato)'
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
            'UPDATE titulos
                SET titulo = :titulo, tipo = :tipo, id_pub = :id_pub, precio = :precio, avance = :avance,
                    total_ventas = :ventas, notas = :notas, fecha_pub = :fecha, contrato = :contrato
              WHERE id_titulo = :id'
        );
        return $stmt->execute($this->parametros());
    }

    /** Elimina el título solo si no aparece en ventas, autores ni derechos. */
    public function delete(string $id): bool
    {
        $this->errores = [];
        if ($this->tieneRelaciones($id)) {
            $this->errores['general'] = 'No se puede eliminar el título: tiene ventas, autores o derechos asociados.';
            return false;
        }
        $stmt = $this->db->prepare('DELETE FROM titulos WHERE id_titulo = :id');
        return $stmt->execute([':id' => $id]);
    }

    private function tieneRelaciones(string $id): bool
    {
        // Nombres de tabla fijos (no provienen del usuario); el valor va como parámetro.
        foreach (['detalle_venta', 'titulo_autor', 'derechos'] as $tabla) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$tabla} WHERE id_titulo = :id");
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
            ':id'       => $this->id_titulo,
            ':titulo'   => $this->titulo,
            ':tipo'     => $this->tipo,
            ':id_pub'   => $this->id_pub,
            ':precio'   => $this->precio === '' ? null : (float) $this->precio,
            ':avance'   => $this->avance === '' ? null : (float) $this->avance,
            ':ventas'   => $this->total_ventas === '' ? null : (int) $this->total_ventas,
            ':notas'    => $this->notas,
            ':fecha'    => $this->fecha_pub . ' 00:00:00',
            ':contrato' => $this->contrato,
        ];
    }
}
