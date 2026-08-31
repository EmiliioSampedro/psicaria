-- uji-content — esquemas del Tema 2, por sección
-- nodo_id apunta al nodo real de la sección en uji_nodos. seccion_id es el
-- número de sección (1-11), para tu propia clasificación/consulta.
-- Fuente: 2EsqTema2.ods (secciones 1,2,3,5,6,7,8,11) + 2politicaspublicas.ods
-- (sección 4, con más detalle). Sin esquema todavía: secciones 9 y 10 (no
-- había contenido para ellas en el material recibido).
-- Idempotente: borra antes de reinsertar cada bloque, así que se puede repetir sin duplicar.
SET NAMES utf8mb4;

-- Esquema de la sección 1: Introducción
SET @nodo_seccion_1 := (SELECT id FROM opouj_wp_uji_nodos WHERE tema_id = 2 AND tipo = 'seccion' AND numero = '1');
DELETE FROM opouj_wp_uji_esquemas WHERE tema_id = 2 AND nodo_id = @nodo_seccion_1;
INSERT INTO opouj_wp_uji_esquemas (tema_id, seccion_id, nodo_id, contenido_html)
VALUES (2, 1, @nodo_seccion_1, '<h2>Introducción</h2><ul><li>LO 3/2007 de 22 de marzo</li><li>Constitución: artículos 9 y 14</li><li>ONU</li><li>UE</li><li>Tratado de Amsterdam 1999</li></ul>');

-- Esquema de la sección 2: Objeto de la ley
SET @nodo_seccion_2 := (SELECT id FROM opouj_wp_uji_nodos WHERE tema_id = 2 AND tipo = 'seccion' AND numero = '2');
DELETE FROM opouj_wp_uji_esquemas WHERE tema_id = 2 AND nodo_id = @nodo_seccion_2;
INSERT INTO opouj_wp_uji_esquemas (tema_id, seccion_id, nodo_id, contenido_html)
VALUES (2, 2, @nodo_seccion_2, '<h2>Objeto de la ley</h2><p>Hacer efectivo el derecho. - establece principios de actuación – Regula derechos – Prevé medidas</p>');

-- Esquema de la sección 3: Ámbito de aplicación
SET @nodo_seccion_3 := (SELECT id FROM opouj_wp_uji_nodos WHERE tema_id = 2 AND tipo = 'seccion' AND numero = '3');
DELETE FROM opouj_wp_uji_esquemas WHERE tema_id = 2 AND nodo_id = @nodo_seccion_3;
INSERT INTO opouj_wp_uji_esquemas (tema_id, seccion_id, nodo_id, contenido_html)
VALUES (2, 3, @nodo_seccion_3, '<h2>Ámbito de aplicación</h2><p>Todas las personas</p><h3>Principios de actuación</h3><ul><li>Como principio informador jurídico</li><li>Ausencia de toda discrminación</li><li>Igualdad de trato</li></ul><h3>Definiciones básicas</h3><ul><li>Indemnidad ante represalias</li><li>Discriminación directa: Situación en la que se encuentra una persona tratada de manera menos favorable.</li><li>Discriminación indirecta: Situación en la que algo aparentemente neutro, pone en desventaja.</li><li>Acoso sexual: Todo compartamiento de materia sexual, verbal o física con el propósito o el efecto de atentar.</li><li>Acoso por razón de sexo: Cualquier comportamiento en función del sexo, con el propósito o el efecto de atentar...</li></ul><h3>Consecuencias jurídicas</h3><ul><li>Acciones positivas</li><li>Tutela judicial efectiva</li><li>Prueba (persona demandada probar ausencia)</li></ul>');

