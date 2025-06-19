const formulario = document.getElementById('formulario');
const inputs = document.querySelectorAll('#formulario input');

const expresiones = {
   nombre: /^[a-zA-ZÀ-ÿ\s]{4,16}$/, // Letras, numeros, guion y guion_bajo
   apellido: /^[a-zA-ZÀ-ÿ\s]{4,16}$/, // Letras y espacios, pueden llevar acentos.
   documento: /^\d{6,10}$/, // 4 a 12 digitos.
   numero: /^\d{7,10}$/ // 7 a 10 numeros.
}

const campos = {
   nombre: false,
   apellido: false,
   documento: false,
   numero: false
}

const validarcampo = (expresiones, input, campo) => {
   if (expresiones.test(input.value)) {
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
      case "numero":
         validarcampo(expresiones.numero, e.target, 'numero');
         break;
   }
}

inputs.forEach((input)=>{
   input.addEventListener('keyup', validarformulario);
   input.addEventListener('blur', validarformulario);
})

formulario.addEventListener('submit', (e) => {
   e.preventDefault();

   if (campos.nombre && campos.apellido && campos.documento && campos.numero) {

      const nombre = document.getElementById('nombre').value.trim();
      const apellido = document.getElementById('apellido').value.trim();
      const tipdoc = document.getElementById('tipdoc').value.trim();
      const documento = document.getElementById('documento').value.trim();
      const numero = document.getElementById('numero').value.trim();
      const tipuser = document.getElementById('tipo_usuario').value.trim();
      const centro = document.getElementById('edificio').value.trim();

      const formData = new FormData();
      formData.append('nombre', nombre);
      formData.append('apellido', apellido);
      formData.append('tipdoc', tipdoc);
      formData.append('documento', documento);
      formData.append('numero', numero);
      formData.append('tipo_usuario', tipuser);
      formData.append('edificio', centro);

      fetch('../../../SENAParking/backend/controllers/UsuarioParqueaderoController.php', {
         method: 'POST',
         body: formData
      })

         .then(response => response.text())
         .then(data => {
            alert(data);
            window.location.href="../views/dashboard_admin.html";
         })
         .catch(error => {
            alert(error);
            window.location.href="../views/dashboard_admin.html";
         });
      formulario.reset();
   }
});

//=== funcion para redirigir al dashboard_supervisor desde la flecha de retroceso !
function goBack() {
   window.location.href = "/frontend/views/dashboard_guardia.html";
}

