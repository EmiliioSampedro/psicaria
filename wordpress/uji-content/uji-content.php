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

register_activation_hook( __FILE__, 'uji_content_activar' );

function uji_content_activar() {
	global $wpdb;

	uji_content_crear_tablas();

	// solo la primera vez: si el Tema 9 ya existe (porque ya se sembró antes,
	// o porque se ha editado su contenido a mano), no lo tocamos, para no
	// pisar ediciones manuales en cada reinstalación del plugin. Los esquemas
	// ya no se siembran aquí: se editan directamente en la tabla uji_esquemas.
	$temas_tbl = $wpdb->prefix . 'uji_temas';
	$ya_existe = $wpdb->get_var( "SELECT id FROM {$temas_tbl} WHERE numero = 9" );
	if ( ! $ya_existe ) {
		uji_content_seed_tema_9();
	}
}
