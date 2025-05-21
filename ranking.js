window.addEventListener('DOMContentLoaded', () => {
    fetch('ranking.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('ranking_container');
            const podioImgs = ['assets/1ero.png', 'assets/2do.png', 'assets/3ro.png'];

            data.usuarios.forEach((usuario, index) => {
                const fila = document.createElement('tr');

                const imgPodio = index < 3 ? `<img class="podio" src="${podioImgs[index]}">` : (index + 1);

                const avatarDiv = document.createElement('div');
                avatarDiv.classList.add('avatar');

                // Imagen base del perfil
                const imgPerfil = document.createElement('img');
                imgPerfil.classList.add('perfil');
                imgPerfil.src = 'assets/usuario.png';
                avatarDiv.appendChild(imgPerfil);

                if (usuario.accesorios && usuario.accesorios.length > 0) {
                    usuario.accesorios.forEach(accesorio => {
                        const imgAccesorio = document.createElement('img');
                        imgAccesorio.classList.add('accesorio');
                        imgAccesorio.id = accesorio.categoria;
                        imgAccesorio.src = `assets/${accesorio.nombre_producto}.png`;
                        avatarDiv.appendChild(imgAccesorio);
                    });
                }

            fila.innerHTML = `
            <td>${imgPodio}</td>
            <td></td>
            <td>${usuario.nombre} ${usuario.apellidos}</td>
            <td>${usuario.puntaje}</td>
            `;

            fila.children[1].appendChild(avatarDiv);
            tbody.appendChild(fila);
        });
    })

    .catch(error => {
        console.error('Error al cargar el ranking:', error);
    });
});