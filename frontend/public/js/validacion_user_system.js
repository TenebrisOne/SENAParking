const formulario = document.getElementById('formulario');
const inputs = document.querySelectorAll('#formulario input');

const expresiones = {
   nombre: /^[a-zA-ZÀ-ÿ\s]{4,16}$/,
   apellido: /^[a-zA-ZÀ-ÿ\s]{4,16}$/,
   documento: /^\d{6,10}$/,
   correo: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/,
   numero: /^\d{7,10}$/,
   usuario: /^[a-zA-Z0-9]{4,16}$/,
   contrasena: /^.{4,12}$/
}

const campos = {
   nombre: false,
   apellido: false,
   documento: false,
   correo: false,
   numero: false,
   usuario: false,
   contrasena: false
}

const validarcampo = (expresion, input, campo) => {
   if (expresion.test(input.value)) {
      document.getElementById(`grupo__${campo}`).classList.remove('formulario__grupo-incorrecto');
      document.getElementById(`grupo__${campo}`).classList.add('formulario__grupo-correcto');
      document.querySelector(`#grupo__${campo} .formulario__input-error`).classList.remove('formulario__input-error-activo');
      campos[campo] = true;
   } else {
      document.getElementById(`grupo__${campo}`).classList.add('formulario__grupo-incorrecto');
      document.getElementById(`grupo__${campo}`).classList.remove('formulario__grupo-correcto');
      document.querySelector(`#grupo__${campo} .formulario__input-error`).classList.add('formulario__input-error-activo');
      campos[campo] = false;
   }
}

const validarformulario = (e) => {
   switch (e.target.name) {
      case "nombre":
         validarcampo(expresiones.nombre, e.target, 'nombre');
         break;
      case "apellido":
         validarcampo(expresiones.apellido, e.target, 'apellido');
         break;
      case "documento":
         validarcampo(expresiones.documento, e.target, 'documento');
         break;
      case "correo":
         validarcampo(expresiones.correo, e.target, 'correo');
         break;
      case "numero":
         validarcampo(expresiones.numero, e.target, 'numero');
         break;
      case "usuario":
         validarcampo(expresiones.usuario, e.target, 'usuario');
         break;
      case "contrasena":
         validarcampo(expresiones.contrasena, e.target, 'contrasena');
         break;
   }
}

inputs.forEach((input) => {
   input.addEventListener('keyup', validarformulario);
   input.addEventListener('blur', validarformulario);
});

formulario.addEventListener('submit', (e) => {
   e.preventDefault();

   const todosValidos = Object.values(campos).every(valor => valor === true);

   if (todosValidos) {
      const formData = new FormData(formulario);

      fetch('guardar_usuario.php', {
         method: 'POST',
         body: formData
      })
      .then(response => response.text())
      .then(data => {
         alert(data);

         if (data.includes("exitosamente")) {
            formulario.reset();

            // Limpiar estilos y estados
            Object.keys(campos).forEach(campo => {
               const grupo = document.getElementById(`grupo__${campo}`);
               grupo.classList.remove('formulario__grupo-correcto', 'formulario__grupo-incorrecto');
               campos[campo] = false;
            });
         }
      })
      .catch(error => {
         alert('Error al enviar el formulario. Intente nuevamente.');
         console.error(error);
      });

   } else {
      alert("Por favor complete correctamente todos los campos.");
   }

   if (validacionesCorrectas) {
      formulario.submit(); // Asegúrate de tener esto si usas preventDefault
  }
  
});

// Flecha de retroceso
function goBack() {
   window.location.href = "/SENAParking/frontend/views/dashboard_admin.html";
}
