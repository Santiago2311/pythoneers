// const easy = [1, 4, 7, 10, 13, 16, 19, 22];
// const mid = [2, 5, 8, 11, 14, 17, 20, 23];
// const hard = [3, 6, 9, 12, 15, 18, 21, 24]; 

window.addEventListener('DOMContentLoaded', () => {
    fetch('niveles.php')
    .then(response => response.json())
    .then(data => {
        if (data.nivel_actual) {
            desbloqueaNivel(data.nivel_actual);
        } else if (data.error) {
            alert("Error: " + data.error);
        }
    });
});

function desbloqueaNivel(nivel_actual) {
    for (let i = 1; i < 25; i++) {
        const nivel = document.getElementById('nivel_' + i);
        if (!nivel) continue;
        if (i <= nivel_actual) {
            if (i % 3 == 0) {
                const img = nivel.querySelector('img.manzana');
                if (img) {
                    img.src = 'assets/nivel_dificil.png';
                }
            } else if ((i % 3) == 2) {
                const img = nivel.querySelector('img.manzana');
                if (img) {
                    img.src = 'assets/nivel_medio.png';
                }
            } else {
                const img = nivel.querySelector('img.manzana');
                if (img) {
                    img.src = 'assets/nivel_facil.png';
                }
            }
            nivel.disabled = false; 
            nivel.classList.remove('bloqueado');
            
        } else {
            const img = nivel.querySelector('img.manzana');
                if (img) {
                    img.src = 'assets/nivel_bloqueado.png';
                }
            nivel.disabled = true; 
            nivel.classList.add('bloqueado');
        }
    }
}