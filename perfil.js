window.addEventListener('DOMContentLoaded', () => {
    fetch('perfil.php')
    .then(response => response.json())
    .then(data => {
        const nombre = data.nombre;
        const apellidos = data.apellidos;
        const correo = data.correo;
        const productos = data.productos;

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

        document.getElementById("progreso_completado").style.width = data.progreso;
        document.getElementById("porcentaje").textContent = data.progreso+"%";
    });
});