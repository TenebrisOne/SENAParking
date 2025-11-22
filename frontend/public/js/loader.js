document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formulario");
  const loader = document.getElementById("loader-overlay");
  const errorMsg = document.getElementById("error-message");

  if (!form || !loader) return;

  form.addEventListener("submit", async (event) => {
    event.preventDefault(); // Evita envío tradicional

    // Ocultar mensaje previo
    errorMsg.style.display = "none";
    errorMsg.textContent = "";

    // Mostrar loader
    loader.style.display = "flex";

    const formData = new FormData(form);

    try {
      const response = await fetch(form.action, {
        method: form.method,
        body: formData,
      });

      // Si la respuesta del servidor no es 200-299 → error real
      if (!response.ok) {
        throw new Error("Servidor respondió con un estado no exitoso");
      }

      const data = await response.text();

      // Validar credenciales incorrectas
      if (data.includes("Usuario inválido") || data.includes("contraseña incorrecta")) {
        loader.style.display = "none";
        errorMsg.textContent = "Usuario o contraseña incorrectos. Intenta de nuevo.";
        errorMsg.style.display = "block";
        return;
      }

      // Login correcto → renderizar siguiente vista
      document.open();
      document.write(data);
      document.close();

    } catch (error) {
      // Este error solo se mostrará cuando haya error real de red
      loader.style.display = "none";

      errorMsg.style.display = "block";

      console.error("Error en la petición:", error);
    }
  });
});
