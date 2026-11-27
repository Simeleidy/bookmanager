# BookManager

Sistema de gestión de librería (CRUD de **tiendas** y **títulos**) en PHP 8 orientado a objetos, PDO con sentencias preparadas, MySQL y Bootstrap 5.

## Ejecución local
1. Instala XAMPP o Laragon y copia la carpeta `bookmanager` en `htdocs` (o `www`).
2. En phpMyAdmin crea la base `dblibreria` (cotejamiento `utf8mb4_general_ci`) e importa `database/dblibreria.sql` (base de datos entregada por el maestro).
3. Copia `config/config.example.php` a `config/config.php` y ajusta las credenciales.
4. Abre `http://localhost/bookmanager/`.

## Arquitectura
- `config/database.php`: clase `Database` (conexión PDO).
- `models/`: clases `Tienda` y `Titulo` (propiedades privadas, validación y CRUD con sentencias preparadas).
- `views/`: vistas (listado, crear, editar, eliminar) por módulo.
- `includes/`: `header.php` (sidebar), `footer.php` y funciones auxiliares.
