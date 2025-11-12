document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formulario");
  const loader = document.getElementById("loader-overlay");
  const errorMsg = document.getElementById("error-message");

  if (!form || !loader) return;

  form.addEventListener("submit", async (event) => {
    event.preventDefault(); // Evita envío tradicional

    // Limpiar mensaje anterior
    errorMsg.style.display = "none";
    errorMsg.textContent = "";

    // Mostrar el loader
    loader.style.display = "flex";

    const formData = new FormData(form);

    try {
      const response = await fetch(form.action, {
        method: form.method,
        body: formData,
      });

      const data = await response.text();

      // Caso: credenciales incorrectas
      if (data.includes("Usuario inválido") || data.includes("contraseña incorrecta")) {
        loader.style.display = "none";
        errorMsg.textContent = "Usuario o contraseña incorrectos. Intenta de nuevo.";
        errorMsg.style.display = "block";
      } else {
        // Login correcto → carga la siguiente vista
        document.open();
        document.write(data);
        document.close();
      }
    } catch (error) {
      loader.style.display = "none";
      errorMsg.textContent = "Error de conexión. Intenta nuevamente.";
      errorMsg.style.display = "block";
      console.error("Error en la petición:", error);
    }
  });
});


