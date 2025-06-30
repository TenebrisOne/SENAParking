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

   if (campos.nombre && campos.apellido && campos.documento && campos.correo && campos.numero && campos.usuario && campos.contrasena) {

      const nombre = document.getElementById('nombre').value.trim();
      const apellido = document.getElementById('apellido').value.trim();
      const tipdoc = document.getElementById('tipdoc').value.trim();
      const documento = document.getElementById('documento').value.trim();
      const rol = document.getElementById('rol').value.trim();
      const correo = document.getElementById('correo').value.trim();
      const numero = document.getElementById('numero').value.trim();
      const usuario = document.getElementById('usuario').value.trim();
      const contrasena = document.getElementById('contrasena').value.trim();

      const formData = new FormData();
      formData.append('nombre', nombre);
      formData.append('apellido', apellido);
      formData.append('tipdoc', tipdoc);
      formData.append('documento', documento);
      formData.append('rol', rol);
      formData.append('correo', correo);
      formData.append('numero', numero);
      formData.append('usuario', usuario);
      formData.append('contrasena', contrasena);

      fetch('../../../SENAParking/backend/controllers/UsuarioSistemaController.php', {
         method: 'POST',
         body: formData
      })

         .then(response => response.text())
         .then(data => {
            alert(data);
            window.location.href="./../views/dashboard_admin.php";
         })
         .catch(error => {
            alert(error);
            window.location.href="./../views/dashboard_admin.php";
         });
      formulario.reset();
   }
});

// Flecha de retroceso
function goBack() {
   window.location.href = "/SENAParking/frontend/views/dashboard_admin.php";
}
