// ============================================================================
// App.jsx — PsicarIA · Tests psicotécnicos generados dinámicamente
// ============================================================================
import { useState, useEffect, useRef, useCallback } from "react";
import generarSumasRestas      from "./generadores/sumas_restas.js";
import generarMultDiv           from "./generadores/mult_div.js";
import generarSeries            from "./generadores/series.js";
import generarPorcentajes       from "./generadores/porcentajes.js";
import generarTiempoVelocidad   from "./generadores/tiempo_velocidad.js";

const WP = window.PsicarIAConfig || { ajaxurl:"", nonce:"", memberID:0 };

const GENERADORES = {
    sumas_restas:     generarSumasRestas,
    mult_div:         generarMultDiv,
    series:           generarSeries,
    porcentajes:      generarPorcentajes,
    tiempo_velocidad: generarTiempoVelocidad,
};

async function api(action, data = {}) {
    const body = new URLSearchParams({
        action, security: WP.nonce, user_id: WP.memberID, ...data
    });
    const r = await fetch(WP.ajaxurl, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body,
    });
    return r.json();
}

const fmt    = s => `${String(Math.floor(s/60)).padStart(2,"0")}:${String(s%60).padStart(2,"0")}`;
const LETRAS = ["A","B","C","D"];

const S = {
    wrap:   { maxWidth:1200, margin:"0 auto", padding:"0 4px", fontFamily:"Arial,sans-serif" },
    h2:     { color:"white", background:"#1a237e", fontSize:"clamp(0.7rem,2.5vw,1rem)",
               padding:"6px 10px", fontWeight:200, margin:"3px 0",
               lineHeight:"1.5rem", borderRadius:4 },
    chip:   { padding:"6px 14px", border:"1px solid #ddd", borderRadius:20,
               cursor:"pointer", background:"#f0f0f0", fontSize:14 },
    chipOn: { background:"#1a237e", color:"white", borderColor:"#1a237e" },
    btnPri: { background:"#1a237e", color:"white", border:"none", borderRadius:6,
               padding:"12px 20px", cursor:"pointer", fontSize:16, width:"100%" },
    btnDng: { background:"#f44336", color:"white", border:"none", borderRadius:6,
               padding:"8px 14px", cursor:"pointer", fontWeight:"bold" },
    btnSm:  { background:"#1a237e", color:"white", border:"none", borderRadius:16,
               padding:"4px 8px", cursor:"pointer", fontSize:13, height:28 },
    badge:  { display:"inline-flex", alignItems:"center", background:"#f0f0f0",
               padding:"4px 8px", borderRadius:16, fontSize:13, whiteSpace:"nowrap", gap:4 },
    hdr:    { background:"#fff", borderBottom:"2px solid #1a237e", fontSize:13 },
    hdrRow: { display:"flex", justifyContent:"space-between", alignItems:"center",
               padding:"4px 10px", gap:8, flexWrap:"nowrap" },
    card:   { background:"#fff", borderRadius:10, boxShadow:"0 2px 8px rgba(0,0,0,.1)",
               padding:16, marginTop:8 },
    enun:   { background:"#e8eaf6", borderRadius:10, padding:"14px 16px",
               fontFamily:"monospace", fontSize:"1.1rem", lineHeight:2,
               marginBottom:16, textAlign:"center", letterSpacing:1 },
};

const SC = { CONFIG:"config", TEST:"test", FIN:"fin" };