-- Esquema de la sección 4: Políticas públicas de igualdad
SET @nodo_seccion_4 := (SELECT id FROM opouj_wp_uji_nodos WHERE tema_id = 2 AND tipo = 'seccion' AND numero = '4');
DELETE FROM opouj_wp_uji_esquemas WHERE tema_id = 2 AND nodo_id = @nodo_seccion_4;
INSERT INTO opouj_wp_uji_esquemas (tema_id, seccion_id, nodo_id, contenido_html)
VALUES (2, 4, @nodo_seccion_4, '<h2>Políticas públicas de igualdad</h2><h3>Criterios generales de actuación de los Poderes Públicos</h3><ul><li>Compromiso con la efectividad del derecho constitucional de igualdad.</li><li>Integración del principio de igualdad de trato y oportunidades en el conjunto de las diferentes políticas</li><li>Colaboración y cooperación entre Administraciones</li><li>Participación equilibrada de mujeres y hombres en las candidaturas electorales y en la toma de decisiones</li><li>Adopción de las medidas necesarias para la erradicación de la violencia de género, la violencia familiar y de todas las formas de acoso sexual y acoso por razón de sexo.</li><li>La consideración de la singulares dificultades de las mujeres de colectivos de especial vulnerabilidad</li></ul><h3>Transversalidad del principio de igualdad</h3><ul><li>El principio de igualdad de trato entre mujeres y hombres, informará, con carácter transversal, la actuación de todos los poderes públicos.</li><li>Las administraciones públicas lo integrarán de forma activa en la adopción y ejecución de sus disposiciones normativas</li></ul><h3>Nombramientos realizados por los poderes públicos</h3><ul><li>Procurarán atender al principio de presencia equilibrada de mujeres y Hombres</li></ul><h3>Plan estratégico de Igualdad de Oportunidades</h3><ul><li>El Gobierno aprobará periódicamente un Plan Estratégico de igualdad de Oportunidades.</li></ul><h3>Informe periódico</h3><ul><li>El Gobierno elaborará un informe periódico sobre el conjunto de sus actuaciones en relación con la efectividad del principio de igualdad.</li><li>De este informe se dará cuenta a las Cortes Generales.</li></ul><h3>Informes de impacto de género</h3><ul><li>Proyectos de disposiciones y todos los Planes deberán incorporar un informe sobre su impacto por razón de género.</li></ul><h3>Colaboración entre las distintas administraciones</h3><ul><li>La AGE y las Administraciones de las CCAA cooperarán para integrar el derecho de igualdad entre mujeres y hombres en el ejercicio de sus respectivas Competencias,</li><li>En el seno de la Conferencia Sectorial de la Mujer podrán adoptarse planes y programas conjuntos de actuación con esta finalidad.</li><li>También Entidades Locales.</li></ul><h3>Acciones de planificación equitativa de los tiempos</h3><ul><li>Planes Municipales de organización del tiempo de la ciudad</li></ul>');

-- Esquema de la sección 5: Acciones administrativas para la igualdad
SET @nodo_seccion_5 := (SELECT id FROM opouj_wp_uji_nodos WHERE tema_id = 2 AND tipo = 'seccion' AND numero = '5');
DELETE FROM opouj_wp_uji_esquemas WHERE tema_id = 2 AND nodo_id = @nodo_seccion_5;
INSERT INTO opouj_wp_uji_esquemas (tema_id, seccion_id, nodo_id, contenido_html)
VALUES (2, 5, @nodo_seccion_5, '<h2>Acciones administrativas para la igualdad</h2><h3>La educación para la igualdad entre hombres y mujeres</h3><ul><li>Educación en el repeto</li><li>Eliminación de obstáculos y fomento igualdad</li></ul><h3>Integración del principio de igualdad en la política de educación</h3><p>En Currículums y todas las etapas educativas.</p><h3>La igualdad en el ámbito de la educación superior</h3><ul><li>Planes de estudio</li><li>Investigaciones</li><li>Cursos</li></ul><h3>La igualdad en el ámbito de la creación y producción artística e intelectual</h3><p>Promover equilibrio.</p><h3>La igualdad en el ámbito de la salud</h3><ul><li>Atender diferencias</li><li>Detección de violencia</li><li>Presencia equilibrada</li><li>Datos desagregados</li></ul><h3>Sociedad de la Información</h3><ul><li>Integracion</li><li>equilibrio</li><li>Lenguaje no sexista</li></ul><h3>Deportes</h3><p>promover.</p><h3>Desarrollo Rural</h3><ul><li>Titularidad compartida</li><li>Mejorar educación</li><li>Red de servicios sociales</li></ul><h3>Políticas urbanas, de ordenación territorial y vivienda</h3><ul><li>Medidas</li><li>Fomentar acceso a vivienda a vulnerables</li><li>Diseño de ciudad</li></ul><h3>Política española de cooperación para el desarrollo</h3><h3>Contratos de las Administraciones públicas</h3><p>Condiciones especiales en contratos administración pública</p><h3>Contratos de la Administración General del Estado</h3><h3>Subvenciones públicas</h3><p>para la consecución efectiva de la igualdad.</p>');

