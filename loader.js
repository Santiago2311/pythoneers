window.addEventListener('DOMContentLoaded', () => {
    fetch('loader.html')
      .then(response => response.text())
      .then(html => {
        document.body.insertAdjacentHTML('afterbegin', html);
      });
  
    window.addEventListener('load', () => {
      const interval = setInterval(() => {
        const loader = document.getElementById('loader');
        if (loader) {
          loader.style.display = 'none';
          clearInterval(interval);
        }
      }, 100); // Espera hasta que se haya insertado el loader
    });
  });