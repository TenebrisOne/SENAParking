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

  if (
    campos.correo &&
    campos.password
  ) {
    const correo = document.getElementById("correo").value.trim();
    const password = document.getElementById("password").value.trim();

    const formData = new FormData();
    formData.append("correo", correo);
    formData.append("password", password);

    fetch(
      "../../../SENAParking/backend/controllers/LoginController.php",
      {
        method: "POST",
        body: formData,
      }
    )
      .then((response) => response.text())
      .then((data) => {
        switch (data) {
          case "1":
            window.location.href = "frontend/views/dashboard_admin.php";
            break;
          case "2":
            window.location.href = "frontend/views/dashboard_supervisor.php";
            break;
          case "3":
            window.location.href = "frontend/views/dashboard_guardia.php";
            break;
          default:
            alert(data);
        }
      })
    formulario.reset();
  }
});