// ============================================================================
// ROOT
// ============================================================================
export default function App() {
    const [screen,     setScreen]     = useState(SC.CONFIG);
    const [config,     setConfig]     = useState(null);
    const [pregs,      setPregs]      = useState([]);
    const [actual,     setActual]     = useState(0);
    const [resps,      setResps]      = useState([]);
    const [tiempos,    setTiempos]    = useState([]);
    const [tGlobal,    setTGlobal]    = useState(0);
    const [showTime,   setShowTime]   = useState(true);
    const [showStats,  setShowStats]  = useState(true);
    const [explIdx,    setExplIdx]    = useState(null);
    const [guardado,   setGuardado]   = useState(false);
    const [finalizado, setFinalizado] = useState(false);  // ← estado correcto
    const [isMobile,   setIsMobile]   = useState(window.innerWidth < 768);

    const timerRef = useRef(null);
    const tInicio  = useRef(null);

    useEffect(() => {
        const h = () => setIsMobile(window.innerWidth < 768);
        window.addEventListener("resize", h);
        return () => window.removeEventListener("resize", h);
    }, []);

    // ── Generar test ─────────────────────────────────────────────────────────
    const generarTest = useCallback((cfg, params) => {
        const gen = GENERADORES[cfg.tipo_id];
        if (!gen) { alert("Generador no disponible para este tipo."); return; }

        const n = cfg.numPregs || 10;
        const nuevasPregs = Array.from({ length: n }, () => gen(params));

        setPregs(nuevasPregs);
        setResps(new Array(n).fill(null));
        setTiempos(new Array(n).fill(0));
        setActual(0);
        setTGlobal(0);
        setGuardado(false);
        setFinalizado(false);
        setExplIdx(null);
        setConfig({ ...cfg, params });
        setScreen(SC.TEST);

        if (timerRef.current) clearInterval(timerRef.current);
        tInicio.current = Date.now();
        timerRef.current = setInterval(() => setTGlobal(t => t + 1), 1000);
    }, []);

    // ── Responder — permite cambiar o desmarcar antes de finalizar ───────────
    const responder = useCallback((pregIdx, opIdx) => {
        if (finalizado) return;
        setResps(prev => {
            const cpy = [...prev];
            // Toggle: pinchar la misma opción la desmarca
            if (prev[pregIdx] !== null && prev[pregIdx].seleccionada === opIdx) {
                cpy[pregIdx] = null;
            } else {
                cpy[pregIdx] = {
                    seleccionada: opIdx,
                    correcta:     pregs[pregIdx].correcta,
                };
            }
            return cpy;
        });
    }, [pregs, finalizado]);

    // ── Finalizar ─────────────────────────────────────────────────────────────
    const finalizar = useCallback(async (respsSnap, tSnap) => {
        if (timerRef.current) { clearInterval(timerRef.current); timerRef.current = null; }
        setFinalizado(true);
        setScreen(SC.FIN);
        setExplIdx(0);

        if (!guardado && config) {
            setGuardado(true);
            const aciertos = respsSnap.filter(r => r && r.seleccionada === r.correcta).length;
            const fallos   = respsSnap.filter(r => r && r.seleccionada !== r.correcta).length;
            await api("psicaria_guardar_resultado", {
                tipo_id:    config.tipo_id,
                dificultad: config.dificultad,
                total:      pregs.length,
                aciertos, fallos,
                tiempo:     tSnap,
            });
        }
    }, [config, pregs, guardado]);

    // ── Teclado ───────────────────────────────────────────────────────────────
    useEffect(() => {
        const h = (e) => {
            if (screen !== SC.TEST) return;
            if (e.key >= "1" && e.key <= "4") {
                e.preventDefault();
                responder(actual, parseInt(e.key) - 1);
            }
            if (e.key === "ArrowRight" && actual < pregs.length - 1) {
                e.preventDefault(); setActual(a => a + 1);
            }
            if (e.key === "ArrowLeft" && actual > 0) {
                e.preventDefault(); setActual(a => a - 1);
            }
            if (e.key === "Enter" && e.ctrlKey) {
                e.preventDefault();
                if (confirm("¿Finalizar el test?")) finalizar(resps, tGlobal);
            }
        };
        window.addEventListener("keydown", h);
        return () => window.removeEventListener("keydown", h);
    }, [screen, actual, resps, pregs, tGlobal, responder, finalizar]);

    const stats = {
        aciertos: resps.filter(r => r && r.seleccionada === r.correcta).length,
        fallos:   resps.filter(r => r && r.seleccionada !== r.correcta).length,
    };

    if (screen === SC.CONFIG) return (
        <div style={S.wrap}>
            <PantallaConfig onIniciar={generarTest} />
        </div>
    );

    if (screen === SC.TEST) return (
        <div style={S.wrap}>
            <Header aciertos={stats.aciertos} fallos={stats.fallos}
                tiempo={tGlobal} showTime={showTime} showStats={showStats}
                isMobile={isMobile} actual={actual} total={pregs.length}
                onToggleTime={()  => setShowTime(v  => !v)}
                onToggleStats={() => setShowStats(v => !v)}
                onFinalizar={() => { if (confirm("¿Finalizar?")) finalizar(resps, tGlobal); }} />

            {isMobile && (
                <NavMobile pregs={pregs} resps={resps} actual={actual}
                    onIr={setActual}
                    onFinalizar={() => { if (confirm("¿Finalizar?")) finalizar(resps, tGlobal); }} />
            )}

            <div style={{ display:"flex", gap:12, marginTop:4 }}>
                <div style={{ flex:1, minWidth:0 }}>
                    <PreguntaView
                        pregunta={pregs[actual]} idx={actual} total={pregs.length}
                        respuesta={resps[actual]}
                        onResponder={op => responder(actual, op)} />
                </div>
                {!isMobile && (
                    <div style={{ flex:"0 0 120px" }}>
                        <NavDesktop pregs={pregs} resps={resps} actual={actual}
                            onIr={setActual}
                            onFinalizar={() => { if (confirm("¿Finalizar?")) finalizar(resps, tGlobal); }} />
                    </div>
                )}
            </div>
        </div>
    );

    if (screen === SC.FIN) return (
        <div style={S.wrap}>
            <div style={{ display:"flex", gap:16, marginTop:8, flexWrap: isMobile ? "wrap" : "nowrap" }}>
                <div style={{ flex:1, minWidth:0 }}>
                    {explIdx !== null && (
                        <ExplicacionView pregunta={pregs[explIdx]} idx={explIdx}
                            total={pregs.length} respuesta={resps[explIdx]}
                            tiempo={tiempos[explIdx] || 0} />
                    )}
                </div>
                <div style={{ flex: isMobile ? "1 1 100%" : "0 0 220px" }}>
                    <Resultados pregs={pregs} resps={resps} tiempos={tiempos}
                        tGlobal={tGlobal} isMobile={isMobile}
                        onVerExpl={i => setExplIdx(i)}
                        onNuevoTest={() => setScreen(SC.CONFIG)} />
                </div>
            </div>
        </div>
    );
}

