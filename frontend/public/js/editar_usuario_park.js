const params = new URLSearchParams(window.location.search);
const id = params.get('id');

// Precargar datos
fetch(`/SENAParking/backend/controllers/UsuarioParqueaderoController.php?id=${id}`)
  .then(res => res.json())
  .then(data => {
    document.getElementById('id_userPark').value = data.id_userPark;
    document.getElementById('editar-nombre').value = data.nombres_park;
    document.getElementById('editar-apellido').value = data.apellidos_park;
    document.getElementById('editar-tipdoc').value = data.tipo_documento;
    document.getElementById('editar-documento').value = data.numero_documento;
    document.getElementById('editar-numero').value = data.numero_contacto;
    document.getElementById('editar-tipo_usuario').value = data.tipo_user;
    document.getElementById('editar-edificio').value = data.edificio;
  });

// Guardar cambios
document.getElementById('formulario').addEventListener('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(this);

  fetch('/SENAParking/backend/controllers/UsuarioParqueaderoController.php', {
    method: 'POST',
    body: formData
  })
    .then(res => res.text())
    .then(msg => {
      alert(msg);
      window.location.href = 'dashboard_admin.html';
    });
});
