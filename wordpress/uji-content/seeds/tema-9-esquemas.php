<?php
/**
 * uji-content — semilla de esquemas del Tema 9.
 *
 * El esquema general del tema y el de la Sección 1 (Introducción) son
 * un resumen propio (no había hoja de cálculo para esa parte). Los de
 * las Secciones 2 a 5 están transcritos de las hojas de cálculo
 * aportadas (9Tipospersonal, 9Funcionarioscuerposfunciones,
 * 9Funcionariosingreso, 9Situacionesadmns).
 *
 * Uso: uji_content_seed_tema_9_esquemas() (o `wp uji seed-tema-9-esquemas`).
 * Requiere haber sembrado antes el árbol de nodos (uji_content_seed_tema_9()).
 */

defined( 'ABSPATH' ) || exit;

function uji_seed_esq_ul( array $items ) {
	if ( ! $items ) {
		return '';
	}
	$html = '<ul>';
	foreach ( $items as $i ) {
		$html .= '<li>' . $i . '</li>';
	}
	return $html . '</ul>';
}

function uji_content_tema_9_esquemas() {
	return [

		'tema' => implode( '', [
			'<h2>Tema 9 en síntesis</h2>',
			'<ul>',
			'<li>Fundamento: autonomía parlamentaria (<span class="uji-esq__ref">art. 72.1 CE</span>) → Estatuto del Personal de las Cortes Generales (2025).</li>',
			'<li>4 clases de personal: funcionario, eventual, adscrito de la AGE, laboral.</li>',
			'<li>7 Cuerpos funcionariales; el Cuerpo de Ujieres es el séptimo.</li>',
			'<li>Ingreso: mérito y capacidad. Oposición libre + turno restringido (25 %) + reserva discapacidad (10 %).</li>',
			'<li>5 situaciones administrativas: activo, servicios especiales, excedencia, expectativa de destino, suspensión.</li>',
			'</ul>',
		] ),

		'secciones' => [

			// ---------------------------------------------------- 1. Introducción (resumen propio)
			'1' => implode( '', [
				'<h2>1. Introducción</h2>',
				'<h3>Fundamento</h3>',
				'<ul>',
				'<li>Autonomía parlamentaria (<span class="uji-esq__ref">art. 72.1 CE</span>): normativa, presupuestaria y de personal.</li>',
				'<li>Administración parlamentaria propia, no integrada en la AGE ni dirigida por el Gobierno.</li>',
				'</ul>',
				'<h3>Estatuto vigente</h3>',
				'<table>',
				'<tr><th>Dato</th><th>Detalle</th></tr>',
				'<tr><td>Aprobación</td><td>Mesas Congreso + Senado, 20-11-2025</td></tr>',
				'<tr><td>Publicación</td><td>BOCG, 21-11-2025</td></tr>',
				'<tr><td>Entrada en vigor</td><td>Día siguiente (retribuciones: 1-1-2026)</td></tr>',
				'<tr><td>Deroga</td><td>Estatuto 2006 y modificaciones 2008 / 2009 / 2016</td></tr>',
				'<tr><td>Estructura</td><td>8 capítulos (arts. 1-86) + disposiciones</td></tr>',
				'</table>',
			] ),

			// ---------------------------------------------------- 2. Tipos de personal (9Tipospersonal.ods)
			'2' => implode( '', [
				'<h2>2. Tipos de personal de Cortes Generales</h2>',
				'<table>',
				'<tr><th>Tipo</th><th>Nombramiento</th><th>Retribución</th><th>Características</th></tr>',
				'<tr><td>Funcionarios</td><td>' .
					uji_seed_esq_ul( [ 'Nombramiento legal.', 'Incorporados con carácter permanente.', 'Relación estatutaria de servicios profesionales.' ] ) .
					'</td><td>' . uji_seed_esq_ul( [ 'Retribuidos con cargo al presupuesto de Cortes Generales.' ] ) .
					'</td><td>' . uji_seed_esq_ul( [ 'Prestan servicio en el Congreso, en el Senado o Junta Electoral Central.' ] ) .
					'</td></tr>',
				'<tr><td>Eventual</td><td>' .
					uji_seed_esq_ul( [
						'Nombrado y separado libremente por el Presidente de cada Cámara.',
						'A propuesta del órgano al que se encuentre adscrito.',
						'Cesará de modo automático cuando cese el titular del órgano al que sirve.',
					] ) .
					'</td><td>' . uji_seed_esq_ul( [ 'El presupuesto de cada Cámara determinará sus retribuciones.' ] ) .
					'</td><td>' . uji_seed_esq_ul( [
						'Asistencia directa a miembros de la Mesa y a otros parlamentarios.',
						'Los GP podrán contar con un número determinado por la Mesa de cada Cámara.',
						'De aplicación el régimen prescrito para los funcionarios en el EPCG.',
						'En ningún caso podrá ocupar puesto de trabajo ni funciones de los funcionarios.',
					] ) .
					'</td></tr>',
				'<tr><td>Cuerpos de la AGE</td><td>' .
					uji_seed_esq_ul( [ 'Dependerá del Presidente y el Secretario General de la Cámara donde esté.' ] ) .
					'</td><td></td><td>' . uji_seed_esq_ul( [
						'Las Cámaras podrán solicitar del Gobierno la adscripción de otro personal.',
						'Para las funciones de seguridad.',
						'Para aquellas funciones no atribuidas a los Cuerpos de Funcionarios de CG.',
					] ) .
					'</td></tr>',
				'<tr><td>Laboral</td><td>' .
					uji_seed_esq_ul( [
						'El personal contratado laboralmente lo será por cada Cámara.',
						'Para funciones no atribuidas en el EPCG a los Cuerpos de funcionarios de CG.',
					] ) .
					'</td><td>' . uji_seed_esq_ul( [ 'Estará retribuido de acuerdo con los créditos de los presupuestos de cada Cámara.' ] ) .
					'</td><td>' . uji_seed_esq_ul( [
						'Congreso y Senado podrán contratar el personal laboral necesario.',
						'En puestos de trabajo que prevean las respectivas plantillas orgánicas.',
					] ) .
					'</td></tr>',
				'</table>',
			] ),

			// ---------------------------------------------------- 3. Cuerpos y funciones (9Funcionarioscuerposfunciones.ods)
			'3' => implode( '', [
				'<h2>3. Funcionarios de las Cortes Generales</h2>',
				'<table>',
				'<tr><th>Cuerpo</th><th>Titulación exigida</th><th>Funciones</th></tr>',
				'<tr><td>Letrados</td><td>Licenciado en Derecho</td><td>' . uji_seed_esq_ul( [
					'Asesoramiento jurídico y técnico a Presidencia y Mesa, a Comisiones y sus órganos, a Subcomisiones y a Ponencias.',
					'Redacción, conforme a los acuerdos de dichos órganos, de resoluciones, informes y dictámenes, y levantamiento de las actas.',
					'Representación y defensa de las Cortes Generales ante los órganos jurisdiccionales y ante el TC. Dirección de la Administración Parlamentaria.',
				] ) . '</td></tr>',
				'<tr><td>Archiveros/Bibliotecarios</td><td>Licenciado en Filosofía y Letras</td><td>' . uji_seed_esq_ul( [
					'Organización y gestión de los recursos documentales y bibliográficos existentes en cada Cámara y su difusión a través de los órganos competentes.',
					'Cuidado y conservación del patrimonio documental y bibliográfico de las Cortes Generales.',
				] ) . '</td></tr>',
				'<tr><td>Asesores Facultativos</td><td>Licenciado en especialidad</td><td>' . uji_seed_esq_ul( [
					'Asistencia y asesoramiento, informe, estudio y propuesta de nivel superior en las materias propias de su especialidad.',
				] ) . '</td></tr>',
				'<tr><td>Redactores-Taquígrafos-Estenotipistas</td><td>Diplomado universitario</td><td>' . uji_seed_esq_ul( [
					'Reproducción íntegra de las intervenciones y sucesos que tengan lugar en las sesiones del Pleno y de las Comisiones de las Cámaras a las que asistan.',
					'Redacción del Diario de Sesiones.',
				] ) . '</td></tr>',
				'<tr><td>Técnico-Administrativo</td><td>Diplomado universitario</td><td>' . uji_seed_esq_ul( [
					'Gestión administrativa y parlamentaria, ejecución, inspección e impulso de los procedimientos, y estudio y propuesta de carácter administrativo.',
				] ) . '</td></tr>',
				'<tr><td>Administrativo</td><td>Bachiller *</td><td>' . uji_seed_esq_ul( [
					'Tareas administrativas de trámite y apoyo a las funciones de gestión, estudio y propuesta de carácter administrativo.',
					'Tratamiento de textos y otras aplicaciones informáticas relativas a la tramitación administrativa. Registro, clasificación, transcripción y archivo de documentos.',
					'Atención de secretarías.',
				] ) . '</td></tr>',
				'<tr><td>Ujieres</td><td>Graduado escolar *</td><td>' . uji_seed_esq_ul( [
					'Vigilancia, control de accesos y custodia en el interior de los edificios parlamentarios, y control de tránsito interno, orientación y acompañamiento de personas.',
					'Asistencia y auxilio durante la celebración de reuniones.',
					'Colaboración en actividades protocolarias.',
					'Trabajos de reproducción, traslado y distribución de documentos, objetos y otras análogas.',
				] ) . '</td></tr>',
				'</table>',
				'<p><em>* Titulaciones históricas de la hoja de resumen; el Estatuto 2025 exige Bachiller/equivalente y Graduado en ESO/equivalente respectivamente.</em></p>',
			] ),

			// ---------------------------------------------------- 4. Ingreso y cese (9Funcionariosingreso.ods)
			'4' => implode( '', [
				'<h2>4. Ingreso y cese de funcionarios</h2>',

				'<h3>Acceso a la condición de funcionario de CG</h3>',
				uji_seed_esq_ul( [
					'De acuerdo con los principios de mérito y capacidad.',
					'Discapacidad: principios de igualdad de oportunidades y compensación de desventajas.',
				] ),
				'<p>Para ser admitido:</p>',
				uji_seed_esq_ul( [
					'Nacionalidad española y mayor de edad.',
					'En posesión de la titulación exigida, o en condiciones de obtenerla.',
					'No hallarse inhabilitado para el ejercicio de funciones públicas por sentencia firme.',
					'No padecer enfermedad o discapacidad que impida el desempeño de funciones.',
					'Cumplir los requisitos que se establezcan en cada convocatoria.',
				] ),
				uji_seed_esq_ul( [
					'No podrán establecerse requisitos que supongan discriminación.',
					'Las CG facilitarán los medios materiales para la formación de aspirantes.',
				] ),

				'<h3>Ingreso en los Cuerpos de funcionarios</h3>',
				'<p>Todos con ocasión de vacante, mediante convocatoria pública, libre y oposición.</p>',
				uji_seed_esq_ul( [
					'Letrados: Licenciado en Derecho.',
					'Archiveros/Bibliotecarios: Licenciado en Filosofía y Letras.',
					'Asesores Facultativos: Licenciado en ciertas especialidades.',
					'Redactores, Taquígrafos y Estenotipistas: Diplomado universitario.',
					'Técnico-Administrativo: Diplomado universitario.',
					'Administrativo: Bachiller.',
					'Ujieres: Graduado en ESO.',
				] ),
				'<p>Corresponde a ambas Cámaras, en reunión conjunta, aprobar la oferta de empleo público.</p>',

				'<h3>Turno restringido y reserva de plazas</h3>',
				uji_seed_esq_ul( [
					'Turno restringido: 25 % de las plazas para funcionarios de CG. Si no es entero, con fracción ≥ 0,5 se redondea al alza, salvo convocatorias de menos de 3 plazas.',
					'Reserva: 10 % para discapacidad superior al 33 %. Mismo criterio de redondeo con 0,5.',
				] ),

				'<h3>Promoción interna</h3>',
				uji_seed_esq_ul( [
					'Para funcionarios de la escala inmediatamente inferior, con 4 años de servicio activo.',
					'Número de plazas fijado por las Mesas en reunión conjunta.',
					'Selección por oposición o por concurso-oposición, excluidas las materias de origen.',
					'Tendrán preferencia para los puestos de trabajo vacantes ofertados.',
				] ),

				'<h3>Formación y perfeccionamiento</h3>',
				uji_seed_esq_ul( [
					'Las CG organizarán y patrocinarán la asistencia a cursos de formación y perfeccionamiento.',
					'Existirá una unidad de formación en las Secretarías Generales.',
					'Los órganos de representación de personal podrán conceder permisos para estudios.',
				] ),

				'<h3>Adquisición de la condición de funcionario</h3>',
				uji_seed_esq_ul( [
					'Superación de las pruebas selectivas.',
					'Nombramiento por los Presidentes.',
					'Juramento o promesa.',
					'Toma de posesión (plazo: 1 mes).',
				] ),

				'<h3>Pérdida de la condición de funcionario</h3>',
				uji_seed_esq_ul( [
					'Renuncia.', 'Pérdida de nacionalidad.', 'Sanción disciplinaria de separación.',
					'Pena de inhabilitación.', 'Jubilación.',
				] ),

				'<h3>Jubilación</h3>',
				uji_seed_esq_ul( [ 'Forzosa.', 'Por incapacidad permanente.', 'Voluntaria.' ] ),
			] ),

			// ---------------------------------------------------- 5. Situaciones administrativas (9Situacionesadmns.ods)
			'5' => implode( '', [
				'<h2>5. Situaciones administrativas de los funcionarios de CG</h2>',
				'<table>',
				'<tr><th>Situación</th><th>Cuándo</th><th>Derechos</th></tr>',
				'<tr><td>Servicio activo</td><td>' . uji_seed_esq_ul( [
					'Ocupen un puesto de trabajo de los adscritos a funcionarios que figuren en las plantillas orgánicas.',
					'Les haya sido conferida una comisión de servicios de menos de 6 meses.',
				] ) . '</td><td>' . uji_seed_esq_ul( [
					'Plenitud de los derechos, deberes y responsabilidades inherentes a su condición.',
				] ) . '</td></tr>',
				'<tr><td>Servicios especiales</td><td>' . uji_seed_esq_ul( [
					'Sean autorizados para realizar una misión por período determinado superior a seis meses.',
					'Adquieran la condición de funcionarios al servicio de organismos internacionales o de carácter supranacional, o desarrollen su labor al servicio del Estado en el exterior.',
					'Accedan a cargos políticos o de confianza.',
					'Accedan a la condición de Diputado, Senador, miembro del Parlamento Europeo, del Parlamento o Asamblea de una CCAA, o cargo local remunerado.',
					'Accedan a la condición de Magistrado del TC, miembro del CGPJ, Presidente del Consejo de Estado, Consejero de Cuentas o Defensor del Pueblo.',
				] ) . '</td><td>' . uji_seed_esq_ul( [
					'Reserva de una plaza del puesto básico del Cuerpo al que perteneciesen.',
					'Si ocupaban puesto por concurso y pasa menos de un año desde su pase a especiales: derecho a la misma plaza si no está ocupada; si lo está, 75 % del complemento hasta obtener otra plaza igual, en un plazo de dos años.',
					'Se computa el tiempo a efectos de ascensos, antigüedad y derechos pasivos. Pueden participar en concursos de provisión de puestos.',
					'Dejarán de percibir las retribuciones que les correspondan como funcionarios de las Cortes Generales, salvo las de antigüedad.',
				] ) . '</td></tr>',
				'<tr><td>Excedencia voluntaria — por prestación de servicios</td><td>' . uji_seed_esq_ul( [
					'Pasen a situación de servicio activo en otros Cuerpos al servicio de las CG o de cualquier organismo público.',
					'No soliciten reingreso en 30 días tras Servicios Especiales.',
				] ) . '</td><td>' . uji_seed_esq_ul( [
					'No devengan derechos económicos, ni es computable el tiempo permanecido en tal situación a efectos de ascenso, antigüedad y derechos pasivos.',
				] ) . '</td></tr>',
				'<tr><td>Excedencia por interés particular</td><td>' . uji_seed_esq_ul( [
					'3 años de servicio activo.', 'Subordinada a la buena marcha del servicio.', 'No expedientados, ni con sanción pendiente de cumplir.',
				] ) . '</td><td></td></tr>',
				'<tr><td>Excedencia para el cuidado de hijos</td><td>' . uji_seed_esq_ul( [
					'No superior a tres años.', 'Para cada hijo.',
				] ) . '</td><td>' . uji_seed_esq_ul( [
					'Computable a efectos de ascensos, antigüedad y derechos pasivos.',
					'Reserva de plaza (1 año); después, puesto equivalente o 75 % del complemento.',
				] ) . '</td></tr>',
				'<tr><td>Excedencia para el cuidado de familiares</td><td>' . uji_seed_esq_ul( [
					'No superior a tres años.',
					'Familiar hasta segundo grado de consanguinidad o afinidad.',
					'Que por edad, accidente o enfermedad no pueda valerse por sí mismo.',
				] ) . '</td><td></td></tr>',
				'<tr><td>Expectativa de destino</td><td>' . uji_seed_esq_ul( [
					'Sea imposible obtener el reingreso al servicio activo tras excedencia o suspensión firme.',
				] ) . '</td><td>' . uji_seed_esq_ul( [
					'Percibir las retribuciones en concepto de sueldo y antigüedad, cómputo del tiempo a efectos pasivos y de antigüedad. A disposición de las CG.',
				] ) . '</td></tr>',
				'<tr><td>Suspensión de funciones</td><td>' . uji_seed_esq_ul( [
					'Privación temporal del ejercicio de sus funciones y de los derechos inherentes a su condición.',
					'Puede ser provisional o firme.',
					'Firme: en virtud de condena criminal o sanción disciplinaria.',
				] ) . '</td><td>' . uji_seed_esq_ul( [
					'Provisional: no mayor de 6 meses; derecho al 75 % del salario y antigüedad. Si hay paralización imputable o no comparece, pérdida de toda retribución. Si es absuelto, computa como servicio activo.',
					'Firme: no superior a 6 años.',
				] ) . '</td></tr>',
				'<tr><td>Reintegro al servicio activo</td><td>' . uji_seed_esq_ul( [
					'No tengan reservada una plaza.', 'Haya vacante.',
				] ) . '</td><td>' . uji_seed_esq_ul( [
					'Orden de preferencia: suspensos; excedencia por hijos/familiares; excedencia voluntaria por prestación de servicios; excedencia por interés particular.',
				] ) . '</td></tr>',
				'</table>',
			] ),
		],
	];
}