// ============================================================================
// PANTALLA CONFIGURACIÓN
// ============================================================================
function PantallaConfig({ onIniciar }) {
    const [tipos,      setTipos]      = useState([]);
    const [tipoSel,    setTipoSel]    = useState(null);
    const [dificultad, setDificultad] = useState(1);
    const [numPregs,   setNumPregs]   = useState(10);
    const [cargando,   setCargando]   = useState(true);
    const [params,     setParams]     = useState(null);
    const [tipoInfo,   setTipoInfo]   = useState(null);

    useEffect(() => {
        (async () => {
            const r = await api("psicaria_get_config", { dificultad: 1 });
            if (r.success && r.data.configs.length) {
                const vistos = {};
                r.data.configs.forEach(c => { vistos[c.tipo_id] = c; });
                const lista = Object.values(vistos);
                setTipos(lista);
                setTipoSel(lista[0].tipo_id);
                setTipoInfo(lista[0]);
            }
            setCargando(false);
        })();
    }, []);

    useEffect(() => {
        if (!tipoSel) return;
        (async () => {
            const r = await api("psicaria_get_config", { dificultad, tipo_id: tipoSel });
            if (r.success && r.data.configs.length) {
                setParams(r.data.configs[0].parametros);
            }
        })();
    }, [tipoSel, dificultad]);

    const seleccionarTipo = (t) => {
        setTipoSel(t.tipo_id);
        setTipoInfo(t);
    };

    if (cargando) return (
        <div style={{ padding:40, textAlign:"center", color:"#888" }}>
            <div style={{ fontSize:32, marginBottom:12 }}>🧠</div>
            Cargando tests…
        </div>
    );

    return (
        <div style={{ maxWidth:600, margin:"0 auto", padding:20 }}>
            <h2 style={S.h2}>🧠 PsicarIA — Tests Psicotécnicos</h2>

            <div style={S.card}>

                {/* Tipo de test */}
                <div style={{ marginBottom:20 }}>
                    <label style={{ display:"block", fontWeight:"bold", marginBottom:8, fontSize:15 }}>
                        📋 Tipo de test
                    </label>
                    <div style={{ display:"flex", flexWrap:"wrap", gap:8 }}>
                        {tipos.map(t => (
                            <button key={t.tipo_id} onClick={() => seleccionarTipo(t)}
                                style={{ ...S.chip, ...(tipoSel === t.tipo_id ? S.chipOn : {}) }}>
                                {t.nombre}
                            </button>
                        ))}
                    </div>
                    {tipoInfo?.descripcion && (
                        <div style={{ marginTop:8, fontSize:13, color:"#666", fontStyle:"italic" }}>
                            {tipoInfo.descripcion}
                        </div>
                    )}
                </div>

                {/* Dificultad */}
                <div style={{ marginBottom:20 }}>
                    <label style={{ display:"block", fontWeight:"bold", marginBottom:8, fontSize:15 }}>
                        🎯 Dificultad
                    </label>
                    <div style={{ display:"flex", gap:8 }}>
                        {[
                            [1, "⭐ Fácil",      "#4CAF50"],
                            [2, "⭐⭐ Medio",    "#ff9800"],
                            [3, "⭐⭐⭐ Difícil","#f44336"],
                        ].map(([val, label, color]) => (
                            <button key={val} onClick={() => setDificultad(val)}
                                style={{ ...S.chip, flex:1,
                                    ...(dificultad === val
                                        ? { background:color, color:"white", borderColor:color }
                                        : {}) }}>
                                {label}
                            </button>
                        ))}
                    </div>
                </div>

                {/* Número de preguntas */}
                <div style={{ marginBottom:20 }}>
                    <label style={{ display:"block", fontWeight:"bold", marginBottom:8, fontSize:15 }}>
                        🔢 Número de preguntas
                    </label>
                    <div style={{ display:"flex", gap:8 }}>
                        {[10, 25, 50].map(n => (
                            <button key={n} onClick={() => setNumPregs(n)}
                                style={{ ...S.chip, flex:1,
                                    ...(numPregs === n ? S.chipOn : {}) }}>
                                {n}
                            </button>
                        ))}
                    </div>
                </div>

                {/* Info configuración */}
                {params && (
                    <div style={{ background:"#e8eaf6", borderRadius:8,
                                  padding:"10px 14px", fontSize:13,
                                  color:"#3949ab", marginBottom:20 }}>
                        <strong>Configuración:</strong>{" "}
                        {params.num_operaciones !== undefined &&
                            `${params.num_operaciones} operación${params.num_operaciones > 1 ? 'es' : ''} · `}
                        {params.num_sumandos !== undefined &&
                            `${params.num_sumandos} números por operación · `}
                        {params.max_numero !== undefined &&
                            `números entre ${params.min_numero ?? 1} y ${params.max_numero}`}
                        {params.tipo !== undefined && !params.num_operaciones &&
                            `Modo: ${params.tipo.replace(/_/g, ' ')}`}
                        {params.longitud_serie !== undefined &&
                            `Serie de ${params.longitud_serie} elementos · tipo: ${params.tipo}`}
                    </div>
                )}

                <button onClick={() => {
                    if (!tipoSel || !params) return;
                    onIniciar({ tipo_id: tipoSel, dificultad, numPregs }, params);
                }} style={{ ...S.btnPri, fontSize:"1.1rem", padding:"14px 20px" }}>
                    ▶ Comenzar ({numPregs} preguntas)
                </button>
            </div>
        </div>
    );
}

