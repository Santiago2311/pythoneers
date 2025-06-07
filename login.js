document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');

    if (error === 'usuario') {
        document.getElementById('alerta_correo').classList.remove('oculto');
    } else if (error === 'contrasena') {
        document.getElementById('alerta_contra').classList.remove('oculto');
    }
});