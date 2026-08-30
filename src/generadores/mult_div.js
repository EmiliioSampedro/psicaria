// Genera una operación de multiplicación o división.
function randInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function distractores(correcta, cantidad = 3) {
    const usados = new Set([correcta]);
    const opciones = [];
    while (opciones.length < cantidad) {
        const delta = randInt(1, Math.max(2, Math.round(correcta * 0.15) + 2)) * (Math.random() < 0.5 ? -1 : 1);
        const val = correcta + delta;
        if (val > 0 && !usados.has(val)) {
            usados.add(val);
            opciones.push(val);
        }
    }
    return opciones;
}

export default function generarMultDiv(params = {}) {
    const {
        max_numero = 12,
        min_numero = 2,
    } = params;

    const esMultiplicacion = Math.random() < 0.5;
    let a, b, resultado, expresion;

    if (esMultiplicacion) {
        a = randInt(min_numero, max_numero);
        b = randInt(min_numero, max_numero);
        resultado = a * b;
        expresion = `${a} × ${b}`;
    } else {
        b = randInt(min_numero, max_numero);
        resultado = randInt(min_numero, max_numero);
        a = b * resultado;
        expresion = `${a} ÷ ${b}`;
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
        explicacion: esMultiplicacion
            ? `${a} × ${b} = <strong>${resultado}</strong>.`
            : `${a} ÷ ${b} = <strong>${resultado}</strong>, ya que ${b} × ${resultado} = ${a}.`,
    };
}