function uji_content_seed_tema_9_esquemas() {
	global $wpdb;

	$temas_tbl    = $wpdb->prefix . 'uji_temas';
	$temarios_tbl = $wpdb->prefix . 'uji_temarios';
	$nodos_tbl    = $wpdb->prefix . 'uji_nodos';
	$esq_tbl      = $wpdb->prefix . 'uji_esquemas';

	$numero_tema = 9; // uji_esquemas.tema_id guarda el número del tema (9), no el id interno de uji_temas

	$tema_id = $wpdb->get_var( $wpdb->prepare(
		"SELECT t.id FROM {$temas_tbl} t
		 INNER JOIN {$temarios_tbl} tm ON tm.id = t.temario_id
		 WHERE tm.slug = %s AND t.numero = %d",
		'ujier-cortes-generales', $numero_tema
	) );

	if ( ! $tema_id ) {
		return null; // hay que sembrar antes el tema y sus nodos
	}

	$wpdb->delete( $esq_tbl, [ 'tema_id' => $numero_tema ] );

	$datos = uji_content_tema_9_esquemas();

	$wpdb->insert( $esq_tbl, [
		'tema_id'        => $numero_tema,
		'nodo_id'        => null,
		'contenido_html' => $datos['tema'],
	] );

	foreach ( $datos['secciones'] as $numero => $html ) {
		$seccion_id = $wpdb->get_var( $wpdb->prepare(
			"SELECT id FROM {$nodos_tbl} WHERE tema_id = %d AND tipo = 'seccion' AND numero = %s",
			$numero_tema, (string) $numero
		) );
		if ( $seccion_id ) {
			$wpdb->insert( $esq_tbl, [
				'tema_id'        => $numero_tema,
				'nodo_id'        => $seccion_id,
				'contenido_html' => $html,
			] );
		}
	}

	return $tema_id;
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command( 'uji seed-tema-9-esquemas', static function () {
		$tema_id = uji_content_seed_tema_9_esquemas();
		if ( ! $tema_id ) {
			WP_CLI::error( 'Siembra primero el tema 9 con `wp uji seed-tema-9`.' );
		}
		WP_CLI::success( "Esquemas del Tema 9 sembrados (tema id {$tema_id})." );
	} );
}
