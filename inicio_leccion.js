window.addEventListener('DOMContentLoaded', () => {
    console.log('URL actual:', window.location.href);
    const params = new URLSearchParams(window.location.search);
    const leccionId = params.get('leccion_id');
    console.log(`Valor de leccion_id: ${leccionId}`);

    // Cambia el texto del título
    const titulo = document.getElementById('titulo');
    if (leccionId) {
        if (leccionId === 'leccion_practica') {
            titulo.textContent = 'Comencemos la lección de práctica';
        } else {
            titulo.textContent = `Comencemos la lección ${leccionId}`;
        }
    }

    setTimeout(() => {
        // Cambia esta URL por la que necesites
        window.location.href = 'pregunta_4opciones.html';
    }, 1000);
});
