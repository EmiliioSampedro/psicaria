<?php
/**
 * Plugin Name: UJI Content
 * Description: Tablas y API de contenido para el lector de temario (temas, secciones, epígrafes, apartados, esquemas, glosario, índice de citas legales).
 * Version:     0.1.0
 */

defined( 'ABSPATH' ) || exit;

define( 'UJI_CONTENT_VERSION', '0.1.0' );
define( 'UJI_CONTENT_URL', plugin_dir_url( __FILE__ ) );

require_once __DIR__ . '/includes/schema.php';
require_once __DIR__ . '/includes/rest-articulo.php';
require_once __DIR__ . '/includes/shortcode-lector.php';
require_once __DIR__ . '/seeds/tema-9.php';
require_once __DIR__ . '/seeds/tema-9-esquemas.php';

register_activation_hook( __FILE__, 'uji_content_crear_tablas' );
