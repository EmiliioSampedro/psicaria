<?php
/**
 * uji-content — esquema de tablas (temarios, temas, árbol de nodos,
 * índice de citas, esquemas y glosario).
 *
 * Los artículos legales en sí (Constitución, reglamentos...) NO viven
 * aquí: cada norma tiene sus propias tablas ya creadas en el sitio
 * (p. ej. {prefix}constitucion_articulos, {prefix}constitucion_puntos).
 * uji_citas_indice solo apunta a ellas por código, no las duplica.
 */

defined( 'ABSPATH' ) || exit;

function uji_content_crear_tablas() {
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();
	$p               = $wpdb->prefix;

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	$sql = [];

	$sql[] = "CREATE TABLE {$p}uji_temarios (
		id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
		slug VARCHAR(100) NOT NULL,
		nombre VARCHAR(255) NOT NULL,
		descripcion TEXT DEFAULT NULL,
		created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY  (id),
		UNIQUE KEY slug (slug)
	) $charset_collate;";

	$sql[] = "CREATE TABLE {$p}uji_temas (
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
		PRIMARY KEY  (id),
		KEY temario_id (temario_id),
		UNIQUE KEY temario_numero (temario_id, numero)
	) $charset_collate;";

	// tipo: seccion -> epigrafe -> apartado -> subapartado (parent_id NULL = directamente bajo el tema)
	$sql[] = "CREATE TABLE {$p}uji_nodos (
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
		PRIMARY KEY  (id),
		KEY tema_id (tema_id),
		KEY parent_id (parent_id),
		KEY tipo (tipo)
	) $charset_collate;";

	// norma + articulo_codigo apuntan a las tablas propias de cada norma (no hay FK real: viven en tablas distintas).
	$sql[] = "CREATE TABLE {$p}uji_citas_indice (
		id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
		nodo_id BIGINT UNSIGNED NOT NULL,
		norma VARCHAR(10) NOT NULL,
		articulo_codigo VARCHAR(10) NOT NULL,
		punto_numero INT DEFAULT NULL,
		created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY  (id),
		KEY nodo_id (nodo_id),
		KEY norma_articulo (norma, articulo_codigo)
	) $charset_collate;";

	// nodo_id NULL = esquema del tema completo
	$sql[] = "CREATE TABLE {$p}uji_esquemas (
		id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
		tema_id BIGINT UNSIGNED NOT NULL,
		nodo_id BIGINT UNSIGNED DEFAULT NULL,
		contenido_html LONGTEXT NOT NULL,
		created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY  (id),
		KEY tema_id (tema_id),
		KEY nodo_id (nodo_id)
	) $charset_collate;";

	$sql[] = "CREATE TABLE {$p}uji_glosario (
		id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
		tema_id BIGINT UNSIGNED NOT NULL,
		termino VARCHAR(255) NOT NULL,
		definicion_html TEXT NOT NULL,
		orden INT NOT NULL DEFAULT 0,
		PRIMARY KEY  (id),
		KEY tema_id (tema_id)
	) $charset_collate;";

	foreach ( $sql as $create_table ) {
		dbDelta( $create_table );
	}
}