// ============================================================================
// HEADER
// ============================================================================
function Header({ aciertos, fallos, tiempo, showTime, showStats, isMobile,
                  actual, total, onToggleTime, onToggleStats, onFinalizar }) {
    return (
        <div style={S.hdr}>
            <div style={S.hdrRow}>
                <div style={{ display:"flex", gap:5 }}>
                    <button onClick={onToggleStats} style={S.btnSm}>📊</button>
                    <button onClick={onToggleTime}  style={S.btnSm}>⏱️</button>
                </div>
                <div style={{ display:"flex", gap:6, alignItems:"center", flex:1 }}>
                    <span style={S.badge}>🧠 PsicarIA</span>
                    <span style={S.badge}>{actual+1}/{total}</span>
                    {showStats && <>
                        <span style={{ ...S.badge, background:"#e3f2fd", border:"1px solid #4CAF50" }}>✅ {aciertos}</span>
                        <span style={{ ...S.badge, background:"#ffebee", border:"1px solid #f44336" }}>❌ {fallos}</span>
                    </>}
                </div>
                {showTime && (
                    <div style={{ background:"#e8eaf6", padding:"4px 10px", borderRadius:20,
                                  fontWeight:"bold", fontSize:13, whiteSpace:"nowrap" }}>
                        ⏱️ {fmt(tiempo)}
                    </div>
                )}
                {!isMobile && (
                    <button onClick={onFinalizar}
                        style={{ ...S.btnDng, padding:"4px 10px", fontSize:12 }}>
                        ⚡ Fin
                    </button>
                )}
            </div>
        </div>
    );
}

