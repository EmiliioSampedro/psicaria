-- uji-content — creación de tablas (equivalente en SQL puro a includes/schema.php)
-- Prefijo asumido: opouj_wp_ (el mismo que usan tus tablas de la Constitución).
-- Si tu prefijo es otro, busca y reemplaza "opouj_wp_" antes de ejecutar.

CREATE TABLE IF NOT EXISTS opouj_wp_uji_temarios (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  slug VARCHAR(100) NOT NULL,
  nombre VARCHAR(255) NOT NULL,
  descripcion TEXT DEFAULT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS opouj_wp_uji_temas (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  temario_id BIGINT UNSIGNED NOT NULL,
  numero SMALLINT UNSIGNED NOT NULL,
  titulo VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL,
  estado VARCHAR(20) NOT NULL DEFAULT 'borrador',
  fuente_pdf VARCHAR(255) DEFAULT NULL,
  orden INT NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY temario_id (temario_id),
  UNIQUE KEY temario_numero (temario_id, numero)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- tipo: seccion -> epigrafe -> apartado -> subapartado (parent_id NULL = directamente bajo el tema)
CREATE TABLE IF NOT EXISTS opouj_wp_uji_nodos (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  tema_id BIGINT UNSIGNED NOT NULL,
  parent_id BIGINT UNSIGNED DEFAULT NULL,
  tipo ENUM('seccion','epigrafe','apartado','subapartado') NOT NULL,
  numero VARCHAR(20) NOT NULL,
  letra VARCHAR(5) DEFAULT NULL,
  color_clase VARCHAR(10) DEFAULT NULL,
  titulo VARCHAR(255) DEFAULT NULL,
  contenido_html LONGTEXT DEFAULT NULL,
  orden INT NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY tema_id (tema_id),
  KEY parent_id (parent_id),
  KEY tipo (tipo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- norma + articulo_codigo apuntan a las tablas propias de cada norma (constitucion_articulos, etc.); no hay FK real.
CREATE TABLE IF NOT EXISTS opouj_wp_uji_citas_indice (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  nodo_id BIGINT UNSIGNED NOT NULL,
  norma VARCHAR(10) NOT NULL,
  articulo_codigo VARCHAR(10) NOT NULL,
  punto_numero INT DEFAULT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY nodo_id (nodo_id),
  KEY norma_articulo (norma, articulo_codigo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- nodo_id NULL = esquema del tema completo
CREATE TABLE IF NOT EXISTS opouj_wp_uji_esquemas (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  tema_id BIGINT UNSIGNED NOT NULL,
  nodo_id BIGINT UNSIGNED DEFAULT NULL,
  contenido_html LONGTEXT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY tema_id (tema_id),
  KEY nodo_id (nodo_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS opouj_wp_uji_glosario (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  tema_id BIGINT UNSIGNED NOT NULL,
  termino VARCHAR(255) NOT NULL,
  definicion_html TEXT NOT NULL,
  orden INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY tema_id (tema_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
