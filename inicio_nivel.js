window.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-nivel').forEach(btn => {
        btn.addEventListener('click', () => {
            const nivelNum = parseInt(btn.dataset.nivelNum);
            fetch('niveles.php')
            .then(response => response.json())
            .then(data => {
                const leccionId = data.leccion_actual;
                console.log('Datos recibidos:', data);
                console.log('Lección ID:', data.leccion_actual);
                if (data.nivel_actual > nivelNum) {
                    window.location.href = `inicio_leccion.html?leccion_id=leccion_practica&nivel_num=${nivelNum}`;
                } else {
                    window.location.href = `inicio_leccion.html?leccion_id=${leccionId}&nivel_num=${nivelNum}`;
                }
    });
        });
    });
});