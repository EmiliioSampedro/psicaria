// Genera una serie numérica y pregunta por el siguiente elemento.
function randInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function distractores(correcta, cantidad = 3) {
    const usados = new Set([correcta]);
    const opciones = [];
    while (opciones.length < cantidad) {
        const delta = randInt(1, 10) * (Math.random() < 0.5 ? -1 : 1);
        const val = correcta + delta;
        if (!usados.has(val)) {
            usados.add(val);
            opciones.push(val);
        }
    }
    return opciones;
}

function serieAritmetica(longitud) {
    const inicio = randInt(1, 20);
    const paso   = randInt(2, 9) * (Math.random() < 0.5 ? -1 : 1);
    const serie  = Array.from({ length: longitud }, (_, i) => inicio + i * paso);
    return { serie, siguiente: inicio + longitud * paso, explicacion: `Cada término suma ${paso}.` };
}

function serieGeometrica(longitud) {
    const inicio = randInt(1, 5);
    const razon  = randInt(2, 3);
    const serie  = Array.from({ length: longitud }, (_, i) => inicio * Math.pow(razon, i));
    return { serie, siguiente: inicio * Math.pow(razon, longitud), explicacion: `Cada término se multiplica por ${razon}.` };
}

export default function generarSeries(params = {}) {
    const {
        longitud_serie = 5,
        tipo           = "aritmetica",
    } = params;

    const generador = tipo === "geometrica" ? serieGeometrica : serieAritmetica;
    const { serie, siguiente, explicacion } = generador(longitud_serie);

    const opciones = [siguiente, ...distractores(siguiente)];
    for (let i = opciones.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [opciones[i], opciones[j]] = [opciones[j], opciones[i]];
    }

    return {
        pregunta:    `${serie.join(", ")}, ... ?`,
        respuestas:  opciones.map(String),
        correcta:    opciones.indexOf(siguiente),
        explicacion: `${explicacion} El siguiente término es <strong>${siguiente}</strong>.`,
    };
}
