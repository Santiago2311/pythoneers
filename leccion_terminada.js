document.getElementById("siguiente").addEventListener("click", function() {
    window.location = "nuevos_logros.html";
});

fetch('actualizar_stats.php')
.then(response => {
    console.log('Status:', response.status);
    return response.json();
})
.then(data => {
    console.log('Respuesta del servidor:', data);

    if (data.error) {
    console.error('Error del servidor:', data.error);
    return;
    }

    document.getElementById('racha').textContent = data.racha;
    document.getElementById('monedas').textContent = data.monedas;
    document.getElementById('estrellas').textContent = data.estrellas;
    document.getElementById('calificacion').textContent = data.calificacion + '%';
})
.catch(error => {
    console.error('Error de red:', error);
});
