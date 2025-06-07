window.addEventListener('DOMContentLoaded', () => {
    fetch('actualizar_vidas.php', {
      method: 'GET',
      credentials: 'include' // Para enviar cookies de sesión
    })
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          console.error('Error:', data.error);
          return;
        }
        // Opcional: Mostrar un mensaje cuando recuperas vidas
        console.log(`Vidas actuales: ${data.vidas}`);
      })
      .catch(error => {
        console.error('Error en la petición:', error);
      });
});