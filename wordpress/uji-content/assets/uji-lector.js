(function(){
  // evitar que el navegador reponga por su cuenta el scroll de la carga
  // anterior al recargar: interferiría con el "retomar sección" de abajo.
  try { if ('scrollRestoration' in history) history.scrollRestoration = 'manual'; } catch(e){}

  if (typeof window.UJI_TEMA === 'undefined') {
    return; // no hay lector en esta carga (p. ej. todavía no hay ningún tema publicado)
  }

  // si se entró sin ?tema= en la URL, el servidor ya eligió uno por
  // defecto (el primer tema publicado); si este visitante ya había leído
  // antes uno distinto, retomar ese en vez de quedarnos en el por defecto.
  if (!/[?&]tema=/.test(location.search)) {
    var recordado = null;
    try { recordado = localStorage.getItem('uji-ultimo-tema'); } catch(e){}
    if (recordado && recordado !== String(window.UJI_TEMA)) {
      var sep = location.search ? '&' : '?';
      location.replace(location.pathname + location.search + sep + 'tema=' + encodeURIComponent(recordado));
      return;
    }
  }

  var lector = document.querySelector('.uji-lector');
  lector.classList.add('uji-js');
  var barra = document.getElementById('uji-barra');
  var cuerpo = document.querySelector('.uji-cuerpo');
  function progreso(){
    var h = document.documentElement;
    var max = (h.scrollHeight - h.clientHeight) || 1;
    barra.style.width = Math.min(100, (h.scrollTop / max) * 100) + '%';
  }
  document.addEventListener('scroll', progreso, {passive:true});
  progreso();

  // ---- índice activo y esquema del punto por el que se va leyendo
  var railCuerpo = document.querySelector('.uji-rail__cuerpo');
  var enlaces = [].slice.call(document.querySelectorAll('.uji-indice a:not(.uji-accion)'));
  var destinos = enlaces.map(function(a){ return document.querySelector(a.getAttribute('href')); });
  var esquemas = window.UJI_ESQUEMAS || {};
  var numTema = window.UJI_TEMA || '';
  try { if (numTema) localStorage.setItem('uji-ultimo-tema', numTema); } catch(e){}
  var panelEsq = document.querySelector('.uji-esq__cuerpo');
  var cabEsq = document.querySelector('.uji-esq__ambito');
  var panelEsqGrande = document.querySelector('.uji-esq-grande__cuerpo');
  var cabEsqGrande = document.querySelector('.uji-esq-grande__ambito');
  var refActual = null;

  function buscarEsquema(ref){
    var pruebas = [];
    if(ref){
      pruebas.push([ref, ref.indexOf('_') > -1 ? 'de este epígrafe' : 'de esta sección']);
      if(ref.indexOf('_') > -1) pruebas.push([ref.split('_')[0], 'de esta sección']);
    }
    if(numTema) pruebas.push([numTema, 'del tema']);
    for(var i = 0; i < pruebas.length; i++){
      if(esquemas[pruebas[i][0]]) return {html: esquemas[pruebas[i][0]], ambito: pruebas[i][1]};
    }
    return null;
  }

  function pintarEsquema(ref){
    if((!panelEsq && !panelEsqGrande) || ref === refActual) return;
    refActual = ref;
    var e = buscarEsquema(ref);
    var titulo = e ? ('Esquema ' + e.ambito) : 'Esquema';
    var html = e ? e.html : '<p class="uji-esq__vacio">Todavía no hay esquema para este punto.</p>';
    if(panelEsq){ cabEsq.textContent = titulo; panelEsq.innerHTML = html; }
    if(panelEsqGrande){ cabEsqGrande.textContent = titulo; panelEsqGrande.innerHTML = html; }
  }

  var obs = new IntersectionObserver(function(entradas){
    entradas.forEach(function(e){
      if(!e.isIntersecting) return;
      var i = destinos.indexOf(e.target);
      if(i < 0) return;
      enlaces.forEach(function(a){ a.classList.remove('activo'); });
      enlaces[i].classList.add('activo');
      var ref = enlaces[i].getAttribute('data-ref');
      pintarEsquema(ref);
      try { if (numTema) localStorage.setItem('uji-ultima-seccion-' + numTema, ref); } catch(err){}
      var a = enlaces[i], r = a.getBoundingClientRect(), c = a.closest('.uji-indice');
      if(c && (r.top < 60 || r.bottom > window.innerHeight - 60)) a.scrollIntoView({block:'nearest'});
    });
  }, {rootMargin:'-72px 0px -70% 0px'});
  destinos.forEach(function(d){ if(d) obs.observe(d); });

  // retomar la sección por la que se iba la última vez en este tema
  var refGuardada = null;
  try { refGuardada = numTema ? localStorage.getItem('uji-ultima-seccion-' + numTema) : null; } catch(e){}
  var indiceGuardado = -1;
  if (refGuardada) {
    for (var gi = 0; gi < enlaces.length; gi++) {
      if (enlaces[gi].getAttribute('data-ref') === refGuardada) { indiceGuardado = gi; break; }
    }
  }
  if (indiceGuardado > -1 && destinos[indiceGuardado]) {
    var destinoGuardado = destinos[indiceGuardado];
    // esperar a que el navegador termine su propio ajuste de scroll de la
    // carga antes de saltar, o nos lo pisaría un instante después
    requestAnimationFrame(function(){ requestAnimationFrame(function(){
      destinoGuardado.scrollIntoView({block:'start'});
    }); });
  }
  var refInicial = indiceGuardado > -1 ? refGuardada : (enlaces.length ? enlaces[0].getAttribute('data-ref') : null);
  pintarEsquema(refInicial);

  // ---- pestañas Tema/Esquema de la zona de contenido: "la caja grande".
  // Se definen antes que la lógica de pestañas de abajo porque en móvil
  // las pestañas del lateral (Tema/Esquema/Índice) reutilizan este mismo
  // conmutador en vez de duplicar un segundo panel de esquema.
  var vistaTabs = [].slice.call(document.querySelectorAll('.uji-vista-tab'));
  var vistaPaneles = [].slice.call(document.querySelectorAll('[data-vista-panel]'));
  function activarVistaPanel(cual){
    vistaTabs.forEach(function(o){
      var on = o.getAttribute('data-vista') === cual;
      o.classList.toggle('activo', on);
      o.setAttribute('aria-selected', on ? 'true' : 'false');
    });
    vistaPaneles.forEach(function(p){
      p.hidden = p.getAttribute('data-vista-panel') !== cual;
    });
  }
  vistaTabs.forEach(function(t){
    t.addEventListener('click', function(){ activarVistaPanel(t.getAttribute('data-vista')); });
  });

  // ---- pestañas: Tema / Esquema / Índice
  // En escritorio el lateral (Índice/Esquema) y el contenido (Tema/Esquema)
  // se ven siempre los dos a la vez, cada uno con su propio conmutador.
  // En móvil solo cabe "una caja": estas tres pestañas pasan a controlar
  // directamente la caja grande (Tema y Esquema, vía activarVistaPanel) o
  // el lateral reducido a solo Índice — nada se muestra dos veces.
  function esMovil(){ return window.matchMedia('(max-width:940px)').matches; }

  var tabs = [].slice.call(document.querySelectorAll('.uji-tab'));
  var panelesLateral = [].slice.call(document.querySelectorAll('.uji-rail .uji-panel'));
  var panelLateral = 'indice'; // escritorio: el lateral siempre empieza en el índice
  var vistaMovil = 'tema'; // móvil: qué pestaña de las tres está activa

  function aplicarVista(){
    var movil = esMovil();
    tabs.forEach(function(t){
      var p = t.getAttribute('data-panel');
      var on = movil ? (p === vistaMovil) : (p !== 'tema' && p === panelLateral);
      t.classList.toggle('activo', on);
      t.setAttribute('aria-selected', on ? 'true' : 'false');
    });
    var panelMostrado = movil ? 'indice' : panelLateral;
    panelesLateral.forEach(function(p){
      p.hidden = p.getAttribute('data-panel') !== panelMostrado;
    });
    if (movil) {
      if (cuerpo) cuerpo.hidden = vistaMovil === 'indice';
      if (railCuerpo) railCuerpo.hidden = vistaMovil !== 'indice';
      if (vistaMovil !== 'indice') activarVistaPanel(vistaMovil);
    } else {
      if (cuerpo) cuerpo.hidden = false;
      if (railCuerpo) railCuerpo.hidden = false;
    }
    lector.classList.toggle('uji-modo-esquema', !movil && panelLateral === 'esquema');
  }

  tabs.forEach(function(t){
    t.addEventListener('click', function(){
      var p = t.getAttribute('data-panel');
      if (esMovil()) {
        vistaMovil = p;
      } else if (p !== 'tema') {
        panelLateral = p;
      }
      aplicarVista();
    });
  });

  // ---- fila de números de sección: eligen a mano el esquema de esa
  // sección (y abren la pestaña Esquema del lateral). No tocan el índice
  // ni desplazan el contenido — son solo un atajo dentro del esquema.
  var botonesSec = [].slice.call(document.querySelectorAll('.uji-secnav__n'));
  botonesSec.forEach(function(b){
    b.addEventListener('click', function(){
      botonesSec.forEach(function(o){ o.classList.remove('activo'); });
      b.classList.add('activo');
      refActual = null; // forzar repintado aunque coincida con la última sección leída
      pintarEsquema(b.getAttribute('data-ref'));
      if (!esMovil()) { panelLateral = 'esquema'; aplicarVista(); }
    });
  });

  // al tocar un enlace del índice en móvil, pasar a la pestaña "Tema" para
  // que el salto a la sección/epígrafe se vea (si no, el destino queda
  // oculto detrás del panel de índice y el navegador no puede desplazarse).
  enlaces.forEach(function(a){
    a.addEventListener('click', function(){
      if (esMovil()) { vistaMovil = 'tema'; aplicarVista(); }
    });
  });

  aplicarVista();

  var pasos = [15.5, 16.5, 17.5, 19, 20.5], n = 1;
  try { var g = localStorage.getItem('uji-tam'); if(g !== null) n = Math.max(0, Math.min(4, +g)); } catch(e){}
  function aplicar(){
    cuerpo.style.setProperty('--uji-cuerpo-tam', pasos[n] + 'px');
    try { localStorage.setItem('uji-tam', n); } catch(e){}
  }
  document.getElementById('uji-mas').addEventListener('click', function(){ n = Math.min(4, n+1); aplicar(); });
  document.getElementById('uji-menos').addEventListener('click', function(){ n = Math.max(0, n-1); aplicar(); });
  document.getElementById('uji-imprimir').addEventListener('click', function(){ window.print(); });
  aplicar();
})();
