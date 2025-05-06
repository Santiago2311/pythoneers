window.addEventListener('DOMContentLoaded', () => {
  const loaderHTML = `
    <div id="loader">
      <img src="assets/ouroboros.gif" alt="Cargando" />
    </div>
  `;
    document.body.insertAdjacentHTML('afterbegin', html);
    });
  
    window.addEventListener('load', () => {
      const loader = document.getElementById('loader');
      if (loader) {
        loader.style.transition = 'opacity 0.5s ease';
        loader.style.opacity = '0';
        setTimeout(() => loader.remove(), 500); // Espera hasta que se haya insertado el loader
    }
  });