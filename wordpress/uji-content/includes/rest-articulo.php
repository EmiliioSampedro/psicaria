<?php
/**
 * uji-content — REST /wp-json/uji/v1/articulo
 *
 * Resuelve las citas del lector (art. X de una norma) contra las tablas
 * propias de cada norma. De momento solo "CE" (Constitución) tiene
 * tablas creadas; añade aquí el resto según se vayan creando.
 */

defined( 'ABSPATH' ) || exit;

function uji_content_tablas_por_norma() {
	return [
		'CE' => 'constitucion',
		// 'RC'   => 'reglamento_congreso',
		// 'RS'   => 'reglamento_senado',
		// 'EPCG' => 'estatuto_personal',
	];
}

add_action( 'rest_api_init', function () {
	register_rest_route( 'uji/v1', '/articulo', [
		'methods'             => 'GET',
		'callback'            => 'uji_content_rest_articulo',
		'permission_callback' => '__return_true',
		'args'                => [
			'norma' => [ 'required' => true ],
			'arts'  => [ 'required' => true ],
		],
	] );
} );

function uji_content_rest_articulo( WP_REST_Request $req ) {
	global $wpdb;

	$normas = uji_content_tablas_por_norma();
	$norma  = strtoupper( sanitize_text_field( $req->get_param( 'norma' ) ) );

	if ( ! isset( $normas[ $norma ] ) ) {
		return new WP_REST_Response( [], 200 ); // norma sin tablas todavía
	}

	$prefijo         = $wpdb->prefix . $normas[ $norma ] . '_';
	$tabla_articulos = $prefijo . 'articulos';
	$tabla_puntos    = $prefijo . 'puntos';

	$codigos   = array_filter( array_map( 'trim', explode( ',', (string) $req->get_param( 'arts' ) ) ) );
	$resultado = [];

	foreach ( $codigos as $clave ) {
		$partes = explode( '.', $clave, 2 );
		$base   = $partes[0];

		if ( array_key_exists( $base, $resultado ) ) {
			continue;
		}

		$articulo = $wpdb->get_row( $wpdb->prepare(
			"SELECT articulo_id, nombre, texto_completo, tiene_puntos
			 FROM {$tabla_articulos} WHERE codigo = %s",
			$base
		) );

		if ( ! $articulo ) {
			$resultado[ $base ] = null;
			continue;
		}

		if ( $articulo->tiene_puntos ) {
			$puntos = $wpdb->get_results( $wpdb->prepare(
				"SELECT numero AS n, texto
				 FROM {$tabla_puntos}
				 WHERE articulo_id = %d
				 ORDER BY orden IS NULL, orden, numero",
				$articulo->articulo_id
			) );
			$resultado[ $base ] = [
				'epigrafe'  => $articulo->nombre,
				'apartados' => $puntos,
			];
		} else {
			$resultado[ $base ] = [
				'epigrafe' => $articulo->nombre,
				'texto'    => $articulo->texto_completo,
			];
		}
	}

	return new WP_REST_Response( $resultado, 200 );
}
