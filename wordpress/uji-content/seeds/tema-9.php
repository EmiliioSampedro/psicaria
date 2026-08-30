<?php
/**
 * uji-content — semilla de datos: Tema 9, "El personal de las Cortes
 * Generales (I)", extraído de Tema9.pdf.
 *
 * Uso: llamar a uji_content_seed_tema_9() una vez (p. ej. desde WP-CLI
 * con `wp eval "uji_content_seed_tema_9();"`, o desde una página de
 * administración temporal). Es idempotente: borra y vuelve a insertar
 * los nodos de este tema en cada ejecución, así que se puede repetir
 * sin duplicar datos.
 */

defined( 'ABSPATH' ) || exit;

// ---------------------------------------------------------------- helpers de marcado

function uji_seed_p( $texto ) {
	return '<p>' . $texto . '</p>';
}

function uji_seed_ul( array $items ) {
	$html = '<ul class="uji-lista">';
	foreach ( $items as $i ) {
		$html .= '<li>' . $i . '</li>';
	}
	return $html . '</ul>';
}

function uji_seed_ol( array $items, $tipo = null ) {
	$attr = $tipo ? ' type="' . esc_attr( $tipo ) . '"' : '';
	$html = '<ol class="uji-lista"' . $attr . '>';
	foreach ( $items as $i ) {
		$html .= '<li>' . $i . '</li>';
	}
	return $html . '</ol>';
}

function uji_seed_ref( $norma, $arts, $texto ) {
	return '<span class="uji-ref" data-norma="' . esc_attr( $norma ) . '" data-arts="' . esc_attr( $arts ) . '">' . esc_html( $texto ) . '</span>';
}

// ---------------------------------------------------------------- contenido del tema

