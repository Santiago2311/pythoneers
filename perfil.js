window.addEventListener('DOMContentLoaded', () => {
    fetch('perfil.php')
    .then(response => response.json())
    .then(data => {
        const nombre = data.nombre;
        const apellidos = data.apellidos;
        const correo = data.correo;

        document.getElementById('usuario_container').textContent = nombre + " " + apellidos;
        document.getElementById('correo').textContent = correo;
    });
});