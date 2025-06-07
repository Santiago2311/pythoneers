let preguntas = []
let num_preguntas = []
let progreso = 0;
let indice_num_preguntas = 0;
let tiempo_inicio;
let intervalo;
let respuestas_correctas = 0;

fetch('generar_preguntas.php', {
    method: "GET"
})
.then(response => response.json())
.then(data => {
    preguntas = data.preguntas_map
    num_preguntas = data.num_preguntas
    vidas = data.vidas
    console.log(vidas)

    tiempo_inicio=Date.now();
    intervalo = setInterval(actualizarCronometro, 1000);

    mostrarPregunta();
    actualizarCronometro();
})
.catch(error => {
    console.error("Error al obtener preguntas:", error);
});

function mostrarPregunta() {
    const main = document.getElementById("main");
    main.innerHTML = "";

    const contenedor = document.getElementById("contenedor");
    contenedor.innerHTML = `
    <div id="top-bar">
        <div id="cerrar">X</div>
        <div id="barra-progreso">
            <div id="progreso"></div>
        </div>
        <div id="estado">
            <ul>
                <li><img id="icono" src="assets/reloj.png"></li>
                <li id="tiempo"></li>
                <li> </li>
                <li><img id="icono" src="assets/vidas.png"></li>
                <li id="vidas"></li>
            </ul>
        </div>
    </div>
    `;

    document.getElementById("cerrar").addEventListener("click", () => {
        if (confirm("¿Seguro que quieres salir de la leccion? Perderas tu progreso")) {
            enviarProgreso(false, vidas);
            window.location.href = "niveles.html";
        }
    });

    const img_serpiente = document.createElement("img");
    img_serpiente.src = "assets/serpientepregunta.jpeg";
    img_serpiente.id = "serpiente";
    main.appendChild(img_serpiente);
    const pregunta_div = document.createElement("div");
    pregunta_div.id = "pregunta"; 
    pregunta_div.innerHTML = "";
    if (indice_num_preguntas >= 10) {
        clearInterval(intervalo);

        const tiempo_pasado = Math.floor((Date.now() - tiempo_inicio) / 1000);

        enviarProgreso(true, vidas, tiempo_pasado);
        setTimeout(() => {
            window.location.href = "leccion_terminada.html";
        }, 100);
        return;
    }

    const id_pregunta_actual = num_preguntas[indice_num_preguntas].id_pregunta;
    const pregunta_actual = preguntas[id_pregunta_actual];
    const texto_pregunta = document.createElement("p");
    texto_pregunta.textContent = pregunta_actual.texto;
    pregunta_div.appendChild(texto_pregunta);

    if (pregunta_actual.imagen_pregunta) {
        const imagen_pregunta = document.createElement("img");
        imagen_pregunta.src = pregunta_actual.imagen_pregunta;
        imagen_pregunta.alt = "Imagen de la pregunta";
        pregunta_div.appendChild(imagen_pregunta);
    }

    main.appendChild(pregunta_div);
    contenedor.appendChild(main);
    //* visualizar respuesta

    if (num_preguntas[indice_num_preguntas].id_tipo == 3) { // *si es de input 
        const respuestas = document.createElement("input");
        respuestas.innerHTML = "";
        respuestas.type = "text";
        respuestas.id = "opcion_input"; // Le damos un id para recuperarlo
        contenedor.appendChild(respuestas);
        
        const botonEnviar = document.createElement("button");
        botonEnviar.textContent = "Enviar";
        botonEnviar.id = "btn-enviar"
        botonEnviar.addEventListener("click", () => {
        const respuestaUsuario = respuestas.value;
        verificarRespuesta(respuestaUsuario, "opcion_input");
    });
    contenedor.appendChild(botonEnviar);
    } else  if (num_preguntas[indice_num_preguntas].id_tipo == 1){ // * cuatro opciones
        const respuestas = document.createElement("div");
        respuestas.innerHTML = "";
        respuestas.id = "cuatro_opciones";
        pregunta_actual.respuestas.forEach((respuesta, indice) => {
            const opcion = document.createElement("button");
            const opcion_id = `respuesta-${indice}`;
            opcion.id = opcion_id;
            opcion.textContent = respuesta.contenido
            opcion.classList.add("option");

            if (respuesta.imagen_respuesta) {
                const imgResp = document.createElement("img");
                imgResp.src = respuesta.imagen_respuesta;
                imgResp.alt = "Imagen respuesta";
                opcion.appendChild(imgResp);
            }

            opcion.addEventListener("click", () => verificarRespuesta(respuesta, opcion_id));
            respuestas.appendChild(opcion);
        });
        const contenedor = document.getElementById("contenedor");
        contenedor.appendChild(respuestas);
    } else { //* verdadero falso
        const respuestas = document.createElement("div");
        respuestas.innerHTML = "";
        respuestas.id = "opciones_vf";
        pregunta_actual.respuestas.forEach((respuesta, indice) => {
            const opcion = document.createElement("button");
            const opcion_id = `respuesta-${indice}`;
            opcion.id = opcion_id;
            opcion.textContent = respuesta.contenido
            opcion.classList.add("option");

            if (respuesta.imagen_respuesta) {
                const imgResp = document.createElement("img");
                imgResp.src = respuesta.imagen_respuesta;
                imgResp.alt = "Imagen respuesta";
                opcion.appendChild(imgResp);
            }

            opcion.addEventListener("click", () => verificarRespuesta(respuesta, opcion_id));
            respuestas.appendChild(opcion);
        });
        const contenedor = document.getElementById("contenedor");
        contenedor.appendChild(respuestas);
    }

    document.getElementById("progreso").style.width = progreso + "%";
    document.getElementById("vidas").textContent = vidas;

}

