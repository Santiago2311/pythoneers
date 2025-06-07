window.addEventListener('DOMContentLoaded', () => {
    fetch('perfil.php')
    .then(response => response.json())
    .then(data => {
        const nombre = data.nombre;
        const apellidos = data.apellidos;
        const correo = data.correo;
        const productos = data.productos;
        const logros_conseguidos =  data.logros_conseguidos;

        console.log(logros_conseguidos);

        document.getElementById('usuario_container').textContent = nombre + " " + apellidos;
        document.getElementById('correo').textContent = correo;

        productos.forEach(item => {
            if (item.nombre_producto == 'vacio') {
                document.getElementById(item.categoria).classList.add('oculto');
            } else {
                document.getElementById(item.categoria).src = "assets/"+item.nombre_producto+".png";
            }
            // document.getElementById(item.categoria).src = "assets/"+item.nombre_producto+".png";
        });

        document.getElementById("progreso_completado").style.width = data.progreso+"%";
        document.getElementById("porcentaje").textContent = data.progreso+"%";

        const logros = document.querySelectorAll('.logro');

        logros.forEach((logro) => {
            const idLogro = parseInt(logro.id, 10);
            
            if (idLogro in logros_conseguidos) {
                logro.src = `assets/logro_${logros_conseguidos[idLogro]}_desbloqueado.png`;
                // Aquí podrías hacer algo adicional, como cambiar la clase, ocultarlo, etc.
            }

        })
    });
});