window.addEventListener('DOMContentLoaded', () => {
    fetch('ranking.php')
    .then(response => response.json())
    .then(data => {
        const tbody = document.getElementById('ranking_container');
        const podioImgs = ['assets/1ero.png', 'assets/2do.png', 'assets/3ro.png'];

        data.forEach((usuario, index) => {
            const fila = document.createElement('tr');

            const imgPodio = index < 3 ? `<img class="podio" src="${podioImgs[index]}">` : (index + 1);

            fila.innerHTML = `
            <td>${imgPodio}</td>
            <td><img class="perfil" src="assets/usuario.png"></td>
            <td>${usuario.nombre} ${usuario.apellidos}</td>
            <td>${usuario.puntaje}</td>
            `;

            tbody.appendChild(fila);
        });
    })

    .catch(error => {
        console.error('Error al cargar el ranking:', error);
    });
});