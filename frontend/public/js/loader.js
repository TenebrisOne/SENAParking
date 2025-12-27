/* ===========================
   ESCUCHADOR DEL LOADER
   =========================== */

function showLoader() {
  const loader = document.getElementById("loader-overlay");
  if (loader) {
    loader.classList.add("active");
  }
}

function hideLoader() {
  const loader = document.getElementById("loader-overlay");
  if (loader) {
    loader.classList.remove("active");
  }
}

// Si se prefiere usar de forma automática en formularios específicos
document.addEventListener("DOMContentLoaded", () => {
  // Opcionalmente, podemos ocultar el loader al cargar la página por si acaso
  hideLoader();
});
