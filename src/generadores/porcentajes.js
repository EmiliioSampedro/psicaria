// Genera un problema de porcentajes.
function randInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function distractores(correcta, cantidad = 3) {
    const usados = new Set([correcta]);
    const opciones = [];
    while (opciones.length < cantidad) {
        const delta = randInt(1, Math.max(2, Math.round(correcta * 0.2) + 2)) * (Math.random() < 0.5 ? -1 : 1);
        const val = Math.round((correcta + delta) * 100) / 100;
        if (val > 0 && !usados.has(val)) {
            usados.add(val);
            opciones.push(val);
        }
    }
    return opciones;
}

export default function generarPorcentajes(params = {}) {
    const {
        max_numero = 500,
        min_numero = 50,
    } = params;

    const porcentajesPosibles = [5, 10, 15, 20, 25, 30, 40, 50, 75];
    const porcentaje = porcentajesPosibles[randInt(0, porcentajesPosibles.length - 1)];
    const cantidad   = randInt(min_numero, max_numero);
    const resultado  = Math.round((cantidad * porcentaje) / 100);

    const opciones = [resultado, ...distractores(resultado)];
    for (let i = opciones.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [opciones[i], opciones[j]] = [opciones[j], opciones[i]];
    }

    return {
        pregunta:    `¿Cuánto es el ${porcentaje}% de ${cantidad}?`,
        respuestas:  opciones.map(String),
        correcta:    opciones.indexOf(resultado),
        explicacion: `${porcentaje}% de ${cantidad} = (${cantidad} × ${porcentaje}) ÷ 100 = <strong>${resultado}</strong>.`,
    };
}
