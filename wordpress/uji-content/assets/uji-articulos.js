/* ------------------------------------------------------------------
   uji-articulos.js — modal de artículos para el lector de temario.

   Engancha cualquier elemento .uji-ref del documento:

       <button class="uji-ref" data-norma="CE" data-arts="72.1">art. 72.1 CE</button>
       <button class="uji-ref" data-norma="EPCG" data-arts="17-29">arts. 17 a 29</button>
       <button class="uji-ref" data-norma="RC" data-arts="7,8">arts. 7 y 8</button>

   Dos fuentes de datos, en este orden:

     1. window.UJI_ARTICULOS = { CE: { "66": {…} } }   ← banco local (demo)
     2. window.UJI_CFG.endpoint                        ← REST de WordPress
        GET  <endpoint>?norma=CE&arts=72.1,73
        →  { "72.1": { epigrafe, apartados:[{n,texto}] }, … }

   Formato de un artículo:
     { "epigrafe": "De las Cortes Generales",
       "apartados": [ {"n":"1","texto":"…"}, {"n":"2","texto":"…"} ] }
   o bien:
     { "texto": "Texto único del artículo." }
   ------------------------------------------------------------------ */

(function () {
  "use strict";

  var CFG = window.UJI_CFG || {};
  var BANCO = window.UJI_ARTICULOS || {};
  var NOMBRES = Object.assign({
    CE: "Constitución española de 1978",
    RC: "Reglamento del Congreso de los Diputados",
    RS: "Reglamento del Senado",
    EPCG: "Estatuto del Personal de las Cortes Generales"
  }, CFG.normas || {});

  var cache = {};        // "CE:72" → objeto artículo | null
  var modal, cuerpo, titulo, eyebrow, pie, ultimoFoco;

  // ---------------------------------------------------------------- datos

  function expandir(cadena) {
    var claves = [];
    (cadena || "").split(",").forEach(function (trozo) {
      trozo = trozo.trim();
      if (!trozo) return;
      var r = trozo.match(/^(\d+)-(\d+)$/);
      if (r) {
        var desde = +r[1], hasta = +r[2];
        if (hasta < desde) { var t = desde; desde = hasta; hasta = t; }
        hasta = Math.min(hasta, desde + 39);          // tope de seguridad
        for (var i = desde; i <= hasta; i++) claves.push(String(i));
      } else {
        claves.push(trozo);
      }
    });
    return claves;
  }

  function partes(clave) {
    var p = String(clave).split(".");
    return { base: p[0], apartado: p.length > 1 ? p.slice(1).join(".") : null };
  }

  function local(norma, base) {
    var n = BANCO[norma];
    return (n && n[base]) ? n[base] : null;
  }

  function pedir(norma, bases) {
    // resuelve lo que haya en local; pide al endpoint solo lo que falte
    var faltan = bases.filter(function (b) {
      return !(norma + ":" + b in cache) && !local(norma, b);
    });
    bases.forEach(function (b) {
      if (!(norma + ":" + b in cache)) {
        var l = local(norma, b);
        if (l) cache[norma + ":" + b] = l;
      }
    });
    if (!faltan.length || !CFG.endpoint) {
      faltan.forEach(function (b) { if (!(norma + ":" + b in cache)) cache[norma + ":" + b] = null; });
      return Promise.resolve();
    }
    var url = CFG.endpoint + (CFG.endpoint.indexOf("?") < 0 ? "?" : "&") +
      "norma=" + encodeURIComponent(norma) + "&arts=" + encodeURIComponent(faltan.join(","));
    return fetch(url, { credentials: "same-origin" })
      .then(function (r) { return r.ok ? r.json() : {}; })
      .then(function (datos) {
        faltan.forEach(function (b) { cache[norma + ":" + b] = datos[b] || null; });
      })
      .catch(function () {
        faltan.forEach(function (b) { cache[norma + ":" + b] = null; });
      });
  }

  // ---------------------------------------------------------------- pintado

  function esc(t) {
    return String(t == null ? "" : t)
      .replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
  }

  function pintarArticulo(norma, clave, art) {
    var p = partes(clave);
    var h = ['<article class="uji-art">'];
    h.push('<h4 class="uji-art__tit">Artículo ' + esc(p.base) +
      (art && art.epigrafe ? ' <span class="uji-art__epi">' + esc(art.epigrafe) + '</span>' : '') +
      '</h4>');
    if (!art) {
      h.push('<p class="uji-art__vacio">Sin texto cargado para este artículo. ' +
        'En producción lo devuelve tu tabla de <strong>' + esc(norma) + '</strong>' +
        (CFG.endpoint ? ' a través de <code>' + esc(CFG.endpoint) + '</code>' : '') + '.</p>');
    } else if (art.apartados && art.apartados.length) {
      h.push('<ol class="uji-art__aps">');
      art.apartados.forEach(function (ap) {
        var suyo = p.apartado && String(ap.n) === String(p.apartado);
        h.push('<li class="uji-art__ap' + (suyo ? ' uji-art__ap--citado' : '') + '"' +
          ' value="' + esc(ap.n) + '">' + esc(ap.texto) +
          (suyo ? ' <span class="uji-art__marca">citado aquí</span>' : '') + '</li>');
      });
      h.push("</ol>");
    } else {
      h.push('<p class="uji-art__texto">' + esc(art.texto || "") + "</p>");
    }
    h.push("</article>");
    return h.join("");
  }

  function abrir(ref) {
    crear();
    var norma = ref.getAttribute("data-norma") || "CE";
    var claves = expandir(ref.getAttribute("data-arts"));
    var bases = [];
    claves.forEach(function (c) {
      var b = partes(c).base;
      if (bases.indexOf(b) < 0) bases.push(b);
    });

    ultimoFoco = ref;
    eyebrow.textContent = NOMBRES[norma] || norma;
    titulo.textContent = ref.textContent.trim();
    cuerpo.innerHTML = '<p class="uji-art__cargando">Buscando el texto…</p>';

    var contenedor = ref.closest(".uji-ap, .uji-sec, .uji-glosario");
    var donde = contenedor ? contenedor.querySelector(".uji-ap__titulo, .uji-sec__titulo, dt") : null;
    pie.textContent = donde ? "Citado en: " + donde.textContent.trim() : "";

    modal.classList.add("abierto");
    modal.removeAttribute("hidden");
    document.documentElement.style.overflow = "hidden";
    modal.querySelector(".uji-modal__cerrar").focus();

    pedir(norma, bases).then(function () {
      var con = [], sin = [];
      claves.forEach(function (c) {
        (cache[norma + ":" + partes(c).base] ? con : sin).push(c);
      });

      var html = "";
      // pocos artículos: se pinta una ficha por cada uno, haya texto o no
      if (claves.length <= 3) {
        html = claves.map(function (c) {
          return pintarArticulo(norma, c, cache[norma + ":" + partes(c).base]);
        }).join("");
      } else {
        html = con.map(function (c) {
          return pintarArticulo(norma, c, cache[norma + ":" + partes(c).base]);
        }).join("");
        if (sin.length) {
          html += '<div class="uji-art__vacio">' +
            (con.length
              ? "Aún no están cargados estos " + sin.length + " artículos de "
              : "Ninguno de estos " + sin.length + " artículos de ") +
            "<strong>" + esc(norma) + "</strong>:" +
            '<span class="uji-art__pend">' +
            sin.map(function (c) { return "<span>" + esc(c) + "</span>"; }).join("") +
            "</span></div>";
        }
      }
      cuerpo.innerHTML = html;
    });
  }

  function cerrar() {
    if (!modal) return;
    modal.classList.remove("abierto");
    modal.setAttribute("hidden", "");
    document.documentElement.style.overflow = "";
    if (ultimoFoco) { ultimoFoco.focus(); ultimoFoco = null; }
  }

  function crear() {
    if (modal) return;
    modal = document.createElement("div");
    modal.className = "uji-modal";
    modal.setAttribute("hidden", "");
    modal.innerHTML =
      '<div class="uji-modal__fondo" data-cerrar></div>' +
      '<div class="uji-modal__caja" role="dialog" aria-modal="true" aria-labelledby="uji-modal-tit">' +
      '  <header class="uji-modal__cab">' +
      '    <p class="uji-modal__eyebrow"></p>' +
      '    <h3 class="uji-modal__tit" id="uji-modal-tit"></h3>' +
      '    <button type="button" class="uji-modal__cerrar" data-cerrar aria-label="Cerrar">✕</button>' +
      '  </header>' +
      '  <div class="uji-modal__cuerpo"></div>' +
      '  <footer class="uji-modal__pie"></footer>' +
      '</div>';
    document.body.appendChild(modal);
    cuerpo = modal.querySelector(".uji-modal__cuerpo");
    titulo = modal.querySelector(".uji-modal__tit");
    eyebrow = modal.querySelector(".uji-modal__eyebrow");
    pie = modal.querySelector(".uji-modal__pie");
    modal.addEventListener("click", function (e) {
      if (e.target.hasAttribute("data-cerrar")) cerrar();
    });
  }

  // ---------------------------------------------------------------- eventos

  document.addEventListener("click", function (e) {
    var ref = e.target.closest(".uji-ref");
    if (!ref) return;
    e.preventDefault();
    abrir(ref);
  });

  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && modal && !modal.hasAttribute("hidden")) cerrar();
  });
})();

