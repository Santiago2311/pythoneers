document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("continuar").addEventListener("click", function(chequeo) {
        console.log("Botón de continuar presionado");
        
        let nombre = document.getElementById('nombre').value;
        const alerta_nombre = document.getElementById('alerta_nombre');
        const apellidos = document.getElementById('apellidos').value;
        const alerta_apellido = document.getElementById('alerta_apellido')
        const correo = document.getElementById('correo').value;
        const alerta_correo = document.getElementById('alerta_correo')
        const alerta_arroba = document.getElementById('alerta_arroba')
        const contraseña = document.getElementById('password').value;
        const alerta_contra_corta = document.getElementById('alerta_contra_corta')
        const alerta_contra_larga = document.getElementById('alerta_contra_larga')
        const alerta_contra_nums = document.getElementById('alerta_contra_nums') 

        let error = false;

        if (nombre.length > 1) {
            alerta_nombre.classList.add("oculto")
        }
        if (apellidos.length > 1) {
            alerta_apellido.classList.add("oculto")
        }
        if (correo.length > 1) {
            alerta_correo.classList.add("oculto")
        } 
        if (correo.includes('@')) {
            alerta_arroba.classList.add("oculto")
        }
        if (contraseña.length > 8) {
            alerta_contra_corta.classList.add("oculto")
        } 
        if (contraseña.length < 25) {
            alerta_contra_larga.classList.add("oculto")
        }
        if (/\d/.test(contraseña)) {
            alerta_contra_nums.classList.add("oculto"); 
        }

        
        if (nombre.length < 1) {
            alerta_nombre.classList.remove("oculto")
            error = true;
        } if (apellidos.length < 1) {
            alerta_apellido.classList.remove("oculto")
            error = true;
        } if (correo.length < 1) {
            alerta_correo.classList.remove("oculto")
            error = true;
        } if (!correo.includes('@')) {
            alerta_arroba.classList.remove("oculto")
            error = true;
        } if (contraseña.length < 8) {
            alerta_contra_corta.classList.remove("oculto")
            error = true;
        } if (contraseña.length > 25) {
            alerta_contra_larga.classList.remove("oculto")
            error = true;
        } if (!/\d/.test(contraseña)) {
            alerta_contra_nums.classList.remove("oculto");
            error = true;
        }

        if (error) {
            chequeo.preventDefault();
        }

    });
});