// ============================================================================
// NAV MÓVIL
// ============================================================================
function NavMobile({ pregs, resps, actual, onIr, onFinalizar }) {
    const [abierto, setAbierto] = useState(false);
    const contestadas = resps.filter(r => r !== null).length;

    return (
        <div style={{ borderBottom:"1px solid #e0e0e0", background:"#fff", marginBottom:6 }}>
            <div style={{ display:"flex", alignItems:"center", padding:"5px 8px", gap:6 }}>
                {!abierto ? (
                    <button onClick={() => setAbierto(true)}
                        style={{ flex:1, background:"#f0f0f0", border:"1px solid #ddd",
                                 borderRadius:6, padding:"6px 10px", cursor:"pointer", fontSize:13,
                                 display:"flex", justifyContent:"space-between", alignItems:"center" }}>
                        <span>📋 {contestadas}/{pregs.length} · P{actual+1}</span>
                        <span style={{ fontSize:16 }}>▼</span>
                    </button>
                ) : (
                    <div style={{ flex:1, display:"flex", alignItems:"center",
                                  justifyContent:"space-between", background:"#e8eaf6",
                                  borderRadius:6, padding:"6px 10px", fontSize:13, fontWeight:"bold" }}>
                        <span>📋 Navegación</span>
                        <button onClick={() => setAbierto(false)}
                            style={{ background:"none", border:"none", fontSize:20,
                                     cursor:"pointer", lineHeight:1, color:"#555" }}>✕</button>
                    </div>
                )}
                <button onClick={onFinalizar}
                    style={{ ...S.btnDng, padding:"6px 12px", fontSize:13, whiteSpace:"nowrap" }}>
                    ⚡ Fin
                </button>
            </div>
            {abierto && (
                <div style={{ display:"grid", gridTemplateColumns:"repeat(5,1fr)",
                              gap:5, padding:"8px 8px 10px" }}>
                    {pregs.map((_,i) => {
                        const bg = resps[i] ? "#A0D2FF" : "#f0f0f0";
                        return (
                            <div key={i} onClick={() => { onIr(i); setAbierto(false); }}
                                style={{ aspectRatio:"1", display:"flex", alignItems:"center",
                                         justifyContent:"center", borderRadius:7, cursor:"pointer",
                                         background:bg, fontSize:12,
                                         fontWeight: actual===i ? "bold" : "normal",
                                         border: actual===i ? "3px solid #1a237e" : "1px solid #ddd" }}>
                                {i+1}
                            </div>
                        );
                    })}
                </div>
            )}
        </div>
    );
}

