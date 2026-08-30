// Genera un problema de velocidad, distancia y tiempo (v = d / t).
function randInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function distractores(correcta, cantidad = 3) {
    const usados = new Set([correcta]);
    const opciones = [];
    while (opciones.length < cantidad) {
        const delta = randInt(1, Math.max(2, Math.round(correcta * 0.2) + 2)) * (Math.random() < 0.5 ? -1 : 1);
        const val = correcta + delta;
        if (val > 0 && !usados.has(val)) {
            usados.add(val);
            opciones.push(val);
        }
    }
    return opciones;
}

export default function generarTiempoVelocidad(params = {}) {
    const {
        max_velocidad = 120,
        min_velocidad = 40,
        max_tiempo    = 5,
        min_tiempo    = 1,
    } = params;

    const velocidad = randInt(min_velocidad, max_velocidad);
    const tiempo    = randInt(min_tiempo, max_tiempo);
    const distancia = velocidad * tiempo;

    const incognitas = ["distancia", "velocidad", "tiempo"];
    const incognita   = incognitas[randInt(0, incognitas.length - 1)];

    let pregunta, resultado, explicacion;
    if (incognita === "distancia") {
        pregunta    = `Un vehículo circula a ${velocidad} km/h durante ${tiempo} horas. ¿Qué distancia recorre (en km)?`;
        resultado   = distancia;
        explicacion = `distancia = velocidad × tiempo = ${velocidad} × ${tiempo} = <strong>${distancia}</strong> km.`;
    } else if (incognita === "velocidad") {
        pregunta    = `Un vehículo recorre ${distancia} km en ${tiempo} horas. ¿A qué velocidad media circula (en km/h)?`;
        resultado   = velocidad;
        explicacion = `velocidad = distancia ÷ tiempo = ${distancia} ÷ ${tiempo} = <strong>${velocidad}</strong> km/h.`;
    } else {
        pregunta    = `Un vehículo recorre ${distancia} km a una velocidad de ${velocidad} km/h. ¿Cuánto tiempo tarda (en horas)?`;
        resultado   = tiempo;
        explicacion = `tiempo = distancia ÷ velocidad = ${distancia} ÷ ${velocidad} = <strong>${tiempo}</strong> horas.`;
    }

    const opciones = [resultado, ...distractores(resultado)];
    for (let i = opciones.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [opciones[i], opciones[j]] = [opciones[j], opciones[i]];
    }

    return {
        pregunta,
        respuestas:  opciones.map(String),
        correcta:    opciones.indexOf(resultado),
        explicacion,
    };
}
