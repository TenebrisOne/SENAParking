// Esperar a que el DOM esté listo
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("formulario");
  const loader = document.getElementById("loader-overlay");

  if (form && loader) {
    form.addEventListener("submit", function () {
      // Mostrar loader cuando se envíe el formulario
      loader.classList.add("active");
    });
  }
});
