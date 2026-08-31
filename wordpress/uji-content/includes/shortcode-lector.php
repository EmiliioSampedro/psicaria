<?php
/**
 * uji-content — shortcode [uji_lector tema="9" temario="ujier-cortes-generales"]
 *
 * Pinta el lector de temario a partir de uji_temas / uji_nodos /
 * uji_esquemas / uji_glosario. El HTML resultante es el mismo que
 * consumen uji-lector.css / uji-articulos.js / uji-lector.js.
 */

defined( 'ABSPATH' ) || exit;

add_shortcode( 'uji_lector', 'uji_content_shortcode_lector' );

/**
 * Encolar el CSS/JS del lector en wp_enqueue_scripts (no dentro del propio
 * shortcode: para entonces wp_head ya se ha impreso y los estilos no
 * saldrían). Comprobamos si la entrada contiene el shortcode antes de
 * cargar nada, para no meter estos assets en páginas que no los usan.
 */
add_action( 'wp_enqueue_scripts', 'uji_content_maybe_enqueue_lector_assets' );

function uji_content_maybe_enqueue_lector_assets() {
	if ( is_singular() ) {
		$post = get_post();
		if ( $post && ( has_shortcode( $post->post_content, 'uji_lector' ) || has_shortcode( $post->post_content, 'uji_temas_indice' ) ) ) {
			uji_content_enqueue_lector_assets();
		}
	}
}

/**
 * Si el shortcode se coloca dentro de un bloque de párrafo (Gutenberg lo
 * envuelve en <p class="wp-block-paragraph">, wpautop hace lo mismo con la
 * clásica), el navegador se encuentra con <div> dentro de un <p> y cierra
 * la etiqueta antes de tiempo: eso deja huecos en blanco y trozos de HTML
 * sueltos. Quitamos ese <p> envolvente después de que el shortcode se haya
 * expandido, sea cual sea el editor que lo haya metido ahí.
 */
add_filter( 'the_content', 'uji_content_strip_wrapping_p', 20 );

function uji_content_strip_wrapping_p( $content ) {
	if ( false !== strpos( $content, 'uji-progreso' ) ) {
		$content = preg_replace(
			'#<p[^>]*>\s*(<div class="uji-progreso">.*?</script>)\s*</p>#s',
			'$1',
			$content
		);
	}
	if ( false !== strpos( $content, 'uji-temario-mini' ) ) {
		$content = preg_replace(
			'#<p[^>]*>\s*(<div class="uji-lector uji-lector--indice">.*?</nav>\s*</div>)\s*</p>#s',
			'$1',
			$content
		);
	}
	return $content;
}

