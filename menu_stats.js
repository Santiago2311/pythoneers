window.addEventListener('DOMContentLoaded', () => {
    fetch('menu_stats.php')
    .then(response => response.json())
    .then(data => {
        const puntaje = data.puntaje;
        const racha = data.racha;
        const monedas = data.monedas;
        const vidas = data.vidas;

        document.getElementById('vidas_info').textContent = vidas;
        document.getElementById('racha_info').textContent = racha;
        document.getElementById('monedas_info').textContent = monedas;
        document.getElementById('puntaje_info').textContent = puntaje;
    });
});