-- Esquema de la sección 6: Otros aspectos
SET @nodo_seccion_6 := (SELECT id FROM opouj_wp_uji_nodos WHERE tema_id = 2 AND tipo = 'seccion' AND numero = '6');
DELETE FROM opouj_wp_uji_esquemas WHERE tema_id = 2 AND nodo_id = @nodo_seccion_6;
INSERT INTO opouj_wp_uji_esquemas (tema_id, seccion_id, nodo_id, contenido_html)
VALUES (2, 6, @nodo_seccion_6, '<h2>Otros aspectos</h2><h3>Igualdad y Medios de comunicación</h3><p>Velar por imagen igualitaria.</p><ul><li>Si la publicidad conducta discriminatoria: Publicidad ilícita.</li></ul>');

-- Esquema de la sección 7: El derecho al trabajo en igualdad de oportunidades
SET @nodo_seccion_7 := (SELECT id FROM opouj_wp_uji_nodos WHERE tema_id = 2 AND tipo = 'seccion' AND numero = '7');
DELETE FROM opouj_wp_uji_esquemas WHERE tema_id = 2 AND nodo_id = @nodo_seccion_7;
INSERT INTO opouj_wp_uji_esquemas (tema_id, seccion_id, nodo_id, contenido_html)
VALUES (2, 7, @nodo_seccion_7, '<h2>El derecho al trabajo en igualdad de oportunidades</h2><h3>Igualdad de trato y de oportunidades en el ámbito laboral</h3><ul><li>Programas de mejora en la empleabilidad</li><li>Potenciar nivel formativo</li><li>Programas de inserción laboral</li><li>Negociación colectiva</li></ul><h3>Igualdad y conciliación</h3><ul><li>Asucnión equilibrada de responsabilidades familiares</li><li>Permiso de paternidad</li></ul><h3>Los planes de igualdad de las empresas</h3><p>Obligadas las empresas con 50 o más trabajadores.</p><ul><li>Medidas y objetivos, así como seguimiento y evaluación. - Previamente se hará diagnóstico.</li><li>Transparencia en los Planes: Acceso trabajadores. - Medidas para fomentar la adopción voluntaria de Planes de Igualdad.</li></ul><h3>Distintivo para las empresas en materia de igualdad</h3><p>Ministerio de Trabajo y Asuntos Sociales.</p>');

-- Esquema de la sección 8: El principio de igualdad en el empleo público
SET @nodo_seccion_8 := (SELECT id FROM opouj_wp_uji_nodos WHERE tema_id = 2 AND tipo = 'seccion' AND numero = '8');
DELETE FROM opouj_wp_uji_esquemas WHERE tema_id = 2 AND nodo_id = @nodo_seccion_8;
INSERT INTO opouj_wp_uji_esquemas (tema_id, seccion_id, nodo_id, contenido_html)
VALUES (2, 8, @nodo_seccion_8, '<h2>El principio de igualdad en el empleo público</h2><h3>Criterios de actuación de las Administraciones públicas</h3><ul><li>remover obstáculos</li><li>Facilitar conciliación</li><li>Fomentar formación</li><li>Promover presencia equilibrada</li><li>Establecer medidas efectivas de protección</li><li>Evaluación periódica</li></ul><h3>El principio de presencia equilibrada</h3><ul><li>Directivos Admon</li><li>Órganos de selección</li><li>Representantes AGE</li></ul><h3>Medidas de igualdad en el empleo para la AGE y organismos dependientes</h3><p>Impacto de género.</p><h3>Las Fuerzas Armadas</h3><p>adaptándose a normativa específica.</p><h3>Fuerzas y cuerpos de Seguridad del Estado</h3><p>adaptándose a normativa específica.</p>');

-- Esquema de la sección 11: Disposiciones organizativas
SET @nodo_seccion_11 := (SELECT id FROM opouj_wp_uji_nodos WHERE tema_id = 2 AND tipo = 'seccion' AND numero = '11');
DELETE FROM opouj_wp_uji_esquemas WHERE tema_id = 2 AND nodo_id = @nodo_seccion_11;
INSERT INTO opouj_wp_uji_esquemas (tema_id, seccion_id, nodo_id, contenido_html)
VALUES (2, 11, @nodo_seccion_11, '<h2>Disposiciones organizativas</h2><ul><li>Comisión Interministerial de Igualdad. - Unidades de Iguadad.</li><li>Instituto de la mujer.</li></ul>');