// ============================================================================
// NAV ESCRITORIO
// ============================================================================
function NavDesktop({ pregs, resps, actual, onIr, onFinalizar }) {
    return (
        <div style={{ background:"#fff", borderRadius:10, padding:10,
                      boxShadow:"0 2px 5px rgba(0,0,0,.1)", position:"sticky", top:8 }}>
            <div style={{ display:"flex", justifyContent:"space-between",
                          alignItems:"center", marginBottom:8 }}>
                <span style={{ fontWeight:"bold", fontSize:13 }}>📋</span>
                <button onClick={onFinalizar}
                    style={{ ...S.btnDng, padding:"3px 8px", fontSize:11 }}>⚡</button>
            </div>
            <div style={{ display:"grid", gridTemplateColumns:"1fr 1fr", gap:4 }}>
                {pregs.map((_,i) => {
                    const bg = resps[i] ? "#A0D2FF" : "#f0f0f0";
                    return (
                        <div key={i} onClick={() => onIr(i)}
                            style={{ aspectRatio:"1", display:"flex", alignItems:"center",
                                     justifyContent:"center", borderRadius:6, cursor:"pointer",
                                     background:bg, fontSize:11,
                                     fontWeight: actual===i ? "bold" : "normal",
                                     border: actual===i ? "2px solid #1a237e" : "1px solid #ddd",
                                     transition:"transform .1s" }}
                            onMouseEnter={e => e.currentTarget.style.transform="scale(1.1)"}
                            onMouseLeave={e => e.currentTarget.style.transform="none"}>
                            {i+1}
                        </div>
                    );
                })}
            </div>
            <div style={{ marginTop:8, fontSize:10, color:"#aaa", textAlign:"center" }}>
                1-4 · ←→ · Ctrl+↵
            </div>
        </div>
    );
}

// ============================================================================
// VISTA PREGUNTA
// ============================================================================
function PreguntaView({ pregunta, idx, total, respuesta, onResponder }) {
    if (!pregunta) return null;

    return (
        <div style={{ ...S.card, maxWidth:"100%" }}>
            <div style={{ fontSize:12, color:"#888", marginBottom:8 }}>
                Pregunta {idx+1} de {total}
            </div>

            <div style={S.enun}
                dangerouslySetInnerHTML={{ __html: pregunta.pregunta }} />

            <div style={{ display:"flex", gap:8, flexWrap:"nowrap" }}>
                {pregunta.respuestas.map((resp, i) => {
                    const sel = respuesta?.seleccionada === i;
                    return (
                        <div key={i} onClick={() => onResponder(i)}
                            style={{ flex:1, display:"flex", flexDirection:"column",
                                     alignItems:"center", justifyContent:"center",
                                     padding:"12px 6px",
                                     background: sel ? "#c5cae9" : "#fafafa",
                                     border:     sel ? "2px solid #1a237e" : "2px solid #e0e0e0",
                                     borderRadius:8, cursor:"pointer",
                                     transition:"all .15s", textAlign:"center", userSelect:"none",
                                     boxShadow: sel ? "0 2px 8px rgba(26,35,126,.2)" : "none" }}
                            onMouseEnter={e => e.currentTarget.style.transform="translateY(-2px)"}
                            onMouseLeave={e => e.currentTarget.style.transform="none"}>
                            <div style={{ width:28, height:28,
                                          background: sel ? "#1a237e" : "#9fa8da",
                                          color:"white", borderRadius:"50%",
                                          display:"flex", alignItems:"center", justifyContent:"center",
                                          fontWeight:"bold", fontSize:13, marginBottom:6 }}>
                                {LETRAS[i]}
                            </div>
                            <div style={{ fontFamily:"monospace", fontSize:"1.1rem",
                                          fontWeight: sel ? "bold" : "normal" }}>
                                {resp}
                            </div>
                        </div>
                    );
                })}
            </div>

            <div style={{ marginTop:10, fontSize:12, color:"#888", textAlign:"center" }}>
                {respuesta
                    ? "✓ Respuesta guardada · pincha otra para cambiar · pincha la misma para desmarcar"
                    : "Selecciona una respuesta"}
            </div>
        </div>
    );
}

