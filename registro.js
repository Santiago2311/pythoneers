document.getElementById("continuar").addEventListener("click", function() {
    const nombre = document.getElementById('nombre').value;
    const alerta_nombre = document.getElementById('alerta_nombre');
    const apellidos = document.getElementById('apellidos').value;
    const alerta_apellido = document.getElementById('alerta_apellido')
    const correo = document.getElementById('correo').value;
    const alerta_correo = document.getElementById('alerta_correo')
    const contraseña = document.getElementById('contraseña').value;
    const alerta_contraseña_corta = document.getElementById('alerta_contraseña_corta')
    const alerta_contraseña_larga = document.getElementById('alerta_contraseña_larga')
    const alerta_falta_numero = document.getElementById('alerta_falta_numero')

    if (nombre.length > 1) {
        alerta_nombre.classList.add("oculto")
    }
    if (apellidos.length > 1) {
        alerta_apellido.classList.add("oculto")
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
    if (/\d/.test(contraseña)) {
        alerta_falta_numero.classList.add("oculto"); 
    }

    
    if (nombre.length < 1) {
        alerta_nombre.classList.remove("oculto")
    } else if (apellidos.length < 1) {
        alerta_apellido.classList.remove("oculto")
    } else if (correo.length < 1) {
        alerta_correo.classList.remove("oculto")
    } else if (contraseña.length < 8) {
        alerta_contraseña_corta.classList.remove("oculto")
    } else if (contraseña.length > 25) {
        alerta_contraseña_larga.classList.remove("oculto")
    } else if (!/\d/.test(contraseña)) {
        alerta_falta_numero.classList.remove("oculto");
    } else {
        window.location.href = "meta.html";
    }

});