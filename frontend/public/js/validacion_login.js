const formulario = document.getElementById('formulario');
const inputs = document.querySelectorAll('#formulario input');

const expresiones = {
   password: /^.{4,12}$/, // 4 a 12 digitos.
   correo: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/
}

const campos = {
   correo: false,
   password: false
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
      case "correo":
         validarcampo(expresiones.correo, e.target, 'correo');
         break;
      case "password":
         validarcampo(expresiones.password, e.target, 'password');
         break;
   }
}

inputs.forEach((input)=>{
   input.addEventListener('keyup', validarformulario);
   input.addEventListener('blur', validarformulario);
})

formulario.addEventListener('submit', (e) => {
    e.preventDefault();
 
    const terminos = document.getElementById('terminos');
    
    if(campos.usuario && campos.nombre && campos.password && campos.correo && campos.telefono && terminos.checked){
       formulario.reset();
 
       document.getElementById('formulario__mensaje-exito').classList.add('formulario__mensaje-exito-activo');
       setTimeout(()=>{
          document.getElementById('formulario__mensaje-exito').classList.remove('formulario__mensaje-exito-activo')   
       }, 5000);
    }else{
       document.getElementById('formulario__mensaje').classList.add('formulario__mensaje-activo');
    }
 });