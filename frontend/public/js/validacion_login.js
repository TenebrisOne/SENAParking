const formulario = document.getElementById("formulario");
const inputs = document.querySelectorAll("#formulario input");

const expresiones = {
  password: /^.{4,12}$/, // 4 a 12 digitos.
  correo: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/,
};

const campos = {
  correo: false,
  password: false,
};

const validarcampo = (expresiones, input, campo) => {
  if (expresiones.test(input.value)) {
    document
      .getElementById(`grupo__${campo}`)
      .classList.remove("formulario__grupo-incorrecto");
    document
      .getElementById(`grupo__${campo}`)
      .classList.add("formulario__grupo-correcto");
    document
      .querySelector(`#grupo__${campo} .formulario__input-error`)
      .classList.remove("formulario__input-error-activo");
    campos[campo] = true;
  } else {
    document
      .getElementById(`grupo__${campo}`)
      .classList.add("formulario__grupo-incorrecto");
    document
      .getElementById(`grupo__${campo}`)
      .classList.remove("formulario__grupo-correcto");
    document
      .querySelector(`#grupo__${campo} .formulario__input-error`)
      .classList.add("formulario__input-error-activo");
    campos[campo] = false;
  }
};

const validarformulario = (e) => {
  switch (e.target.name) {
    case "correo":
      validarcampo(expresiones.correo, e.target, "correo");
      break;
    case "password":
      validarcampo(expresiones.password, e.target, "password");
      break;
  }
};

inputs.forEach((input) => {
  input.addEventListener("keyup", validarformulario);
  input.addEventListener("blur", validarformulario);
});

formulario.addEventListener("submit", (e) => {
  e.preventDefault();

  const correo = document.getElementById("correo").value.trim();
  const password = document.getElementById("password").value.trim();

  // Validar directamente los valores (no depender de los eventos keyup/blur)
  const correoValido = expresiones.correo.test(correo);
  const passwordValido = expresiones.password.test(password);

  // Actualizar estado visual
  if (correoValido) {
    document.getElementById("grupo__correo").classList.remove("formulario__grupo-incorrecto");
    document.getElementById("grupo__correo").classList.add("formulario__grupo-correcto");
  } else {
    document.getElementById("grupo__correo").classList.add("formulario__grupo-incorrecto");
  }

  if (passwordValido) {
    document.getElementById("grupo__password").classList.remove("formulario__grupo-incorrecto");
    document.getElementById("grupo__password").classList.add("formulario__grupo-correcto");
  } else {
    document.getElementById("grupo__password").classList.add("formulario__grupo-incorrecto");
  }

  if (correoValido && passwordValido) {
    const formData = new FormData();
    formData.append("correo", correo);
    formData.append("password", password);

    const errorMessage = document.getElementById("error-message");

    // Mostrar loader
    if (typeof showLoader === 'function') {
      showLoader();
    }

    fetch(
      "backend/controllers/LoginController.php",
      {
        method: "POST",
        body: formData,
      }
    )
      .then((response) => response.text())
      .then((data) => {
        // Ocultar loader
        if (typeof hideLoader === 'function') {
          hideLoader();
        }
        // Limpiar espacios en blanco
        const respuesta = data.trim();

        switch (respuesta) {
          case "admin":
            window.location.href = "frontend/views/dashboard_admin.php";
            break;
          case "supervisor":
            window.location.href = "frontend/views/dashboard_supervisor.php";
            break;
          case "guardia":
            window.location.href = "frontend/views/dashboard_guardia.php";
            break;
          default:
            // Mostrar el mensaje de error específico del servidor
            if (errorMessage) {
              errorMessage.textContent = respuesta || "Usuario o contraseña incorrectos";
              errorMessage.style.display = "block";
            } else {
              alert(respuesta || "Usuario o contraseña incorrectos");
            }
        }
      })
      .catch((error) => {
        // Ocultar loader
        if (typeof hideLoader === 'function') {
          hideLoader();
        }
        console.error("Error de conexión:", error);
        if (errorMessage) {
          errorMessage.textContent = "Error de conexión. Intenta de nuevo.";
          errorMessage.style.display = "block";
        } else {
          alert("Error de conexión. Intenta de nuevo.");
        }
      });
  }
});