function uji_content_tema_9_secciones() {
	$ref_ce = static function ( $arts, $texto ) {
		return uji_seed_ref( 'CE', $arts, $texto );
	};
	$ref_epcg = static function ( $arts, $texto ) {
		return uji_seed_ref( 'EPCG', $arts, $texto );
	};

	return [

		// ============================================================ SECCIÓN 1
		[
			'numero' => '1', 'titulo' => 'Introducción', 'color_clase' => 'c1',
			'epigrafes' => [
				[
					'numero' => '1.1', 'titulo' => 'Fundamentación constitucional: autonomía parlamentaria',
					'contenido' => implode( '', [
						uji_seed_p( 'El personal de las Cortes Generales se encuadra en la organización administrativa propia del Congreso de los Diputados y del Senado. Su régimen jurídico se fundamenta en la autonomía parlamentaria, que asegura la independencia de las Cámaras respecto de los demás poderes del Estado y permite el ejercicio efectivo de sus funciones constitucionales.' ),
						uji_seed_p( 'Las Cortes Generales representan al pueblo español, ejercen la potestad legislativa del Estado, aprueban los Presupuestos Generales del Estado y controlan la acción del Gobierno, conforme al ' . $ref_ce( '66', 'art. 66 CE' ) . '. Estas funciones requieren una Administración parlamentaria profesional, estable y especializada, capaz de proporcionar apoyo jurídico, técnico, documental, administrativo, económico, protocolario, operativo y de seguridad interna.' ),
						uji_seed_p( 'La norma constitucional central es el ' . $ref_ce( '72.1', 'art. 72.1 CE' ) . ', que reconoce que las Cámaras establecen sus propios Reglamentos, aprueban autónomamente sus presupuestos y, de común acuerdo, regulan el Estatuto del Personal de las Cortes Generales.' ),
						uji_seed_p( 'De este precepto se deriva una autonomía parlamentaria con tres manifestaciones principales:' ),
						uji_seed_ul( [
							'autonomía normativa, mediante la aprobación de los Reglamentos parlamentarios',
							'autonomía presupuestaria, mediante la aprobación autónoma de los presupuestos',
							'autonomía de personal, mediante la regulación conjunta del Estatuto del Personal',
						] ),
						uji_seed_p( 'Esta autonomía no implica que las Cámaras queden fuera del ordenamiento jurídico, sino que cuentan con un régimen propio de organización y de empleo público, sujeto a la Constitución y a las normas específicamente aplicables al ámbito parlamentario.' ),
					] ),
				],
				[
					'numero' => '1.2', 'titulo' => 'Singularidad del régimen jurídico del personal de las Cortes Generales',
					'contenido' => implode( '', [
						uji_seed_p( 'La Administración parlamentaria no forma parte de la Administración General del Estado ni está sometida a la dirección del Gobierno. Depende de las Presidencias, de las Mesas y de las Secretarías Generales de las Cámaras, de conformidad con las normas parlamentarias y con el Estatuto del Personal de las Cortes Generales.' ),
						uji_seed_p( 'La singularidad de esta Administración se explica por las características de la actividad parlamentaria:' ),
						uji_seed_ul( [
							'asistencia a órganos constitucionales',
							'elaboración y tramitación de iniciativas parlamentarias',
							'apoyo a los procedimientos legislativos',
							'asesoramiento jurídico y técnico',
							'organización de Plenos, Comisiones, Ponencias y otros órganos',
							'gestión documental y bibliográfica',
							'redacción y publicación del Diario de Sesiones',
							'administración presupuestaria y de personal',
							'funcionamiento material de las sedes parlamentarias',
							'control de accesos, orientación, custodia y apoyo a los trabajos de las Cámaras',
						] ),
						uji_seed_p( 'El personal de las Cortes Generales se rige prioritariamente por su Estatuto específico. El régimen general de la función pública estatal solo resulta aplicable cuando así lo prevea expresamente el Estatuto o de forma supletoria, siempre que sea compatible con la autonomía parlamentaria.' ),
					] ),
				],
				[
					'numero' => '1.3', 'titulo' => 'El Estatuto del Personal de las Cortes Generales vigente',
					'contenido' => implode( '', [
						uji_seed_p( 'La norma básica vigente es el Acuerdo de 20 de noviembre de 2025, adoptado por las Mesas del Congreso de los Diputados y del Senado en reunión conjunta, por el que se aprueba el Estatuto del Personal de las Cortes Generales.' ),
						uji_seed_p( 'Este Estatuto actualiza de forma integral el marco jurídico aplicable al personal parlamentario. Mantiene los principios estructurales tradicionales de profesionalidad, estabilidad, mérito, capacidad y especialización, pero incorpora medidas orientadas a modernizar la Administración parlamentaria.' ),
						uji_seed_p( 'Entre las novedades más relevantes destacan:' ),
						uji_seed_ul( [
							'el refuerzo de las medidas de igualdad en el acceso y permanencia',
							'la ampliación y actualización de permisos y medidas de conciliación',
							'el reconocimiento de nuevas modalidades de prestación de servicios',
							'la introducción expresa de la carrera horizontal',
							'una nueva regulación de la promoción interna',
							'la actualización de las situaciones administrativas',
							'la adaptación a la coexistencia de regímenes de Clases Pasivas y Seguridad Social',
							'el reforzamiento de la formación continua',
							'la regulación más detallada de derechos de representación, negociación y participación',
							'y la modernización de las reglas retributivas, de provisión y de carrera profesional',
						] ),
					] ),
				],
				[
					'numero' => '1.4', 'titulo' => 'Aprobación, entrada en vigor y derogación del régimen anterior',
					'contenido' => implode( '', [
						uji_seed_p( 'El Estatuto vigente fue aprobado por las Mesas del Congreso y del Senado en reunión conjunta de 20 de noviembre de 2025 y publicado en el Boletín Oficial de las Cortes Generales el 21 de noviembre de 2025.' ),
						uji_seed_p( 'Su entrada en vigor se produjo al día siguiente de su publicación. Sin embargo, las disposiciones relativas al régimen de retribuciones producen efectos desde el 1 de enero de 2026.' ),
						uji_seed_p( 'La disposición derogatoria del Estatuto deja sin efecto las normas de igual o inferior rango que se opongan a su contenido y deroga expresamente:' ),
						uji_seed_ul( [
							'el Estatuto del Personal de las Cortes Generales aprobado en 2006',
							'las modificaciones aprobadas en 2008 y 2009',
							'y las modificaciones relativas a titulaciones aprobadas en 2016',
						] ),
						uji_seed_p( 'Por ello, no resultan aplicables las referencias del Estatuto de 2006 cuando contradigan o hayan sido sustituidas por la regulación vigente de 2025. En especial, deben actualizarse las reglas sobre promoción interna, régimen de jubilación, permisos, carrera profesional, Cuerpo de Redactores, situaciones administrativas y representación del personal.' ),
					] ),
				],
				[
					'numero' => '1.5', 'titulo' => 'Estructura sistemática del Estatuto de 2025',
					'contenido' => implode( '', [
						uji_seed_p( 'El Estatuto se distribuye en ocho capítulos:' ),
						uji_seed_ol( [
							'Del personal de las Cortes Generales: arts. 1 a 6.',
							'Del personal funcionario de las Cortes Generales: arts. 7 y 8.',
							'Del ingreso y cese del personal funcionario: arts. 9 a 16.',
							'De las situaciones del personal funcionario: arts. 17 a 29.',
							'De los derechos del personal funcionario: arts. 30 a 64.',
							'De los deberes e incompatibilidades del personal funcionario: arts. 65 a 76.',
							'Del régimen disciplinario: arts. 77 a 84.',
							'De la eficacia e impugnación de las resoluciones adoptadas en materia de personal: arts. 85 y 86.',
						] ),
						uji_seed_p( 'El texto se completa con disposiciones adicionales, transitorias, derogatoria y final.' ),
						uji_seed_p( 'Este tema se centra en las materias propias de su primer bloque: clases de personal, organización, Cuerpos, ingreso y cese, y situaciones administrativas.' ),
					] ),
				],
			],
		],

		// ============================================================ SECCIÓN 2
		[
			'numero' => '2', 'titulo' => 'Personal de las Cortes Generales: clases y órganos competentes', 'color_clase' => 'c2',
			'epigrafes' => [
				[
					'numero' => '2.1', 'titulo' => 'Consideraciones generales',
					'contenido' => implode( '', [
						uji_seed_p( 'El Estatuto distingue cuatro categorías de personal:' ),
						uji_seed_ol( [
							'Personal funcionario de las Cortes Generales.',
							'Personal eventual.',
							'Personal perteneciente a Cuerpos de la Administración General del Estado adscrito a las Cámaras.',
							'Personal laboral.',
						] ),
						uji_seed_p( 'La categoría principal es la del personal funcionario de las Cortes Generales. Constituye el núcleo profesional permanente de la Administración parlamentaria y desarrolla las funciones que el Estatuto atribuye a los distintos Cuerpos.' ),
						uji_seed_p( 'Las demás modalidades responden a necesidades específicas: confianza y asistencia directa en el caso del personal eventual; seguridad o funciones no atribuidas a los Cuerpos de las Cortes en el caso del personal procedente de la Administración General del Estado; y funciones no reservadas estatutariamente a los Cuerpos funcionariales en el caso del personal laboral.' ),
					] ),
				],
				[
					'numero' => '2.2', 'titulo' => 'Personal funcionario de las Cortes Generales',
					'contenido' => implode( '', [
						uji_seed_p( 'El ' . $ref_epcg( '1', 'art. 1 del Estatuto' ) . ' define al personal funcionario de las Cortes Generales como aquel que, en virtud de nombramiento legal, se halle incorporado a las mismas con carácter permanente, mediante una relación estatutaria de servicios profesionales y retribuidos con cargo al presupuesto de aquellas.' ),
						uji_seed_p( 'Conforme al ' . $ref_epcg( '7.2', 'art. 7.2 del Estatuto' ) . ', el personal funcionario puede prestar servicio en:' ),
						uji_seed_ul( [
							'el Congreso de los Diputados',
							'el Senado',
							'los Servicios Comunes de las Cortes Generales',
							'la Junta Electoral Central',
						] ),
						uji_seed_p( 'La prestación se realiza mediante el desempeño de puestos incluidos en las correspondientes plantillas orgánicas.' ),
						uji_seed_p( 'De esta definición derivan cinco notas esenciales:' ),
					] ),
					'subapartados' => [
						[ 'letra' => 'a', 'titulo' => 'Nombramiento legal', 'contenido' => uji_seed_p( 'La incorporación se produce mediante nombramiento formal, previa superación del correspondiente proceso selectivo y cumplimiento de los requisitos estatutarios.' ) ],
						[ 'letra' => 'b', 'titulo' => 'Carácter permanente', 'contenido' => uji_seed_p( 'El vínculo funcionarial tiene vocación de estabilidad. No es una relación temporal ni una prestación ocasional vinculada a un cargo de confianza.' ) ],
						[ 'letra' => 'c', 'titulo' => 'Relación estatutaria', 'contenido' => uji_seed_p( 'La relación de servicio se rige por normas de Derecho público, especialmente por el Estatuto del Personal de las Cortes Generales y por sus disposiciones de desarrollo.' ) ],
						[ 'letra' => 'd', 'titulo' => 'Profesionalidad', 'contenido' => uji_seed_p( 'El personal funcionario desempeña funciones profesionales dirigidas al apoyo, organización y continuidad de las actividades parlamentarias y administrativas de las Cámaras.' ) ],
						[ 'letra' => 'e', 'titulo' => 'Retribución presupuestaria', 'contenido' => uji_seed_p( 'Las retribuciones se financian con cargo a los presupuestos propios de las Cortes Generales, en coherencia con su autonomía presupuestaria.' ) ],
					],
				],
				[
					'numero' => '2.3', 'titulo' => 'Personal eventual',
					'contenido' => implode( '', [
						uji_seed_p( 'El personal eventual se regula en el ' . $ref_epcg( '2', 'art. 2 del Estatuto' ) . '. Su función es la asistencia directa y de confianza a las personas miembros de las Mesas y a otros parlamentarios o parlamentarias que aquellas determinen.' ),
						uji_seed_p( 'Los Grupos Parlamentarios pueden contar con personal eventual dentro del número que determine la Mesa de cada Cámara.' ),
						uji_seed_p( 'Sus notas principales son las siguientes:' ),
					] ),
					'subapartados' => [
						[ 'letra' => 'a', 'titulo' => 'Asistencia directa y de confianza', 'contenido' => uji_seed_p( 'Su función no es la de ocupar puestos estructurales de la Administración parlamentaria, sino prestar apoyo de carácter fiduciario y directo a los órganos o parlamentarios a los que se encuentre adscrito.' ) ],
						[ 'letra' => 'b', 'titulo' => 'Libre nombramiento y libre separación', 'contenido' => uji_seed_p( 'Es nombrado y separado libremente por la Presidencia de cada Cámara, a propuesta de la persona titular del órgano de adscripción.' ) ],
						[ 'letra' => 'c', 'titulo' => 'Cese automático', 'contenido' => implode( '', [
							uji_seed_p( 'Cesa automáticamente cuando cesa la persona titular del órgano al que sirve. Se trata de una consecuencia lógica del carácter de confianza del vínculo.' ),
							uji_seed_p( 'No obstante, durante los períodos de disolución de las Cámaras, las Mesas pueden adoptar las medidas provisionales que estimen oportunas.' ),
						] ) ],
						[ 'letra' => 'd', 'titulo' => 'Aplicación limitada del Estatuto', 'contenido' => uji_seed_p( 'Al personal eventual se le aplica el régimen general previsto para el personal funcionario únicamente cuando no se oponga a la naturaleza de sus funciones.' ) ],
						[ 'letra' => 'e', 'titulo' => 'Prohibición de ejercer funciones propias del personal funcionario', 'contenido' => uji_seed_p( 'No puede ocupar puestos de trabajo ni desempeñar funciones propias del personal funcionario de las Cortes Generales. Esta regla garantiza que las funciones estructurales y profesionales de la Administración parlamentaria sean desarrolladas por personal seleccionado conforme a los principios de igualdad, mérito y capacidad.' ) ],
						[ 'letra' => 'f', 'titulo' => 'Retribuciones', 'contenido' => uji_seed_p( 'Las retribuciones se determinan en el presupuesto de cada Cámara.' ) ],
					],
				],
				[
					'numero' => '2.4', 'titulo' => 'Personal perteneciente a Cuerpos de la Administración General del Estado',
					'contenido' => implode( '', [
						uji_seed_p( 'El ' . $ref_epcg( '3', 'art. 3 del Estatuto' ) . ' prevé que las Cámaras puedan solicitar del Gobierno la adscripción de personal perteneciente a Cuerpos de la Administración General del Estado.' ),
						uji_seed_p( 'La adscripción se puede solicitar para:' ),
						uji_seed_ul( [
							'el desempeño de funciones de seguridad',
							'y el desarrollo de otras funciones no atribuidas estatutariamente a los Cuerpos de personal funcionario de las Cortes Generales',
						] ),
						uji_seed_p( 'Este personal conserva su pertenencia a los Cuerpos de origen y se mantiene en situación de servicio activo en ellos. Sin embargo, mientras presta servicio en las Cortes Generales, depende de la Presidencia y de la Secretaría General de la Cámara en la que se encuentre destinado.' ),
						uji_seed_p( 'La disposición adicional primera añade que este personal se rige por la legislación de funcionarios civiles del Estado. No obstante, queda sometido jerárquicamente a la Presidencia y a la Secretaría General de la Cámara respecto del desarrollo de sus funciones, y puede percibir incentivos con cargo al presupuesto de la Cámara a la que esté adscrito.' ),
					] ),
				],
				[
					'numero' => '2.5', 'titulo' => 'Personal laboral',
					'contenido' => implode( '', [
						uji_seed_p( 'El ' . $ref_epcg( '4', 'art. 4' ) . ' permite al Congreso y al Senado contratar personal laboral para funciones que no estén atribuidas estatutariamente a los Cuerpos de personal funcionario de las Cortes Generales.' ),
						uji_seed_p( 'La contratación laboral debe ajustarse a dos exigencias:' ),
						uji_seed_ul( [
							'los puestos deben estar previstos como laborales en las plantillas orgánicas',
							'las funciones no pueden estar reservadas a los Cuerpos funcionariales',
						] ),
						uji_seed_p( 'El personal laboral es contratado por cada Cámara y se retribuye con cargo a los créditos presupuestarios correspondientes.' ),
						uji_seed_p( 'La selección se determina por las Mesas de cada Cámara y ha de respetar necesariamente los principios de:' ),
						uji_seed_ul( [ 'publicidad', 'igualdad', 'mérito', 'capacidad' ] ),
					] ),
				],
				[
					'numero' => '2.6', 'titulo' => 'Órganos competentes en materia de personal',
					'contenido' => implode( '', [
						uji_seed_p( 'El ' . $ref_epcg( '5', 'art. 5' ) . ' atribuye las competencias de personal a:' ),
						uji_seed_ul( [
							'las Presidencias del Congreso y del Senado',
							'las Mesas de las Cámaras, actuando conjunta o separadamente',
							'los órganos competentes de las Secretarías Generales',
							'y la Junta de Personal, en los supuestos y con el alcance previstos en el Estatuto',
						] ),
						uji_seed_p( 'La distribución competencial se corresponde con la estructura bicameral de las Cortes Generales. Hay materias propias de cada Cámara y otras que, por afectar al régimen común, a los Servicios Comunes o a la unidad del Estatuto, requieren la actuación conjunta de las Mesas.' ),
						uji_seed_p( 'Entre las competencias de las Mesas reunidas conjuntamente destacan, por ejemplo:' ),
						uji_seed_ul( [
							'la aprobación y modificación del Estatuto',
							'la aprobación de la oferta de empleo público y de los planes de empleo',
							'la aprobación de determinados elementos retributivos',
							'la aprobación de las plantillas de los Servicios Comunes y de la Junta Electoral Central',
							'la aprobación del desarrollo normativo del Estatuto',
							'la resolución de determinados recursos',
							'y la ratificación de los acuerdos alcanzados en la Mesa Negociadora',
						] ),
					] ),
				],
				[
					'numero' => '2.7', 'titulo' => 'Secretarías Generales, Letrado o Letrada Mayor y órganos adjuntos',
					'contenido' => uji_seed_p( 'El ' . $ref_epcg( '6', 'art. 6' ) . ' regula las figuras superiores de la Administración parlamentaria.' ),
					'subapartados' => [
						[ 'letra' => 'a', 'titulo' => 'Secretaría General del Congreso y Letrado o Letrada Mayor del Senado', 'contenido' => implode( '', [
							uji_seed_p( 'La persona titular de la Secretaría General del Congreso y la persona titular de la Letrada o Letrado Mayor del Senado son nombradas o, en su caso, ratificadas por la Mesa de cada Cámara, a propuesta de la Presidencia respectiva, en cada legislatura o cuando exista vacante.' ),
							uji_seed_p( 'Deben ser miembros del Cuerpo de Letrados de las Cortes Generales con más de cinco años de servicio activo.' ),
						] ) ],
						[ 'letra' => 'b', 'titulo' => 'Adjuntos', 'contenido' => implode( '', [
							uji_seed_p( 'Los Secretarios o Secretarias Generales Adjuntos del Congreso y los Letrados o Letradas Mayores Adjuntos del Senado son nombrados por las Mesas respectivas, a propuesta de la Secretaría General del Congreso o de la Letrada o Letrado Mayor del Senado.' ),
							uji_seed_p( 'Han de pertenecer al Cuerpo de Letrados de las Cortes Generales.' ),
						] ) ],
						[ 'letra' => 'c', 'titulo' => 'Cese', 'contenido' => implode( '', [
							uji_seed_p( 'Cesan por:' ),
							uji_seed_ul( [
								'renuncia',
								'decisión del órgano que realizó el nombramiento',
								'pérdida de la condición de personal funcionario',
								'pase a una situación administrativa distinta de la de servicio activo',
								'imposibilidad para el desempeño del cargo',
							] ),
						] ) ],
						[ 'letra' => 'd', 'titulo' => 'Letrado o Letrada Mayor de las Cortes Generales', 'contenido' => implode( '', [
							uji_seed_p( 'Con carácter ordinario, la persona titular de la Secretaría General del Congreso ostenta el cargo de Letrado o Letrada Mayor de las Cortes Generales.' ),
							uji_seed_p( 'Sin embargo, las Mesas de ambas Cámaras, en reunión conjunta, pueden decidir que este cargo se provea independientemente entre miembros del Cuerpo de Letrados con más de cinco años de servicio activo.' ),
							uji_seed_p( 'Estas figuras concentran funciones de dirección superior, interpretación normativa, asesoramiento y gestión de la Administración parlamentaria.' ),
						] ) ],
					],
				],
			],
		],

		// ============================================================ SECCIÓN 3
		[
			'numero' => '3', 'titulo' => 'Personal funcionario: Cuerpos y funciones', 'color_clase' => 'c3',
			'epigrafes' => [
				[
					'numero' => '3.1', 'titulo' => 'Cuerpos de personal funcionario de las Cortes Generales',
					'contenido' => implode( '', [
						uji_seed_p( 'El ' . $ref_epcg( '7', 'art. 7 del Estatuto' ) . ' enumera los siguientes Cuerpos:' ),
						uji_seed_ol( [
							'Cuerpo de Letrados de las Cortes Generales.',
							'Cuerpo de Archiveros-Bibliotecarios de las Cortes Generales.',
							'Cuerpo de Asesores Facultativos de las Cortes Generales.',
							'Cuerpo de Redactores de las Cortes Generales.',
							'Cuerpo Técnico-Administrativo de las Cortes Generales.',
							'Cuerpo Administrativo de las Cortes Generales.',
							'Cuerpo de Ujieres de las Cortes Generales.',
						] ),
						uji_seed_p( 'La adscripción a un Cuerpo determina el ámbito general de funciones que puede desempeñar cada funcionario o funcionaria, sin perjuicio de las funciones específicas asignadas a cada puesto en las plantillas orgánicas.' ),
					] ),
				],
				[
					'numero' => '3.2', 'titulo' => 'Cuerpo de Letrados',
					'contenido' => implode( '', [
						uji_seed_p( 'El Cuerpo de Letrados desarrolla funciones de asesoramiento jurídico, técnico y parlamentario de máximo nivel.' ),
						uji_seed_p( 'Le corresponde:' ),
						uji_seed_ul( [
							'asesorar jurídicamente y técnicamente a las Presidencias, Mesas y Juntas de Portavoces',
							'asistir a Comisiones, Subcomisiones, Ponencias y otros órganos parlamentarios',
							'redactar resoluciones, informes y dictámenes conforme a los acuerdos de los órganos',
							'levantar las actas correspondientes',
							'asumir la representación y defensa de las Cortes Generales, Congreso, Senado, Junta Electoral Central y órganos vinculados o dependientes ante los órganos jurisdiccionales y el Tribunal Constitucional',
							'asesorar en relaciones internacionales e institucionales',
							'realizar funciones de estudio y propuesta de nivel superior',
							'dirigir la Administración parlamentaria y asumir la titularidad de sus órganos de dirección',
						] ),
						uji_seed_p( 'La función del Cuerpo de Letrados es esencial para asegurar la regularidad jurídica de la actividad parlamentaria y administrativa.' ),
					] ),
				],
				[
					'numero' => '3.3', 'titulo' => 'Cuerpo de Archiveros-Bibliotecarios',
					'contenido' => implode( '', [
						uji_seed_p( 'El Cuerpo de Archiveros-Bibliotecarios se ocupa de la organización y gestión de la información, documentación y patrimonio bibliográfico de las Cámaras.' ),
						uji_seed_p( 'Le corresponde:' ),
						uji_seed_ul( [
							'organizar y gestionar recursos documentales y bibliográficos',
							'tratar y analizar documentos',
							'difundir los recursos a través de los órganos competentes',
							'cuidar, conservar y preservar el patrimonio documental y bibliográfico de las Cortes Generales',
							'realizar tareas de asistencia, asesoramiento, informe, estudio y propuesta de nivel superior',
							'ejercer la jefatura de los servicios correspondientes',
							'y desarrollar funciones directivas en materias de su especialidad, según las plantillas orgánicas',
						] ),
					] ),
				],
				[
					'numero' => '3.4', 'titulo' => 'Cuerpo de Asesores Facultativos',
					'contenido' => implode( '', [
						uji_seed_p( 'El Cuerpo de Asesores Facultativos desempeña las funciones superiores de asistencia, asesoramiento, informe, estudio y propuesta dentro de las materias propias de su especialidad.' ),
						uji_seed_p( 'También puede ejercer:' ),
						uji_seed_ul( [
							'jefaturas de los servicios correspondientes',
							'funciones de dirección en materias propias de cada especialidad, conforme a las plantillas orgánicas',
						] ),
						uji_seed_p( 'La especialización de este Cuerpo permite cubrir necesidades técnicas de las Cámaras en ámbitos como economía, comunicación, ingeniería, arquitectura, informática, sociología u otras disciplinas.' ),
					] ),
				],
				[
					'numero' => '3.5', 'titulo' => 'Cuerpo de Redactores',
					'contenido' => implode( '', [
						uji_seed_p( 'El Estatuto vigente denomina a este Cuerpo Cuerpo de Redactores de las Cortes Generales, sustituyendo la anterior denominación de Cuerpo de Redactores Taquígrafos y Estenotipistas.' ),
						uji_seed_p( 'Sus funciones son:' ),
						uji_seed_ul( [
							'asistir a la reproducción íntegra de las intervenciones y hechos ocurridos en Plenos, Comisiones, Diputaciones Permanentes y, en su caso, otros órganos de las Cámaras',
							'redactar, editar y publicar el Diario de Sesiones',
							'ejercer la jefatura de los servicios correspondientes en los términos previstos en las plantillas orgánicas',
						] ),
						uji_seed_p( 'Este Cuerpo garantiza la documentación oficial y la publicidad de la actividad parlamentaria.' ),
					] ),
				],
				[
					'numero' => '3.6', 'titulo' => 'Cuerpo Técnico-Administrativo',
					'contenido' => implode( '', [
						uji_seed_p( 'El Cuerpo Técnico-Administrativo asume las funciones de:' ),
						uji_seed_ul( [
							'gestión administrativa',
							'gestión económico-presupuestaria',
							'gestión parlamentaria',
							'ejecución, ordenación, inspección e impulso de procedimientos',
							'estudio y propuesta de carácter técnico-administrativo',
							'jefatura de servicios conforme a las plantillas orgánicas',
						] ),
						uji_seed_p( 'Se sitúa en un nivel de gestión técnica y de impulso de la actividad administrativa y parlamentaria de las Cortes.' ),
					] ),
				],
				[
					'numero' => '3.7', 'titulo' => 'Cuerpo Administrativo',
					'contenido' => implode( '', [
						uji_seed_p( 'El Cuerpo Administrativo desempeña:' ),
						uji_seed_ul( [
							'tareas administrativas de trámite',
							'apoyo a funciones de gestión, ejecución, estudio y propuesta administrativa',
							'tramitación administrativa, parlamentaria y económico-presupuestaria',
							'utilización de aplicaciones informáticas de gestión',
							'registro, clasificación, transcripción y archivo de documentos',
							'atención y responsabilidad de secretarías',
							'jefatura de unidades administrativas determinadas en las plantillas orgánicas',
						] ),
					] ),
				],
				[
					'numero' => '3.8', 'titulo' => 'Cuerpo de Ujieres',
					'contenido' => implode( '', [
						uji_seed_p( 'El Cuerpo de Ujieres realiza funciones vinculadas al funcionamiento material, operativo y protocolario de las sedes parlamentarias.' ),
						uji_seed_p( 'Le corresponde:' ),
						uji_seed_ul( [
							'vigilancia, control de accesos y custodia en el interior de los edificios parlamentarios',
							'control del tránsito interno',
							'orientación y acompañamiento de personas',
							'asistencia y auxilio durante las reuniones de los órganos parlamentarios',
							'colaboración en actividades protocolarias',
							'reproducción, traslado y distribución de documentos, objetos y otros elementos análogos',
							'apoyo a las unidades administrativas en los servicios especiales de destino',
							'jefatura de unidades en los términos fijados por las plantillas orgánicas',
						] ),
						uji_seed_p( 'Estas funciones se desempeñan sin perjuicio de las propias del personal de seguridad adscrito desde la Administración General del Estado conforme al ' . $ref_epcg( '3', 'art. 3 del Estatuto' ) . '.' ),
					] ),
				],
			],
		],

		// ============================================================ SECCIÓN 4
		[
			'numero' => '4', 'titulo' => 'Ingreso, adquisición, pérdida y jubilación', 'color_clase' => 'c4',
			'epigrafes' => [
				[
					'numero' => '4.1', 'titulo' => 'Principios rectores de acceso',
					'contenido' => implode( '', [
						uji_seed_p( 'El acceso a la condición de personal funcionario de las Cortes Generales se rige por los principios de:' ),
						uji_seed_ul( [ 'igualdad', 'mérito', 'capacidad' ] ),
						uji_seed_p( 'El Estatuto incorpora expresamente el principio de igualdad de oportunidades y compensación de desventajas en el acceso de las personas con discapacidad, con la posible adaptación de las bases de las convocatorias.' ),
						uji_seed_p( 'El acceso ha de realizarse con pleno respeto a la igualdad y sin discriminaciones por razones personales o sociales.' ),
					] ),
				],
				[
					'numero' => '4.2', 'titulo' => 'Requisitos de admisión a las pruebas selectivas',
					'contenido' => implode( '', [
						uji_seed_p( 'Para ser admitido a las pruebas selectivas se requiere:' ),
						uji_seed_ol( [
							'Poseer nacionalidad española y ser mayor de edad.',
							'Estar en posesión de la titulación exigida, o en condiciones de obtenerla dentro del plazo de presentación de solicitudes.',
							'No estar inhabilitado para el ejercicio de las funciones públicas por sentencia firme.',
							'Reunir las condiciones psicofísicas necesarias para el desempeño de las funciones.',
							'Cumplir los requisitos establecidos en cada convocatoria.',
						], 'a' ),
						uji_seed_p( 'Las convocatorias no pueden establecer requisitos discriminatorios por razón de raza, sexo, religión, opinión, lugar de nacimiento, vecindad o cualquier otra circunstancia personal o social. Tampoco pueden incluir preguntas sobre ideología, religión o creencias de las personas aspirantes.' ),
					] ),
				],
				[
					'numero' => '4.3', 'titulo' => 'Ingreso en los Cuerpos de las Cortes Generales',
					'contenido' => implode( '', [
						uji_seed_p( 'El ingreso se realiza mediante convocatoria pública, libre y oposición.' ),
						uji_seed_p( 'La convocatoria se efectúa en ejecución de:' ),
						uji_seed_ul( [ 'la oferta de empleo público anual', 'sus actualizaciones', 'y los planes de empleo plurianuales' ] ),
						uji_seed_p( 'La aprobación de dichos instrumentos corresponde a las Mesas de ambas Cámaras en reunión conjunta, previo cumplimiento de las reglas de negociación colectiva previstas estatutariamente.' ),
						uji_seed_p( 'La oferta de empleo público debe garantizar una adecuada dotación de los Cuerpos para asegurar el correcto funcionamiento de las Secretarías Generales y facilitar la carrera administrativa.' ),
						uji_seed_p( 'Las titulaciones exigidas son:' ),
						uji_seed_ul( [
							'Letrados: Licenciatura o Grado en Derecho.',
							'Archiveros-Bibliotecarios: Licenciatura o Grado en Artes, Humanidades o Ciencias Sociales y Jurídicas.',
							'Asesores Facultativos: titulación superior correspondiente a la especialidad exigida por cada convocatoria.',
							'Redactores: Diplomatura universitaria o Grado.',
							'Técnico-Administrativo: Diplomatura universitaria o Grado.',
							'Administrativo: Bachiller o equivalente.',
							'Ujieres: Graduado en Educación Secundaria Obligatoria o equivalente.',
						] ),
					] ),
				],
				[
					'numero' => '4.4', 'titulo' => 'Turno restringido y reserva de plazas',
					'contenido' => implode( '', [
						uji_seed_p( 'En cada convocatoria se reserva un 25 % de las plazas para su provisión mediante turno restringido entre personal de otros Cuerpos de las Cortes Generales que posea la titulación exigida.' ),
						uji_seed_p( 'Si el porcentaje produce una fracción igual o superior a 0,5, se incrementa en una unidad el número de plazas reservadas, salvo que la convocatoria tenga menos de tres plazas, caso en el que todas corresponden al turno libre.' ),
						uji_seed_p( 'Las plazas de turno restringido que no se cubran se incorporan al turno libre.' ),
						uji_seed_p( 'Existe además una reserva del 10 % de las plazas para personas con discapacidad de grado igual o superior al 33 %, siempre que reúnan los requisitos de la convocatoria, superen las pruebas y acrediten la compatibilidad con las funciones.' ),
						uji_seed_p( 'Cuando la aplicación del porcentaje sea inferior a una plaza, se reserva una plaza si la convocatoria incluye al menos tres plazas.' ),
					] ),
				],
				[
					'numero' => '4.5', 'titulo' => 'Promoción interna',
					'contenido' => implode( '', [
						uji_seed_p( 'El Estatuto de 2025 refuerza la promoción interna.' ),
						uji_seed_p( 'Antes de cada convocatoria de oposición se realizará una convocatoria de plazas reservadas para promoción interna cuando la oferta anual correspondiente al Cuerpo incluya diez o más plazas. Excepcionalmente, puede reservarse promoción interna cuando el número sea inferior.' ),
						uji_seed_p( 'Pueden participar funcionarios de Cuerpos inmediatamente inferiores que:' ),
						uji_seed_ul( [ 'cuenten con al menos dos años de servicio activo en el Cuerpo de origen', 'y posean la titulación exigida' ] ),
						uji_seed_p( 'Las Mesas de las Cámaras concretan en cada convocatoria:' ),
						uji_seed_ul( [ 'el número de plazas', 'los Cuerpos que pueden participar', 'y las condiciones aplicables' ] ),
						uji_seed_p( 'El procedimiento puede ser de oposición o de concurso-oposición. Se excluyen del temario las materias cuyo conocimiento ya haya quedado acreditado con el ingreso en el Cuerpo de origen.' ),
						uji_seed_p( 'Quienes accedan por promoción interna tienen preferencia para cubrir las vacantes ofertadas respecto de quienes accedan por turno libre.' ),
					] ),
				],
				[
					'numero' => '4.6', 'titulo' => 'Formación y perfeccionamiento',
					'contenido' => implode( '', [
						uji_seed_p( 'Las Cortes Generales organizan y patrocinan cursos de formación y perfeccionamiento para facilitar la promoción del personal y mejorar la prestación de los servicios.' ),
						uji_seed_p( 'En las Secretarías Generales existe una unidad de formación encargada de ejecutar la política formativa de las Cámaras.' ),
						uji_seed_p( 'La Junta de Personal participa en los planes de formación en los términos establecidos por el Estatuto.' ),
						uji_seed_p( 'También pueden concederse permisos para realizar estudios directamente vinculados con la función pública parlamentaria. Requieren:' ),
						uji_seed_ul( [ 'informe del superior jerárquico', 'autorización de la Secretaría General correspondiente' ] ),
						uji_seed_p( 'Durante un plazo máximo de un año, el personal autorizado puede percibir el 50 % del sueldo y de las retribuciones por antigüedad.' ),
					] ),
				],
				[
					'numero' => '4.7', 'titulo' => 'Adquisición de la condición de personal funcionario',
					'contenido' => implode( '', [
						uji_seed_p( 'La condición se adquiere por el cumplimiento sucesivo de estos requisitos:' ),
						uji_seed_ol( [
							'Superación de las pruebas selectivas.',
							'Nombramiento conjunto por las Presidencias del Congreso y del Senado.',
							'Juramento o promesa de acatamiento a la Constitución, obediencia a las leyes y ejercicio imparcial de las funciones.',
							'Toma de posesión dentro del plazo de un mes desde la notificación del nombramiento.',
						] ),
					] ),
				],
				[
					'numero' => '4.8', 'titulo' => 'Pérdida de la condición de personal funcionario',
					'contenido' => implode( '', [
						uji_seed_p( 'La condición de personal funcionario se pierde por:' ),
						uji_seed_ol( [
							'Renuncia, que no impide un nuevo ingreso en la función pública.',
							'Pérdida de la nacionalidad española.',
							'Sanción disciplinaria de separación del servicio.',
							'Pena principal o accesoria de inhabilitación absoluta o especial para cargos públicos.',
							'Jubilación forzosa o voluntaria.',
						], 'a' ),
						uji_seed_p( 'En caso de pérdida de la condición funcionarial por pérdida de nacionalidad, la recuperación de esta permite solicitar la rehabilitación ante el Letrado o Letrada Mayor de las Cortes Generales.' ),
						uji_seed_p( 'También puede acordarse excepcionalmente la rehabilitación de quien hubiera perdido la condición funcionarial por condena de inhabilitación, atendiendo a las circunstancias y a la naturaleza y entidad del delito.' ),
					] ),
				],
				[
					'numero' => '4.9', 'titulo' => 'Jubilación',
					'contenido' => uji_seed_p( 'La jubilación se regula en el ' . $ref_epcg( '16', 'art. 16 del Estatuto' ) . ' vigente.' ),
					'subapartados' => [
						[ 'letra' => 'a', 'titulo' => 'Jubilación forzosa', 'contenido' => implode( '', [
							uji_seed_p( 'La jubilación forzosa se declara de oficio al cumplirse la edad legalmente establecida según el régimen de Seguridad Social aplicable.' ),
							uji_seed_p( 'Para el personal incluido en el régimen de Clases Pasivas, la jubilación forzosa se produce al cumplir 65 años.' ),
						] ) ],
						[ 'letra' => 'b', 'titulo' => 'Prolongación voluntaria', 'contenido' => implode( '', [
							uji_seed_p( 'El personal puede prolongar voluntariamente su permanencia en servicio activo hasta los 70 años, mediante escrito dirigido al Letrado o Letrada Mayor de las Cortes Generales y con una antelación mínima de dos meses respecto de la fecha de jubilación forzosa.' ),
							uji_seed_p( 'Una vez solicitada, la prórroga puede ser renunciada con tres meses de antelación a la fecha prevista de jubilación.' ),
						] ) ],
						[ 'letra' => 'c', 'titulo' => 'Prolongación hasta los 72 años', 'contenido' => implode( '', [
							uji_seed_p( 'La permanencia puede prolongarse hasta los 72 años cuando se cumpla una de estas condiciones:' ),
							uji_seed_ul( [
								'quince años de servicio activo en las Cortes Generales, de los cuales los cinco últimos sean inmediatamente anteriores a la solicitud',
								'o veinticinco años de servicio activo total',
							] ),
						] ) ],
						[ 'letra' => 'd', 'titulo' => 'Prórroga excepcional hasta los 75 años', 'contenido' => implode( '', [
							uji_seed_p( 'Excepcionalmente, puede autorizarse una prórroga anual hasta los 75 años. La autorización corresponde a la Secretaría General del Congreso o a la Letrada o Letrado Mayor del Senado.' ),
							uji_seed_p( 'Requiere:' ),
							uji_seed_ul( [
								'solicitud del interesado',
								'informe médico favorable sobre condiciones psicofísicas',
								'informe favorable del superior jerárquico',
								'resolución motivada',
							] ),
							uji_seed_p( 'La decisión debe atender a la trayectoria profesional, méritos, servicios que pueda prestar la persona, razones organizativas y funcionales y, especialmente, a necesidades de mentoría.' ),
						] ) ],
						[ 'letra' => 'e', 'titulo' => 'Limitación relativa a puestos con complemento de destino', 'contenido' => implode( '', [
							uji_seed_p( 'No se puede ocupar un puesto con complemento de destino desde los 67 años, salvo si aún no se ha consolidado el porcentaje máximo previsto, y sin superar en ningún caso los 70 años.' ),
							uji_seed_p( 'Las Secretarías Generales deben adscribir al personal afectado a una plaza básica, atendiendo a su experiencia y a las necesidades del servicio.' ),
							uji_seed_p( 'La disposición transitoria novena difiere la efectividad de esta regla durante cuatro años desde la entrada en vigor del Estatuto, con las particularidades que establece para quienes no hayan consolidado el complemento.' ),
						] ) ],
						[ 'letra' => 'f', 'titulo' => 'Jubilación por incapacidad y jubilación voluntaria', 'contenido' => implode( '', [
							uji_seed_p( 'También procede la jubilación por incapacidad permanente para ejercer las funciones propias del Cuerpo. Exige expediente, iniciado de oficio o a instancia de la persona interesada, con audiencia.' ),
							uji_seed_p( 'En el régimen de Clases Pasivas, la jubilación voluntaria puede solicitarse al cumplir 60 años o al reunir 35 años de servicios efectivos en las Cortes Generales o en otros entes públicos, sin perjuicio de las reglas aplicables para el personal sometido al régimen de Seguridad Social.' ),
						] ) ],
					],
				],
			],
		],

		// ============================================================ SECCIÓN 5
		[
			'numero' => '5', 'titulo' => 'Situaciones administrativas', 'color_clase' => 'c5',
			'epigrafes' => [
				[
					'numero' => '5.1', 'titulo' => 'Enumeración y finalidad',
					'contenido' => implode( '', [
						uji_seed_p( 'El personal funcionario de las Cortes Generales puede encontrarse en las siguientes situaciones administrativas:' ),
						uji_seed_ol( [
							'Servicio activo.', 'Servicios especiales.', 'Excedencia.', 'Expectativa de destino.', 'Suspensión de funciones.',
						], 'a' ),
						uji_seed_p( 'Las situaciones administrativas permiten adaptar el vínculo funcionarial a circunstancias de servicio público, acceso a cargos, cuidado familiar, incompatibilidades, protección frente a violencia, imposibilidad temporal de servicio o ejercicio de derechos.' ),
					] ),
				],
				[
					'numero' => '5.2', 'titulo' => 'Servicio activo',
					'contenido' => implode( '', [
						uji_seed_p( 'Se encuentra en servicio activo el personal funcionario:' ),
						uji_seed_ul( [
							'que ocupa un puesto adscrito a personal funcionario en las plantillas del Congreso, Senado, Servicios Comunes o Junta Electoral Central',
							'que desempeña una comisión de servicios de duración no superior a seis meses en organismos internacionales, entidades públicas, Gobiernos extranjeros, programas de cooperación, órganos constitucionales, Parlamentos o Asambleas Legislativas autonómicas',
							'o que accede a un cargo electivo en corporaciones locales conforme al régimen local, salvo que el cargo sea retribuido o de dedicación exclusiva',
						] ),
						uji_seed_p( 'El personal en servicio activo mantiene la plenitud de derechos, deberes y responsabilidades inherentes a su condición.' ),
					] ),
				],
				[
					'numero' => '5.3', 'titulo' => 'Servicios especiales',
					'contenido' => implode( '', [
						uji_seed_p( 'El pase a servicios especiales se produce, entre otros supuestos, cuando el personal funcionario:' ),
						uji_seed_ul( [
							'realiza una misión superior a seis meses en organismos internacionales, Gobiernos, entidades extranjeras, programas de cooperación, órganos constitucionales o Parlamentos',
							'presta servicio en organizaciones internacionales o supranacionales',
							'desarrolla su labor al servicio del Estado en el exterior',
							'accede a cargos políticos o de confianza',
							'es nombrado personal eventual del Congreso o del Senado',
							'adquiere la condición de diputado, senador, miembro del Parlamento Europeo o de una Asamblea Legislativa autonómica',
							'desempeña un cargo electivo local retribuido y de dedicación exclusiva',
							'accede a cargos constitucionales o institucionales expresamente enumerados por el Estatuto',
							'o cuando una ley determine expresamente el pase a esa situación',
						] ),
						uji_seed_p( 'El personal en servicios especiales tiene derecho a la reserva de una plaza básica del Cuerpo de pertenencia.' ),
						uji_seed_p( 'El tiempo en servicios especiales se computa para ascensos, antigüedad y derechos en Seguridad Social o Clases Pasivas. Puede participar en concursos de provisión de puestos en los términos estatutariamente establecidos.' ),
						uji_seed_p( 'No percibe las retribuciones ordinarias de las Cortes Generales, salvo las que correspondan por antigüedad.' ),
					] ),
				],
				[
					'numero' => '5.4', 'titulo' => 'Excedencia: régimen general',
					'contenido' => implode( '', [
						uji_seed_p( 'La excedencia puede declararse por:' ),
						uji_seed_ul( [
							'interés particular', 'prestación de servicios en el sector público', 'agrupación familiar',
							'cuidado de hijos, hijas y familiares', 'violencia de género o sexual', 'violencia terrorista',
						] ),
						uji_seed_p( 'La excedencia requiere, con carácter general, petición de la persona interesada y se concede por la Secretaría General del Congreso o por la Letrada o Letrado Mayor del Senado, según la Cámara de destino, salvo la excepción prevista para la excedencia por interés particular derivada de falta de solicitud de reingreso.' ),
					] ),
				],
				[
					'numero' => '5.5', 'titulo' => 'Excedencia por interés particular',
					'contenido' => implode( '', [
						uji_seed_p( 'Puede concederse al personal que haya completado tres años de servicio activo desde su ingreso en las Cortes Generales.' ),
						uji_seed_p( 'La concesión queda subordinada a la buena marcha del servicio y no puede otorgarse a quien tenga un expediente disciplinario en curso o no haya cumplido una sanción anterior.' ),
						uji_seed_p( 'También se produce esta situación cuando, desaparecida la causa que justificó el pase a una situación distinta del servicio activo, no se solicita el reingreso en treinta días.' ),
						uji_seed_p( 'Durante esta excedencia:' ),
						uji_seed_ul( [
							'no se devengan retribuciones',
							'no se computa el tiempo para ascensos',
							'no se computa a efectos de antigüedad',
							'no se computa a efectos de derechos de Seguridad Social o Clases Pasivas',
						] ),
					] ),
				],
				[
					'numero' => '5.6', 'titulo' => 'Excedencia por prestación de servicios en el sector público',
					'contenido' => implode( '', [
						uji_seed_p( 'Se declara cuando el personal funcionario pasa a servicio activo en otros Cuerpos de las Cortes Generales o en cualquier organismo público.' ),
						uji_seed_p( 'También se aplica si el personal funcionario pasa a prestar servicios como personal laboral del Congreso, Senado o cualquier entidad u organismo del sector público.' ),
						uji_seed_p( 'No procede en los casos de docencia, investigación u otros supuestos legalmente exceptuados que cuenten con autorización de compatibilidad y no determinen servicios especiales.' ),
						uji_seed_p( 'Sus efectos son los mismos que los de la excedencia por interés particular.' ),
					] ),
				],
				[
					'numero' => '5.7', 'titulo' => 'Excedencia por agrupación familiar',
					'contenido' => implode( '', [
						uji_seed_p( 'Puede concederse sin necesidad de acreditar tres años previos de servicio activo cuando el cónyuge o pareja de hecho resida en otra localidad por haber obtenido un puesto definitivo como funcionario de carrera o personal laboral fijo.' ),
						uji_seed_p( 'El puesto puede estar en cualquier Administración Pública, organismo público, entidad de derecho público, órgano constitucional, órgano jurisdiccional, institución análoga autonómica, Unión Europea u organización internacional.' ),
						uji_seed_p( 'Durante esta excedencia no se perciben retribuciones ni se computa el tiempo para ascensos, antigüedad o derechos de Seguridad Social o Clases Pasivas.' ),
					] ),
				],
				[
					'numero' => '5.8', 'titulo' => 'Excedencia para el cuidado de hijos, hijas y familiares',
					'contenido' => implode( '', [
						uji_seed_p( 'El personal funcionario tiene derecho a una excedencia de duración no superior a tres años para el cuidado de cada hijo o hija, por nacimiento, adopción, guarda con fines de adopción o acogimiento, temporal o permanente en los términos establecidos.' ),
						uji_seed_p( 'También puede solicitarse para atender al cónyuge, pareja de hecho registrada o familiar hasta el segundo grado de consanguinidad o afinidad que, por edad, accidente, enfermedad o discapacidad, no pueda valerse por sí mismo y no desempeñe actividad retribuida.' ),
						uji_seed_p( 'Es un derecho individual. Si dos personas funcionarias generan el derecho por el mismo sujeto causante, la Administración parlamentaria puede limitar el disfrute simultáneo por razones justificadas de funcionamiento del servicio.' ),
						uji_seed_p( 'La excedencia puede disfrutarse fraccionadamente, por períodos mínimos de tres meses, atendiendo a las necesidades del servicio.' ),
						uji_seed_p( 'Durante esta situación:' ),
						uji_seed_ul( [
							'se computa el tiempo a efectos de antigüedad, ascensos y derechos de Seguridad Social o Clases Pasivas',
							'se puede participar en concursos de provisión',
							'se puede participar en cursos de formación',
							'se reserva el puesto de trabajo básico o específico obtenido por concurso durante un máximo de tres años',
						] ),
					] ),
				],
				[
					'numero' => '5.9', 'titulo' => 'Excedencia por violencia de género o sexual y por violencia terrorista',
					'contenido' => implode( '', [
						uji_seed_p( 'Las funcionarias víctimas de violencia de género o sexual pueden solicitar esta excedencia sin necesidad de servicios previos mínimos y sin plazo mínimo de permanencia.' ),
						uji_seed_p( 'Durante los dos primeros años tienen derecho a:' ),
						uji_seed_ul( [ 'reserva del puesto', 'cómputo de antigüedad', 'cómputo para ascensos', 'cómputo de derechos en Seguridad Social o Clases Pasivas' ] ),
						uji_seed_p( 'Este período puede prorrogarse por períodos sucesivos de seis meses, hasta un máximo de dieciocho meses, si las actuaciones judiciales lo exigen.' ),
						uji_seed_p( 'Durante los cuatro primeros meses se mantienen las retribuciones íntegras y puede accederse a prestaciones del Fondo de Prestaciones Sociales de las Cortes Generales.' ),
						uji_seed_p( 'El personal que haya sufrido daños, amenazas o coacciones por terrorismo puede acceder a una excedencia en condiciones equivalentes, mientras resulte necesaria para garantizar su protección o asistencia social integral.' ),
					] ),
				],
				[
					'numero' => '5.10', 'titulo' => 'Expectativa de destino',
					'contenido' => implode( '', [
						uji_seed_p( 'La expectativa de destino se produce cuando resulta imposible el reingreso al servicio activo tras cesar en una situación de excedencia o suspensión firme.' ),
						uji_seed_p( 'Quien se encuentre en expectativa de destino:' ),
						uji_seed_ul( [
							'percibe las retribuciones básicas por sueldo y antigüedad',
							'mantiene el cómputo del tiempo a efectos pasivos, de cotización y de antigüedad',
							'permanece a disposición de las Cortes Generales para realizar suplencias o sustituciones propias de su Cuerpo',
						] ),
					] ),
				],
				[
					'numero' => '5.11', 'titulo' => 'Suspensión de funciones',
					'contenido' => implode( '', [
						uji_seed_p( 'La suspensión de funciones priva temporalmente del ejercicio de las funciones y de los derechos inherentes a la condición funcionarial.' ),
						uji_seed_p( 'Puede ser:' ),
						uji_seed_ul( [ 'provisional', 'firme' ] ),
						uji_seed_p( 'La suspensión firme se declara por sentencia penal o por sanción disciplinaria, así como cuando la pena determine imposibilidad de desempeñar el puesto.' ),
						uji_seed_p( 'La suspensión provisional puede adoptarse como medida cautelar durante la tramitación de un proceso penal o expediente disciplinario.' ),
						uji_seed_p( 'La suspensión provisional en expediente disciplinario no puede exceder de seis meses, salvo paralización imputable a la persona interesada. Durante la suspensión provisional se perciben retribuciones básicas, pero no complementarias.' ),
						uji_seed_p( 'La suspensión firme por sanción disciplinaria no puede exceder de seis años. Si supera seis meses, determina la pérdida del puesto de trabajo.' ),
					] ),
				],
				[
					'numero' => '5.12', 'titulo' => 'Reingreso al servicio activo',
					'contenido' => implode( '', [
						uji_seed_p( 'El reingreso del personal que no tenga plaza reservada requiere solicitud escrita presentada, al menos, quince días hábiles antes de la fecha prevista.' ),
						uji_seed_p( 'El reingreso se acuerda por el Letrado o Letrada Mayor de las Cortes Generales cuando exista puesto vacante. La adscripción provisional corresponde a la Secretaría General del Congreso, a la Letrada o Letrado Mayor del Senado o a la Letrada o Letrado Mayor de las Cortes Generales en el ámbito de los Servicios Comunes y de la Junta Electoral Central.' ),
						uji_seed_p( 'También se puede reingresar mediante participación y obtención de puesto en una convocatoria de provisión, por concurso o libre designación, adquiriéndose entonces plaza con carácter definitivo.' ),
					] ),
				],
			],
		],

		// ============================================================ SECCIÓN 6 (cierre)
		[
			'numero' => '6', 'titulo' => 'Cierre del tema', 'color_clase' => 'c6',
			'epigrafes' => [
				[
					'numero' => '6.1', 'titulo' => 'Recapitulación',
					'contenido' => implode( '', [
						uji_seed_p( 'El régimen del personal de las Cortes Generales se fundamenta en la autonomía parlamentaria del ' . $ref_ce( '72.1', 'art. 72.1 CE' ) . ' y se regula, con carácter principal, por el Estatuto del Personal de las Cortes Generales de 2025.' ),
						uji_seed_p( 'Ese régimen configura una Administración parlamentaria propia, integrada fundamentalmente por personal funcionario especializado y organizada en siete Cuerpos profesionales. El acceso se rige por igualdad, mérito y capacidad; la carrera se articula mediante promoción interna, provisión de puestos y carrera horizontal; y el vínculo funcionarial se adapta a las circunstancias personales y profesionales a través de las distintas situaciones administrativas.' ),
						uji_seed_p( 'La especialización, estabilidad y neutralidad del personal parlamentario son garantías necesarias para la continuidad institucional, la autonomía de las Cámaras y el correcto ejercicio de las funciones constitucionales de las Cortes Generales.' ),
					] ),
				],
			],
		],
	];
}