function verificarRespuesta(respuesta_usuario, opcion_id) {
    if (num_preguntas[indice_num_preguntas].id_tipo == 3) {
        const pregunta_actual = preguntas[num_preguntas[indice_num_preguntas].id_pregunta];
        const esCorrecto = pregunta_actual.respuestas.some(
            respuesta => respuesta === respuesta_usuario
        );

        const llenar = document.getElementById(opcion_id);
        if (esCorrecto) {
            llenar.classList.add("correcto");
            respuestas_correctas++;
        } else {
            if (vidas > 1) {
                vidas--;
                llenar.classList.add("incorrecto");
            } else {
                clearInterval(intervalo);
                mostrarMensajePerdiste(contenedor)
                enviarProgreso(false, vidas);
                setTimeout(() => {
                    window.location.href = "niveles.html";
                }, 100000);
                return;
            }
        }
    } else {
        const contenedor = document.getElementById(opcion_id);
        if (respuesta_usuario.correcta == 1) {
            contenedor.classList.add("correcto");
            respuestas_correctas++;
        } else {
            if (vidas > 1) {
                vidas--;
                contenedor.classList.add("incorrecto");
            } else {
                clearInterval(intervalo);
                mostrarMensajePerdiste(contenedor);
                enviarProgreso(false, vidas);
                setTimeout(() => {
                    window.location.href = "niveles.html";
                }, 1000);
                return;
            }
        }
    }
    setTimeout(() => {
        indice_num_preguntas++;
        progreso += 10;
        console.log("progreso ", progreso);
        mostrarPregunta();
    }, 1000);
}

function mostrarMensajePerdiste(contenedor) {
    const perdio = document.createElement("div");
    perdio.innerHTML = `<div id="perdio">
    <img src="https://i.pinimg.com/736x/2b/de/9a/2bde9a0fe36ee87e40dd39e7419ef0ce.jpg">
    <h1> Perdiste </h1>
    <h3> Insertar mensaje gracioso pero que no te haga sentir mal</h3>
</div>`;


    contenedor.appendChild(perdio);
}

function actualizarCronometro() {
    const ahora = Date.now();
    const tiempo_pasado = Math.floor((ahora - tiempo_inicio) / 1000);
    const minutos = Math.floor(tiempo_pasado / 60);
    const segundos = tiempo_pasado % 60;
    const tiempo_formato = `${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;
    document.getElementById("tiempo").textContent = tiempo_formato;
}

function enviarProgreso(leccion_completada, vidas_restantes, tiempo_cronometro = null) {
    const fecha_finalizacion = new Date().toISOString();

    const bodyData = {
        leccion_completada: leccion_completada,
        vidas: vidas_restantes,
        fecha_finalizacion: fecha_finalizacion,
        respuestas_correctas: respuestas_correctas
    };

    if (tiempo_cronometro !== null) {
        bodyData.tiempo_cronometro = tiempo_cronometro;
    }

    fetch('actualizar_stats.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }, 
        body: JSON.stringify(bodyData)
    })
    .then(response => response.text())
    .then(data => {
        console.log("Respuesta del servidor:", data);
    })
    .catch(error => {
        console.error("Error al enviar el progreso:", error);
    });
}