// ============================================================================
// EXPLICACIÓN
// ============================================================================
function ExplicacionView({ pregunta, idx, total, respuesta, tiempo }) {
    if (!pregunta) return null;
    const ok     = respuesta && respuesta.seleccionada === pregunta.correcta;
    const estado = !respuesta ? "❓ No respondida" : ok ? "✅ Correcta" : "❌ Incorrecta";
    const bgE    = !respuesta ? "#fff3e0" : ok ? "#e8f5e8" : "#ffebee";

    return (
        <div style={S.card}>
            <div style={{ display:"flex", justifyContent:"space-between",
                          alignItems:"center", marginBottom:12 }}>
                <span style={{ color:"#888", fontSize:13 }}>Pregunta {idx+1} / {total}</span>
                <span style={{ background:bgE, padding:"4px 14px",
                               borderRadius:20, fontWeight:"bold", fontSize:13 }}>
                    {estado}
                </span>
            </div>

            <div style={S.enun}
                dangerouslySetInnerHTML={{ __html: pregunta.pregunta }} />

            <div style={{ display:"flex", gap:8, flexWrap:"nowrap", marginBottom:16 }}>
                {pregunta.respuestas.map((resp, i) => {
                    let bg2 = "#fafafa", brd = "1px solid #ddd";
                    if (i === pregunta.correcta)
                        { bg2="#e8f5e8"; brd="2px solid #4CAF50"; }
                    if (respuesta && i === respuesta.seleccionada && i !== pregunta.correcta)
                        { bg2="#ffcdd2"; brd="2px solid #f44336"; }
                    return (
                        <div key={i} style={{ flex:1, display:"flex", flexDirection:"column",
                                              alignItems:"center", justifyContent:"center",
                                              padding:"12px 6px", background:bg2, border:brd,
                                              borderRadius:8, textAlign:"center" }}>
                            <div style={{ width:28, height:28, background:"#1a237e", color:"white",
                                          borderRadius:"50%", display:"flex", alignItems:"center",
                                          justifyContent:"center", fontWeight:"bold",
                                          fontSize:13, marginBottom:6 }}>
                                {LETRAS[i]}
                            </div>
                            <div style={{ fontFamily:"monospace", fontSize:"1.05rem", fontWeight:"bold" }}>
                                {resp}
                            </div>
                            {i === pregunta.correcta &&
                                <span style={{ color:"#4CAF50", fontSize:16, marginTop:4 }}>✓</span>}
                        </div>
                    );
                })}
            </div>

            <div style={{ padding:"12px 16px", background:"#fff3e0",
                          borderLeft:"5px solid #ff9800", borderRadius:8 }}>
                <strong>📘 Explicación:</strong>
                <div style={{ marginTop:6, fontSize:14, lineHeight:1.7 }}
                    dangerouslySetInnerHTML={{ __html: pregunta.explicacion || "—" }} />
            </div>

            <div style={{ textAlign:"center", fontSize:12, color:"#aaa", marginTop:10 }}>
                ⏱️ {fmt(tiempo)} en esta pregunta
            </div>
        </div>
    );
}