function uji_content_shortcode_lector( $atts ) {
	global $wpdb;

	$atts = shortcode_atts( [
		'tema'    => '',
		'temario' => '',
	], $atts, 'uji_lector' );

	$temas_tbl = $wpdb->prefix . 'uji_temas';

	// una sola página del lector para todos los temas: si no se fija el
	// atributo, se elige por la URL (?tema=9), como hace [uji_temas_indice]
	if ( '' === $atts['tema'] && isset( $_GET['tema'] ) ) {
		$atts['tema'] = sanitize_text_field( wp_unslash( $_GET['tema'] ) );
	}

	// si aún así no hay tema (primera visita, sin nada en la URL todavía),
	// nunca dejar la página en blanco con un mensaje de error: se muestra
	// el primer tema publicado que exista (hoy el 9; cuando haya más temas
	// será el 1). uji-lector.js puede luego redirigir por encima de esto
	// si el visitante ya había leído otro tema antes (localStorage).
	if ( '' === $atts['tema'] ) {
		$atts['tema'] = $wpdb->get_var(
			"SELECT numero FROM {$temas_tbl} WHERE estado = 'publicado' ORDER BY orden, numero LIMIT 1"
		);
	}

	if ( '' === $atts['tema'] || null === $atts['tema'] ) {
		return '<p><em>Todavía no hay ningún tema publicado.</em></p>';
	}
	$nodos_tbl = $wpdb->prefix . 'uji_nodos';
	$esq_tbl   = $wpdb->prefix . 'uji_esquemas';
	$glo_tbl   = $wpdb->prefix . 'uji_glosario';

	if ( '' !== $atts['temario'] ) {
		$temarios_tbl = $wpdb->prefix . 'uji_temarios';
		$tema         = $wpdb->get_row( $wpdb->prepare(
			"SELECT t.* FROM {$temas_tbl} t
			 INNER JOIN {$temarios_tbl} tm ON tm.id = t.temario_id
			 WHERE tm.slug = %s AND t.numero = %d",
			$atts['temario'], (int) $atts['tema']
		) );
	} else {
		$tema = $wpdb->get_row( $wpdb->prepare(
			"SELECT * FROM {$temas_tbl} WHERE numero = %d ORDER BY id LIMIT 1",
			(int) $atts['tema']
		) );
	}

	if ( ! $tema ) {
		return '<p><em>Tema no encontrado.</em></p>';
	}

	$nodos_planos = $wpdb->get_results( $wpdb->prepare(
		"SELECT * FROM {$nodos_tbl} WHERE tema_id = %d ORDER BY orden, id",
		$tema->numero
	) );

	$por_id = [];
	foreach ( $nodos_planos as $n ) {
		$n->hijos       = [];
		$por_id[ $n->id ] = $n;
	}
	$raiz = [];
	foreach ( $nodos_planos as $n ) {
		if ( $n->parent_id && isset( $por_id[ $n->parent_id ] ) ) {
			$por_id[ $n->parent_id ]->hijos[] = $n;
		} else {
			$raiz[] = $n;
		}
	}

	$esquemas_filas = $wpdb->get_results( $wpdb->prepare(
		"SELECT nodo_id, contenido_html FROM {$esq_tbl} WHERE tema_id = %d",
		$tema->numero
	) );

	$esquemas     = []; // ref-visible => html
	$esquema_tema = '';
	foreach ( $esquemas_filas as $e ) {
		if ( null === $e->nodo_id ) {
			$esquema_tema = $e->contenido_html;
			continue;
		}
		if ( isset( $por_id[ $e->nodo_id ] ) ) {
			$ref               = uji_content_ref_nodo( $tema->numero, $por_id[ $e->nodo_id ], $por_id );
			$esquemas[ $ref ]  = $e->contenido_html;
		}
	}
	if ( '' !== $esquema_tema ) {
		$esquemas[ (string) $tema->numero ] = $esquema_tema;
	}

	$glosario = $wpdb->get_results( $wpdb->prepare(
		"SELECT termino, definicion_html FROM {$glo_tbl} WHERE tema_id = %d ORDER BY orden, id",
		$tema->numero
	) );

	uji_content_enqueue_lector_assets();

	ob_start();
	?>
	<div class="uji-progreso"><i id="uji-barra"></i></div>
	<div class="uji-lector">
		<aside class="uji-rail">
			<div class="uji-rail__tabs" role="tablist">
				<button type="button" class="uji-tab activo" data-panel="tema" role="tab" aria-selected="true">Tema</button>
				<button type="button" class="uji-tab" data-panel="esquema" role="tab" aria-selected="false">Esquema</button>
				<button type="button" class="uji-tab" data-panel="indice" role="tab" aria-selected="false">Índice</button>
			</div>
			<div class="uji-rail__cuerpo" hidden>
				<div class="uji-panel" data-panel="indice">
					<nav class="uji-indice" aria-label="Índice del tema">
						<?php echo uji_content_render_indice( $raiz, $tema->numero ); ?>
					</nav>
				</div>
				<div class="uji-panel" data-panel="esquema" hidden>
					<?php echo uji_content_render_secnav( $raiz, $tema->numero ); ?>
					<div class="uji-esq">
						<p class="uji-esq__cab"><span class="uji-esq__ambito">Esquema</span></p>
						<div class="uji-esq__cuerpo"></div>
					</div>
				</div>
				<div class="uji-herr">
					<button class="uji-btn" id="uji-menos" title="Reducir el texto">A−</button>
					<button class="uji-btn" id="uji-mas" title="Aumentar el texto">A+</button>
					<button class="uji-btn" id="uji-tema" title="Cambiar a modo oscuro">🌙 Oscuro</button>
					<button class="uji-btn" id="uji-imprimir">Imprimir</button>
				</div>
			</div>
		</aside>

		<div class="uji-cuerpo">
			<header class="uji-cuerpo__cab">
				<?php echo uji_content_render_temario_mini_nav( $tema->numero ); ?>
				<span class="uji-tema__eyebrow">Tema <?php echo esc_html( $tema->numero ); ?></span>
				<h1 class="uji-tema__titulo"><?php echo esc_html( $tema->titulo ); ?></h1>
				<div class="uji-cuerpo__tabs" role="tablist">
					<button type="button" class="uji-vista-tab activo" data-vista="tema" role="tab" aria-selected="true">Tema</button>
					<button type="button" class="uji-vista-tab" data-vista="esquema" role="tab" aria-selected="false">Esquema</button>
				</div>
			</header>
			<article class="uji-tema" data-vista-panel="tema">
				<?php foreach ( $raiz as $indice_seccion => $seccion ) : ?>
					<?php echo uji_content_render_seccion( $seccion, $indice_seccion + 1 ); ?>
				<?php endforeach; ?>

				<?php if ( $glosario ) : ?>
					<section class="uji-glosario">
						<h2 class="uji-sec__titulo">Glosario</h2>
						<dl class="uji-dl">
							<?php foreach ( $glosario as $g ) : ?>
								<div class="uji-term">
									<dt><?php echo esc_html( $g->termino ); ?></dt>
									<dd><?php echo $g->definicion_html; // contenido editorial, no de usuario ?></dd>
								</div>
							<?php endforeach; ?>
						</dl>
					</section>
				<?php endif; ?>
			</article>
			<div class="uji-esq-grande" data-vista-panel="esquema" hidden>
				<p class="uji-esq-grande__cab"><span class="uji-esq-grande__ambito">Esquema</span></p>
				<div class="uji-esq-grande__cuerpo"></div>
			</div>
			<button type="button" class="uji-arriba" id="uji-arriba" title="Ir arriba del todo" hidden>↑</button>
		</div>
	</div>

	<script>
		window.UJI_TEMA     = <?php echo wp_json_encode( (string) $tema->numero ); ?>;
		window.UJI_ESQUEMAS = <?php echo wp_json_encode( $esquemas ); ?>;
		window.UJI_CFG      = { endpoint: <?php echo wp_json_encode( rest_url( 'uji/v1/articulo' ) ); ?> };
	</script>
	<?php
	return ob_get_clean();
}

