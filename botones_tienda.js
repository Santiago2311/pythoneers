window.addEventListener('DOMContentLoaded', () => {
    fetch('botones_tienda.php')
    .then(response => response.json())
    .then(data => {
        const productos = data.productos_usuario;

        for (const [nombre_producto, activo] of Object.entries(productos)) {
            const btn = document.getElementById(nombre_producto);
            if (activo == false) {
                btn.textContent = "Usar";
                btn.classList.add('usar');
                btn.classList.remove('comprar', 'quitar');
                
            } else {
                btn.textContent = "Quitar";
                btn.classList.add('quitar');
                btn.classList.remove('usar', 'comprar');
            }
        }
    });
});