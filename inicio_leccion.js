window.addEventListener('DOMContentLoaded', () => {
    console.log('URL actual:', window.location.href);
    const params = new URLSearchParams(window.location.search);
    const leccionId = params.get('leccion_id');
    const nivelId = params.get('nivel_num');
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

    fetch('generar_preguntas.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ nivel_num: nivelId })
    })
    .then(response => response.text())
    .then(data => {
        console.log('Respuesta del servidor:', data);
        console.log('Estoy hasta mi madre');
        setTimeout(() => {
            window.location.href = 'pregunta.html';
        }, 1000);
    })
});