// ============================================================================
// RESULTADOS
// ============================================================================
function Resultados({ pregs, resps, tiempos, tGlobal, isMobile, onVerExpl, onNuevoTest }) {
    const [abierto, setAbierto] = useState(!isMobile);
    const total    = pregs.length;
    const aciertos = resps.filter(r => r && r.seleccionada === r.correcta).length;
    const fallos   = resps.filter(r => r && r.seleccionada !== r.correcta).length;
    const noResp   = total - aciertos - fallos;
    const pct      = total ? Math.round(aciertos / total * 100) : 0;

    const grid = (
        <div style={{ display:"grid", gridTemplateColumns:"repeat(5,1fr)", gap:5, margin:"10px 0" }}>
            {pregs.map((_,i) => {
                const r  = resps[i];
                const bg = !r ? "#ffccbc" : r.seleccionada===r.correcta ? "#c8e6c9" : "#ffcdd2";
                return (
                    <div key={i} onClick={() => onVerExpl(i)}
                        style={{ aspectRatio:"1", display:"flex", alignItems:"center",
                                 justifyContent:"center", background:bg, borderRadius:6,
                                 cursor:"pointer", fontWeight:"bold", border:"1px solid #ddd",
                                 fontSize:12, transition:"transform .1s" }}
                        onMouseEnter={e => e.currentTarget.style.transform="scale(1.1)"}
                        onMouseLeave={e => e.currentTarget.style.transform="none"}>
                        {i+1}
                    </div>
                );
            })}
        </div>
    );

    const resumen = (
        <div style={{ background:"#f5f5f5", borderRadius:8, padding:12, marginBottom:8 }}>
            <div style={{ display:"grid", gridTemplateColumns:"1fr 1fr 1fr",
                          gap:6, textAlign:"center", marginBottom:10 }}>
                {[["✅",aciertos,"#4CAF50"],["❌",fallos,"#f44336"],["—",noResp,"#999"]].map(([ic,val,col]) => (
                    <div key={ic}>
                        <div style={{ fontSize:11, color:"#666" }}>{ic}</div>
                        <div style={{ fontSize:26, fontWeight:"bold", color:col }}>{val}</div>
                    </div>
                ))}
            </div>
            <div style={{ height:12, background:"#eee", borderRadius:6, marginBottom:8 }}>
                <div style={{ width:`${pct}%`, height:"100%", background:"#4CAF50", borderRadius:6 }} />
            </div>
            <div style={{ display:"grid", gridTemplateColumns:"1fr 1fr", gap:4, fontSize:12 }}>
                <div><b>Total:</b> {total}</div>
                <div><b>Aciertos:</b> {pct}%</div>
                <div><b>Tiempo:</b> {fmt(tGlobal)}</div>
                <div><b>Media:</b> {total ? (tGlobal/total).toFixed(1) : 0}s/p</div>
            </div>
        </div>
    );

    if (isMobile) return (
        <div style={{ background:"#fff", borderRadius:10, boxShadow:"0 2px 5px rgba(0,0,0,.1)" }}>
            <div style={{ display:"flex", alignItems:"center", padding:"8px 12px", gap:6 }}>
                {!abierto ? (
                    <button onClick={() => setAbierto(true)}
                        style={{ flex:1, background:"#f0f0f0", border:"1px solid #ddd",
                                 borderRadius:6, padding:"6px 10px", cursor:"pointer", fontSize:13,
                                 display:"flex", justifyContent:"space-between" }}>
                        <span>📊 ✅{aciertos} ❌{fallos} · {pct}%</span>
                        <span>▼</span>
                    </button>
                ) : (
                    <div style={{ flex:1, display:"flex", justifyContent:"space-between",
                                  alignItems:"center", background:"#e8eaf6",
                                  borderRadius:6, padding:"6px 10px", fontWeight:"bold", fontSize:13 }}>
                        <span>📊 Resultados</span>
                        <button onClick={() => setAbierto(false)}
                            style={{ background:"none", border:"none", fontSize:20, cursor:"pointer" }}>✕</button>
                    </div>
                )}
            </div>
            {abierto && (
                <div style={{ padding:"0 12px 12px" }}>
                    {resumen}{grid}
                    <button onClick={onNuevoTest}
                        style={{ ...S.btnPri, background:"#4CAF50", marginTop:4 }}>
                        🔄 Nuevo test
                    </button>
                </div>
            )}
        </div>
    );

    return (
        <div style={{ background:"#fff", borderRadius:10, padding:14,
                      boxShadow:"0 2px 5px rgba(0,0,0,.1)" }}>
            <div style={{ fontWeight:"bold", fontSize:15, textAlign:"center", marginBottom:10 }}>
                📊 RESULTADOS
            </div>
            {resumen}
            <div style={{ fontWeight:"bold", fontSize:13, marginBottom:6 }}>📋 CORRECCIÓN</div>
            {grid}
            <button onClick={onNuevoTest}
                style={{ ...S.btnPri, background:"#4CAF50", marginTop:8 }}>
                🔄 Nuevo test
            </button>
        </div>
    );
}
