// Genera una operación de sumas y restas encadenadas.
function randInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function distractores(correcta, cantidad = 3) {
    const usados = new Set([correcta]);
    const opciones = [];
    while (opciones.length < cantidad) {
        const delta = randInt(1, 12) * (Math.random() < 0.5 ? -1 : 1);
        const val = correcta + delta;
        if (!usados.has(val)) {
            usados.add(val);
            opciones.push(val);
        }
    }
    return opciones;
}

export default function generarSumasRestas(params = {}) {
    const {
        num_sumandos = 3,
        min_numero   = 1,
        max_numero   = 99,
    } = params;

    const numeros   = Array.from({ length: num_sumandos }, () => randInt(min_numero, max_numero));
    const operadores = Array.from({ length: num_sumandos - 1 }, () => (Math.random() < 0.5 ? "+" : "−"));

    let resultado = numeros[0];
    let expresion = String(numeros[0]);
    for (let i = 0; i < operadores.length; i++) {
        resultado += operadores[i] === "+" ? numeros[i + 1] : -numeros[i + 1];
        expresion += ` ${operadores[i]} ${numeros[i + 1]}`;
    }

    const opciones = [resultado, ...distractores(resultado)];
    for (let i = opciones.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [opciones[i], opciones[j]] = [opciones[j], opciones[i]];
    }

    return {
        pregunta:    `${expresion} = ?`,
        respuestas:  opciones.map(String),
        correcta:    opciones.indexOf(resultado),
        explicacion: `Operando en orden: ${expresion} = <strong>${resultado}</strong>.`,
    };
}
