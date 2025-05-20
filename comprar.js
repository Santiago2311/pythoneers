// window.addEventListener('DOMContentLoaded', () => {
//     const forms = document.querySelectorAll('form[action="tienda.php"]');

//     fetch('comprar.php')
//     .then(response => response.json())
//     .then(data => {
//         console.log("Script cargado"); // Verifica que el JS se ejecute
//         document.querySelector('form').addEventListener('submit', function(e) {
//             e.preventDefault();
//             console.log("Formulario enviado"); // Verifica que el evento se capture
//         });
//         if (data.compra_exitosa == 0) {
//             const alerta_fondos_insuficientes = document.getElementById('alerta_fondos_insuficientes');
//             alerta_fondos_insuficientes.classList.remove("oculto")
//         } else if (data.compra_exitosa == 1) {
//             document.getElementById(data.nombre_producto).textContent = "Usar";
//             document.getElementById(data.nombre_producto).classList.add('usar');
//             document.getElementById(data.nombre_producto).classList.remove('comprar');
//         }
//     });
// });

window.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('form[action="tienda.php"]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Obtén el valor del input hidden
            const nombreProducto = form.querySelector('[name="nombre_producto"]').value;

            // // Mostrar loader o estado de carga (opcional)
            // const boton = form.querySelector('button[type="submit"]');
            // boton.disabled = true;
            // boton.textContent = "Procesando...";

            fetch('comprar.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `nombre_producto=${encodeURIComponent(nombreProducto)}`
            })
            .then(response => response.json())
            // .then(response => {
            //     console.log("Status:", response.status);
            //     if (!response.ok) {
            //         throw new Error(`Error HTTP: ${response.status}`);
            //     }
            //     return response.json();
            // })
            .then(data => {
                
                // // Reiniciar el botón
                // boton.disabled = false;
                
                if (data.compra_exitosa === 0) {
                    // Mostrar alerta de fondos insuficientes
                    const alerta = form.querySelector('#alerta_fondos_insuficientes');
                    alerta.classList.remove("oculto");
                    
                    // Restaurar texto del botón
                    boton.textContent = "Comprar";
                    
                } else if (data.compra_exitosa === 1) {
                    // Actualizar botón a estado "Usar"
                    boton.textContent = "Usar";
                    boton.classList.add('usar');
                    boton.classList.remove('comprar');
                    
                    // Ocultar alerta si estaba visible
                    const alerta = form.querySelector('#alerta_fondos_insuficientes');
                    alerta.classList.add("oculto");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                // Restaurar el botón en caso de error
                boton.disabled = false;
                boton.textContent = "Comprar";
                
                // Mostrar mensaje de error al usuario
                alert("Ocurrió un error. Por favor intenta nuevamente.");
            });
        });
    });
});