add_shortcode( 'uji_temas_indice', 'uji_content_shortcode_temas_indice' );

/**
 * [uji_temas_indice] — fila con el número de cada uno de los 17 temas del
 * temario, arriba del todo de la página. Los que ya tengan contenido
 * (fila en uji_temas, estado "publicado") enlazan a esta misma página con
 * ?tema=N; el resto se muestran apagados, sin enlace, a la espera de tener
 * contenido.
 */
function uji_content_shortcode_temas_indice( $atts ) {
	// [uji_lector] ya trae esta misma fila integrada en su propia cabecera;
	// si conviven en la misma página (caso normal) esta versión suelta se
	// queda callada para no duplicarla.
	$post = get_post();
	if ( $post && has_shortcode( $post->post_content, 'uji_lector' ) ) {
		return '';
	}

	$actual = isset( $_GET['tema'] ) ? (int) $_GET['tema'] : 0;

	ob_start();
	?>
	<div class="uji-lector uji-lector--indice">
		<?php echo uji_content_render_temario_mini_nav( $actual ); ?>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Fila con el número de cada uno de los 17 temas del temario -- la usan
 * tanto [uji_temas_indice] (suelta) como la cabecera de [uji_lector]
 * (integrada arriba del todo de la caja de contenido). Los que ya tengan
 * contenido (fila en uji_temas, estado "publicado") enlazan a esta misma
 * página con ?tema=N; el resto se muestran apagados, sin enlace.
 */
function uji_content_render_temario_mini_nav( $actual_numero ) {
	global $wpdb;

	$total_temas = 17;
	$temas_tbl   = $wpdb->prefix . 'uji_temas';

	$publicados = $wpdb->get_col(
		"SELECT numero FROM {$temas_tbl} WHERE estado = 'publicado'"
	);
	$publicados = array_map( 'intval', $publicados );
	$base_url   = get_permalink();

	ob_start();
	?>
	<nav class="uji-temario-mini" aria-label="Índice de temas">
		<?php for ( $n = 1; $n <= $total_temas; $n++ ) : ?>
			<?php if ( in_array( $n, $publicados, true ) ) : ?>
				<?php $clase = 'uji-temario-mini__n' . ( $n === (int) $actual_numero ? ' uji-temario-mini__n--actual' : '' ); ?>
				<a class="<?php echo esc_attr( $clase ); ?>" href="<?php echo esc_url( add_query_arg( 'tema', $n, $base_url ) ); ?>"><?php echo esc_html( $n ); ?></a>
			<?php else : ?>
				<span class="uji-temario-mini__n uji-temario-mini__n--pendiente" aria-disabled="true"><?php echo esc_html( $n ); ?></span>
			<?php endif; ?>
		<?php endfor; ?>
	</nav>
	<?php
	return ob_get_clean();
}

/**
 * Referencia de navegación de una sección, ej. "9.1".
 */
function uji_content_ref_seccion( $tema_numero, $seccion ) {
	return $tema_numero . '.' . $seccion->numero;
}

/**
 * Referencia de navegación de un epígrafe (o nivel inferior), ej. "9.1_2"
 * (o "9.1_2_a" con letra). El guion bajo es intencional: es lo que permite
 * a buscarEsquema() en uji-lector.js caer al esquema de la sección
 * (ref.split('_')[0]) cuando el propio epígrafe no tiene esquema específico.
 */
function uji_content_ref_hijo( $ref_seccion, $numero_seccion, $nodo ) {
	$resto  = $nodo->numero;
	$prefijo = $numero_seccion . '.';
	if ( strpos( $resto, $prefijo ) === 0 ) {
		$resto = substr( $resto, strlen( $prefijo ) );
	}
	$ref = $ref_seccion . '_' . $resto;
	if ( ! empty( $nodo->letra ) ) {
		$ref .= '_' . $nodo->letra;
	}
	return $ref;
}

/**
 * Ref de cualquier nodo del árbol, subiendo por sus padres hasta la sección.
 * Se usa para volcar uji_esquemas (que puede apuntar a un nodo a cualquier
 * profundidad) al mismo formato de ref que genera el índice lateral.
 */
function uji_content_ref_nodo( $tema_numero, $nodo, array $por_id ) {
	if ( 'seccion' === $nodo->tipo ) {
		return uji_content_ref_seccion( $tema_numero, $nodo );
	}

	$padre = ( ! empty( $nodo->parent_id ) && isset( $por_id[ $nodo->parent_id ] ) ) ? $por_id[ $nodo->parent_id ] : null;
	if ( ! $padre ) {
		return $tema_numero . '.' . $nodo->numero; // no debería ocurrir con un árbol bien formado
	}

	$ref_padre = uji_content_ref_nodo( $tema_numero, $padre, $por_id );

	if ( 'seccion' === $padre->tipo ) {
		return uji_content_ref_hijo( $ref_padre, $padre->numero, $nodo );
	}

	$ref = $ref_padre;
	if ( ! empty( $nodo->letra ) ) {
		$ref .= '_' . $nodo->letra;
	}
	return $ref;
}

function uji_content_anchor_id( $nodo ) {
	$base = 'n-' . str_replace( '.', '-', $nodo->numero );
	if ( ! empty( $nodo->letra ) ) {
		$base .= '-' . strtolower( $nodo->letra );
	}
	return $base;
}

/**
 * Fila de botones numéricos (uno por sección del tema), arriba del todo del
 * panel Esquema del lateral (solo visible ahí, con esa pestaña activa):
 * eligen a mano qué esquema de sección se muestra. No tienen relación con
 * el Índice ni con el contenido del tema.
 */
function uji_content_render_secnav( array $secciones, $tema_numero ) {
	$html = '<nav class="uji-secnav" aria-label="Esquemas por sección">';
	foreach ( $secciones as $seccion ) {
		$ref = uji_content_ref_seccion( $tema_numero, $seccion );
		$html .= '<button type="button" class="uji-secnav__n" data-ref="' . esc_attr( $ref ) . '" title="Esquema: ' . esc_attr( $seccion->titulo ) . '">' . esc_html( $seccion->numero ) . '</button>';
	}
	$html .= '</nav>';
	return $html;
}

/**
 * Índice lateral: solo baja hasta "epigrafe" (2 niveles). Apartados y
 * subapartados se leen en el cuerpo pero no aparecen en el árbol lateral.
 */
function uji_content_render_indice( array $secciones, $tema_numero ) {
	$html = '<ol>';
	foreach ( $secciones as $seccion ) {
		$ref        = uji_content_ref_seccion( $tema_numero, $seccion );
		$id         = uji_content_anchor_id( $seccion );
		$color_attr = $seccion->color_clase ? ' class="uji-sec--' . esc_attr( $seccion->color_clase ) . '"' : '';

		$html .= '<li' . $color_attr . '>';
		$html .= '<a href="#' . esc_attr( $id ) . '" data-ref="' . esc_attr( $ref ) . '" data-titulo="' . esc_attr( $seccion->titulo ) . '">';
		$html .= '<span class="uji-num">' . esc_html( $seccion->numero ) . '</span><span>' . esc_html( $seccion->titulo ) . '</span></a>';

		$epigrafes = [];
		foreach ( $seccion->hijos as $hijo ) {
			if ( 'epigrafe' === $hijo->tipo ) {
				$epigrafes[] = $hijo;
			}
		}

		if ( $epigrafes ) {
			$html .= '<ol>';
			foreach ( $epigrafes as $epigrafe ) {
				$ref_e = uji_content_ref_hijo( $ref, $seccion->numero, $epigrafe );
				$id_e  = uji_content_anchor_id( $epigrafe );
				$html .= '<li>';
				$html .= '<a href="#' . esc_attr( $id_e ) . '" data-ref="' . esc_attr( $ref_e ) . '" data-titulo="' . esc_attr( $epigrafe->titulo ) . '">';
				$html .= '<span class="uji-num">' . esc_html( $epigrafe->numero ) . '</span><span>' . esc_html( $epigrafe->titulo ) . '</span></a>';
				$html .= '</li>';
			}
			$html .= '</ol>';
		}
		$html .= '</li>';
	}
	$html .= '</ol>';
	return $html;
}

function uji_content_render_seccion( $seccion, $indice_visible ) {
	$id    = uji_content_anchor_id( $seccion );
	$color = $seccion->color_clase ? ' uji-sec--' . esc_attr( $seccion->color_clase ) : '';

	$html  = '<section class="uji-sec' . $color . '" id="' . esc_attr( $id ) . '">';
	$html .= '<header class="uji-sec__cab"><span class="uji-sec__n" aria-hidden="true">' . esc_html( $indice_visible ) . '</span>';
	$html .= '<h2 class="uji-sec__titulo">' . esc_html( $seccion->titulo ) . '</h2></header>';

	if ( ! empty( $seccion->contenido_html ) ) {
		// mismo envoltorio "tarjeta" que un epígrafe (uji-ap), pero sin
		// cabecera numerada -- si no, este texto (el de una sección sin
		// epígrafes propios) sale sin la caja ni la tipografía del resto.
		$html .= '<section class="uji-ap uji-ap--suelto">';
		$html .= '<div class="uji-ap__cuerpo">' . $seccion->contenido_html . '</div>';
		$html .= '</section>';
	}

	foreach ( $seccion->hijos as $hijo ) {
		$html .= uji_content_render_nodo( $hijo );
	}

	$html .= '</section>';
	return $html;
}

/**
 * Epígrafe -> caja "uji-ap" (numerada). Apartado/subapartado -> caja "uji-sub" (con letra si la tiene).
 * Recursivo: un apartado puede contener subapartados anidados.
 */
function uji_content_render_nodo( $nodo ) {
	$id = uji_content_anchor_id( $nodo );

	if ( 'epigrafe' === $nodo->tipo ) {
		$html  = '<section class="uji-ap" id="' . esc_attr( $id ) . '">';
		$html .= '<div class="uji-ap__cab">';
		$html .= '<div class="uji-ap__n"><span>' . esc_html( $nodo->numero ) . '</span></div>';
		$html .= '<h3 class="uji-ap__titulo">' . esc_html( $nodo->titulo ) . '</h3>';
		$html .= '</div>';
		$html .= '<div class="uji-ap__cuerpo">';
		if ( ! empty( $nodo->contenido_html ) ) {
			$html .= $nodo->contenido_html;
		}
		foreach ( $nodo->hijos as $hijo ) {
			$html .= uji_content_render_nodo( $hijo );
		}
		$html .= '</div></section>';
		return $html;
	}

	// apartado / subapartado
	$letra_visible = ! empty( $nodo->letra ) ? strtoupper( $nodo->letra ) : '';
	$html  = '<section class="uji-sub" id="' . esc_attr( $id ) . '">';
	$html .= '<h4 class="uji-sub__titulo">';
	if ( $letra_visible ) {
		$html .= '<span class="uji-sub__letra">' . esc_html( $letra_visible ) . '</span>';
	}
	$html .= esc_html( $nodo->titulo ) . '</h4>';
	if ( ! empty( $nodo->contenido_html ) ) {
		$html .= $nodo->contenido_html;
	}
	foreach ( $nodo->hijos as $hijo ) {
		$html .= uji_content_render_nodo( $hijo );
	}
	$html .= '</section>';
	return $html;
}

function uji_content_enqueue_lector_assets() {
	static $hecho = false;
	if ( $hecho ) {
		return;
	}
	$hecho = true;

	wp_enqueue_style( 'uji-lector', UJI_CONTENT_URL . 'assets/uji-lector.css', [], UJI_CONTENT_VERSION );
	wp_enqueue_script( 'uji-articulos', UJI_CONTENT_URL . 'assets/uji-articulos.js', [], UJI_CONTENT_VERSION, true );
	wp_enqueue_script( 'uji-lector', UJI_CONTENT_URL . 'assets/uji-lector.js', [ 'uji-articulos' ], UJI_CONTENT_VERSION, true );
}
