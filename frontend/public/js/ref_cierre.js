window.addEventListener("pageshow", function (event) {
  if (
    event.persisted ||
    (window.performance && window.performance.navigation.type === 2)
  ) {
    // Si la página fue cargada desde caché, forzar recarga
    window.location.reload();
  }
});
