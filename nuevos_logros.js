let nuevos_logros = false;
window.addEventListener('DOMContentLoaded', () => {
    fetch('nuevos_logros.php')
    .then(response => response.json())
    .then(data => {
        console.log(data.logro_nivel_conseguido);
        if (data.logro_nivel_conseguido > 0) {
            const div_logro = document.createElement("div");
            const img_logro = document.createElement("img");
            img_logro.src = "assets/logro_niveles_desbloqueado.png";
            const texto_logro = document.createElement("p");
            if (data.logro_nivel_conseguido == 1) {
                texto_logro.textContent = "1 nivel";
            } else if (data.logro_nivel_conseguido == 24) {
                texto_logro.textContent = "Todos los niveles";
            } else {
                texto_logro.textContent = data.logro_nivel_conseguido + " niveles";
            }
            div_logro.appendChild(img_logro);
            div_logro.appendChild(texto_logro);
            document.getElementById('logros').appendChild(div_logro);
            nuevos_logros = true;
        }

        if (data.logro_racha_conseguido > 0) {
            const div_logro = document.createElement("div");
            const img_logro = document.createElement("img");
            img_logro.src = "assets/logro_racha_desbloqueado.png";
            const texto_logro = document.createElement("p");
            if (data.logro_racha_conseguido == 1) {
                texto_logro.textContent = "1 dia de racha";
            } else {
                texto_logro.textContent = data.logro_racha_conseguido + " dias de racha";
            }
            div_logro.appendChild(img_logro);
            div_logro.appendChild(texto_logro);
            document.getElementById('logros').appendChild(div_logro);
            nuevos_logros = true;
        }

        if (data.logro_tienda_conseguido > 0) {
            const div_logro = document.createElement("div");
            const img_logro = document.createElement("img");
            img_logro.src = "assets/logro_tienda_desbloqueado.png";
            const texto_logro = document.createElement("p");
            if (data.logro_tienda_conseguido == 1) {
                texto_logro.textContent = "25% de tienda";
            } else {
                texto_logro.textContent = data.logro_tienda_conseguido + " % de tienda";
            }
            div_logro.appendChild(img_logro);
            div_logro.appendChild(texto_logro);
            document.getElementById('logros').appendChild(div_logro);
            nuevos_logros = true;
        }

        if (data.logro_lp_conseguido > 0) {
            const div_logro = document.createElement("div");
            const img_logro = document.createElement("img");
            img_logro.src = "assets/logro_lecciones_perfectas_desbloqueado.png";
            const texto_logro = document.createElement("p");
            texto_logro.textContent = data.logro_lp_conseguido + " lecciones perfectas";
            div_logro.appendChild(img_logro);
            div_logro.appendChild(texto_logro);
            document.getElementById('logros').appendChild(div_logro);
            nuevos_logros = true;
        }

        if (data.logro_puntaje_conseguido > 0) {
            const div_logro = document.createElement("div");
            const img_logro = document.createElement("img");
            img_logro.src = "assets/logro_puntaje_desbloqueado.png";
            const texto_logro = document.createElement("p");
            texto_logro.textContent = data.logro_puntaje_conseguido + " estrellas";
            div_logro.appendChild(img_logro);
            div_logro.appendChild(texto_logro);
            document.getElementById('logros').appendChild(div_logro);
            nuevos_logros = true;
        }

        if (nuevos_logros == false) {
            document.getElementById('titulo').textContent = "No conseguiste nuevos logros";
        }
    });
});