// ---------------------------------------------------------------- inserción

function uji_content_seed_tema_9() {
	global $wpdb;

	$temarios_tbl = $wpdb->prefix . 'uji_temarios';
	$temas_tbl    = $wpdb->prefix . 'uji_temas';
	$nodos_tbl    = $wpdb->prefix . 'uji_nodos';

	$temario_id = $wpdb->get_var( $wpdb->prepare(
		"SELECT id FROM {$temarios_tbl} WHERE slug = %s", 'ujier-cortes-generales'
	) );
	if ( ! $temario_id ) {
		$wpdb->insert( $temarios_tbl, [
			'slug'        => 'ujier-cortes-generales',
			'nombre'      => 'Oposición Ujier de las Cortes Generales',
			'descripcion' => 'Temario de la oposición al Cuerpo de Ujieres de las Cortes Generales.',
		] );
		$temario_id = $wpdb->insert_id;
	}

	$numero_tema = 9; // uji_nodos.tema_id guarda el número del tema (9), no el id interno de uji_temas

	$tema_id = $wpdb->get_var( $wpdb->prepare(
		"SELECT id FROM {$temas_tbl} WHERE temario_id = %d AND numero = %d", $temario_id, $numero_tema
	) );
	if ( $tema_id ) {
		$wpdb->update( $temas_tbl, [
			'titulo'     => 'El personal de las Cortes Generales (I)',
			'estado'     => 'publicado',
			'fuente_pdf' => 'Tema9.pdf',
			'updated_at' => current_time( 'mysql' ),
		], [ 'id' => $tema_id ] );
	} else {
		$wpdb->insert( $temas_tbl, [
			'temario_id' => $temario_id,
			'numero'     => 9,
			'titulo'     => 'El personal de las Cortes Generales (I)',
			'slug'       => 'tema-9-personal-cortes-generales-i',
			'estado'     => 'publicado',
			'fuente_pdf' => 'Tema9.pdf',
			'orden'      => 9,
		] );
		$tema_id = $wpdb->insert_id;
	}

	// reseeding idempotente: fuera los nodos anteriores de este tema
	$wpdb->delete( $nodos_tbl, [ 'tema_id' => $numero_tema ] );

	$orden_seccion = 0;
	foreach ( uji_content_tema_9_secciones() as $seccion ) {
		$orden_seccion++;
		$wpdb->insert( $nodos_tbl, [
			'tema_id'     => $numero_tema,
			'parent_id'   => null,
			'tipo'        => 'seccion',
			'numero'      => $seccion['numero'],
			'color_clase' => $seccion['color_clase'],
			'titulo'      => $seccion['titulo'],
			'orden'       => $orden_seccion,
		] );
		$seccion_id = $wpdb->insert_id;

		$orden_epigrafe = 0;
		foreach ( $seccion['epigrafes'] as $epigrafe ) {
			$orden_epigrafe++;
			$wpdb->insert( $nodos_tbl, [
				'tema_id'        => $numero_tema,
				'parent_id'      => $seccion_id,
				'tipo'           => 'epigrafe',
				'numero'         => $epigrafe['numero'],
				'titulo'         => $epigrafe['titulo'],
				'contenido_html' => $epigrafe['contenido'],
				'orden'          => $orden_epigrafe,
			] );
			$epigrafe_id = $wpdb->insert_id;

			$orden_sub = 0;
			foreach ( $epigrafe['subapartados'] ?? [] as $sub ) {
				$orden_sub++;
				$wpdb->insert( $nodos_tbl, [
					'tema_id'        => $numero_tema,
					'parent_id'      => $epigrafe_id,
					'tipo'           => 'subapartado',
					'numero'         => $epigrafe['numero'],
					'letra'          => $sub['letra'],
					'titulo'         => $sub['titulo'],
					'contenido_html' => $sub['contenido'],
					'orden'          => $orden_sub,
				] );
			}
		}
	}

	return $tema_id;
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command( 'uji seed-tema-9', static function () {
		$tema_id = uji_content_seed_tema_9();
		WP_CLI::success( "Tema 9 sembrado (id {$tema_id})." );
	} );
}
