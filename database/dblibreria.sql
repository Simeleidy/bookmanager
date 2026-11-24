-- BookManager - esquema y datos de ejemplo (dblibreria)
-- En InfinityFree NO ejecutes CREATE DATABASE / USE: selecciona tu BD en phpMyAdmin e importa este archivo.

CREATE TABLE IF NOT EXISTS publicadores (
  id_pub INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre_pub VARCHAR(80) NOT NULL,
  ciudad VARCHAR(60) DEFAULT NULL,
  pais VARCHAR(60) DEFAULT NULL,
  PRIMARY KEY (id_pub)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS tiendas (
  id_tienda INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre_tienda VARCHAR(80) NOT NULL,
  direccion VARCHAR(120) DEFAULT NULL,
  ciudad VARCHAR(60) DEFAULT NULL,
  estado VARCHAR(60) DEFAULT NULL,
  codigo_postal VARCHAR(10) DEFAULT NULL,
  PRIMARY KEY (id_tienda)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS titulos (
  id_titulo INT UNSIGNED NOT NULL AUTO_INCREMENT,
  titulo VARCHAR(120) NOT NULL,
  tipo VARCHAR(30) NOT NULL DEFAULT 'General',
  id_pub INT UNSIGNED NOT NULL,
  precio DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  ventas_ytd INT UNSIGNED NOT NULL DEFAULT 0,
  fecha_pub DATE DEFAULT NULL,
  notas VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id_titulo),
  KEY idx_titulos_pub (id_pub),
  CONSTRAINT fk_titulos_pub FOREIGN KEY (id_pub) REFERENCES publicadores (id_pub)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO publicadores (nombre_pub, ciudad, pais) VALUES
('Editora Santillana', 'Santo Domingo', 'República Dominicana'),
('Alfaguara', 'Madrid', 'España'),
('Planeta', 'Barcelona', 'España'),
('Penguin Random House', 'Nueva York', 'Estados Unidos'),
('Editorial Norma', 'Bogotá', 'Colombia');

INSERT INTO tiendas (nombre_tienda, direccion, ciudad, estado, codigo_postal) VALUES
('Librería Cuesta', 'Av. Mella #301', 'Santo Domingo', 'Distrito Nacional', '10101'),
('Librería Thomén', 'Av. 27 de Febrero #50', 'Santo Domingo', 'Distrito Nacional', '10120'),
('Papelería y Libros del Cibao', 'Calle del Sol #12', 'Santiago', 'Santiago', '51000'),
('Libros del Este', 'Av. Libertad #8', 'San Pedro de Macorís', 'San Pedro de Macorís', '21000'),
('Rincón del Lector', 'Calle Duarte #77', 'La Vega', 'La Vega', '41000');

INSERT INTO titulos (titulo, tipo, id_pub, precio, ventas_ytd, fecha_pub, notas) VALUES
('Cien años de soledad', 'Novela', 2, 590.00, 1200, '1967-06-05', 'Clásico del realismo mágico'),
('El amor en los tiempos del cólera', 'Novela', 4, 520.00, 860, '1985-12-01', NULL),
('Over', 'Novela', 1, 450.00, 300, '2004-03-15', 'Autor dominicano'),
('Don Quijote de la Mancha', 'Clásico', 2, 700.00, 950, '1605-01-16', 'Edición conmemorativa'),
('Introducción a PHP y MySQL', 'Técnico', 3, 890.00, 410, '2021-08-20', 'Para estudiantes de informática'),
('Fundamentos de Bases de Datos', 'Técnico', 5, 1050.00, 275, '2019-02-10', NULL);
