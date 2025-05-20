window.addEventListener('DOMContentLoaded', () => {
    console.log("comprar.js cargado");
    document.querySelectorAll('form[data-comprar="1"]').forEach(form => {
        form.addEventListener('submit', function (e) {
        e.preventDefault(); // Previene que se recargue la página

        const formData = new FormData(form);

        fetch('tienda.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            console.log('Ya basta');
            if (data.fondos_insuficientes) {
            // Mostrar alerta correspondiente
            const id = data.fondos_insuficientes;
            const alerta = document.getElementById('alerta_' + id);
            if (alerta) {
                alerta.classList.remove('oculto');
            }
        } else {
            // Redirige si no hay error
            window.location.href = "tienda.html";
        }
        })
        .catch(err => {
            console.error("Error al procesar la compra:", err);
        });
        });
    });
});