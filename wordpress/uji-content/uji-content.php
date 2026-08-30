<?php
/**
 * Plugin Name: UJI Content
 * Description: Tablas y API de contenido para el lector de temario (temas, secciones, epígrafes, apartados, esquemas, glosario, índice de citas legales).
 * Version:     0.1.0
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/includes/schema.php';
require_once __DIR__ . '/includes/rest-articulo.php';

register_activation_hook( __FILE__, 'uji_content_crear_tablas' );
