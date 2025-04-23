document.getElementById("continuar").addEventListener("click", function() {
    const usuario = document.getElementById('usuario').value;
    const alerta_usuario = document.getElementById('alerta_usuario')
    const correo = document.getElementById('correo').value;
    const alerta_correo = document.getElementById('alerta_correo')
    const contraseña = document.getElementById('contraseña').value;
    const alerta_contraseña_corta = document.getElementById('alerta_contraseña_corta')
    const alerta_contraseña_larga = document.getElementById('alerta_contraseña_larga')

    if (usuario.length > 1) {
        alerta_usuario.classList.add("oculto")
    } 
    if (correo.length > 1) {
        alerta_correo.classList.add("oculto")
    } 
    if (contraseña.length > 8) {
        alerta_contraseña_corta.classList.add("oculto")
    } 
    if (contraseña.length < 25) {
        alerta_contraseña_larga.classList.add("oculto")
    }
    
    if (usuario.length < 1) {
        alerta_usuario.classList.remove("oculto")
    } else if (correo.length < 1) {
        alerta_correo.classList.remove("oculto")
    } else if (contraseña.length < 8) {
        alerta_contraseña_corta.classList.remove("oculto")
    } else if (contraseña.length > 25) {
        alerta_contraseña_larga.classList.remove("oculto")
    } else {
        window.location.href = "meta.html";
    }

});