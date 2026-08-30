(function(){
  document.querySelector('.uji-lector').classList.add('uji-js');
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
  var lector = document.querySelector('.uji-lector');
  var railCuerpo = document.querySelector('.uji-rail__cuerpo');
  var enlaces = [].slice.call(document.querySelectorAll('.uji-indice a:not(.uji-accion)'));
  var destinos = enlaces.map(function(a){ return document.querySelector(a.getAttribute('href')); });
  var esquemas = window.UJI_ESQUEMAS || {};
  var numTema = window.UJI_TEMA || '';
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
      pintarEsquema(enlaces[i].getAttribute('data-ref'));
      var a = enlaces[i], r = a.getBoundingClientRect(), c = a.closest('.uji-indice');
      if(c && (r.top < 60 || r.bottom > window.innerHeight - 60)) a.scrollIntoView({block:'nearest'});
    });
  }, {rootMargin:'-72px 0px -70% 0px'});
  destinos.forEach(function(d){ if(d) obs.observe(d); });
  pintarEsquema(enlaces.length ? enlaces[0].getAttribute('data-ref') : null);

  // ---- pestañas: Tema / Índice / Esquema
  // En escritorio el lateral y el contenido se ven siempre los dos a la vez
  // (las pestañas solo cambian qué se ve DENTRO del lateral). En móvil son
  // tres vistas excluyentes: solo se ve una de las tres a la vez.
  function esMovil(){ return window.matchMedia('(max-width:940px)').matches; }

  var tabs = [].slice.call(document.querySelectorAll('.uji-tab'));
  var panelesLateral = [].slice.call(document.querySelectorAll('.uji-rail .uji-panel'));
  var panelLateral = 'indice';
  try {
    var g = localStorage.getItem('uji-panel');
    if (g === 'esquema' || g === 'indice') panelLateral = g;
  } catch(e){}
  var vista = 'tema';

  function aplicarVista(){
    tabs.forEach(function(t){
      var p = t.getAttribute('data-panel');
      var on = p === 'tema' ? vista === 'tema' : (vista !== 'tema' && p === panelLateral);
      t.classList.toggle('activo', on);
      t.setAttribute('aria-selected', on ? 'true' : 'false');
    });
    panelesLateral.forEach(function(p){
      p.hidden = p.getAttribute('data-panel') !== panelLateral;
    });
    var movil = esMovil();
    if (cuerpo) cuerpo.hidden = movil && vista !== 'tema';
    if (railCuerpo) railCuerpo.hidden = movil && vista === 'tema';
    lector.classList.toggle('uji-modo-esquema', panelLateral === 'esquema');
  }

  tabs.forEach(function(t){
    t.addEventListener('click', function(){
      var p = t.getAttribute('data-panel');
      if (p === 'tema') {
        vista = 'tema';
      } else {
        vista = 'otro';
        panelLateral = p;
        try { localStorage.setItem('uji-panel', p); } catch(e){}
      }
      aplicarVista();
    });
  });

  // al tocar un enlace del índice en móvil, pasar a la vista "Tema" para
  // que el salto a la sección/epígrafe se vea (si no, el destino queda
  // oculto detrás del panel de índice y el navegador no puede desplazarse).
  enlaces.forEach(function(a){
    a.addEventListener('click', function(){
      if (esMovil()) { vista = 'tema'; aplicarVista(); }
    });
  });

  aplicarVista();

  // ---- pestañas Tema/Esquema de la zona de contenido (independientes de
  // las del lateral: aquí "Esquema" muestra el esquema a todo el ancho de
  // la columna de contenido, no en el hueco estrecho del lateral).
  var vistaTabs = [].slice.call(document.querySelectorAll('.uji-vista-tab'));
  var vistaPaneles = [].slice.call(document.querySelectorAll('[data-vista-panel]'));
  vistaTabs.forEach(function(t){
    t.addEventListener('click', function(){
      var cual = t.getAttribute('data-vista');
      vistaTabs.forEach(function(o){
        var on = o === t;
        o.classList.toggle('activo', on);
        o.setAttribute('aria-selected', on ? 'true' : 'false');
      });
      vistaPaneles.forEach(function(p){
        p.hidden = p.getAttribute('data-vista-panel') !== cual;
      });
    });
  });

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
