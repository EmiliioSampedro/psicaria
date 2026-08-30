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
  var enlaces = [].slice.call(document.querySelectorAll('.uji-indice a:not(.uji-accion)'));
  var destinos = enlaces.map(function(a){ return document.querySelector(a.getAttribute('href')); });
  var esquemas = window.UJI_ESQUEMAS || {};
  var numTema = window.UJI_TEMA || '';
  var panelEsq = document.querySelector('.uji-esq__cuerpo');
  var cabEsq = document.querySelector('.uji-esq__ambito');
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
    if(!panelEsq || ref === refActual) return;
    refActual = ref;
    var e = buscarEsquema(ref);
    if(e){
      cabEsq.textContent = 'Esquema ' + e.ambito;
      panelEsq.innerHTML = e.html;
    } else {
      cabEsq.textContent = 'Esquema';
      panelEsq.innerHTML = '<p class="uji-esq__vacio">Todavía no hay esquema para este punto.</p>';
    }
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

  // ---- pestañas del lateral
  var tabs = [].slice.call(document.querySelectorAll('.uji-tab'));
  tabs.forEach(function(t){
    t.addEventListener('click', function(){
      var cual = t.getAttribute('data-panel');
      tabs.forEach(function(o){
        var on = o === t;
        o.classList.toggle('activo', on);
        o.setAttribute('aria-selected', on ? 'true' : 'false');
      });
      document.querySelectorAll('.uji-panel').forEach(function(p){
        p.hidden = p.getAttribute('data-panel') !== cual;
      });
      lector.classList.toggle('uji-modo-esquema', cual === 'esquema');
      try { localStorage.setItem('uji-panel', cual); } catch(e){}
    });
  });
  try {
    var guardado = localStorage.getItem('uji-panel');
    if(guardado === 'esquema') tabs[1].click();
  } catch(e){}

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
