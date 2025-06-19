document.addEventListener("DOMContentLoaded", function () {
    const tabla = document.getElementById("tablaUsuariosParqueadero");
    if (!tabla) return;
  
    fetch("/SENAParking/backend/controllers/UsuarioParqueaderoController.php")
      .then(response => response.json())
      .then(data => {
        tabla.innerHTML = "";
  
        data.forEach(usuario => {
          const nombreCompleto = `${usuario.nombres_park} ${usuario.apellidos_park}`;
          const documento = usuario.numero_documento;
          const estado = usuario.estado;
          const checked = estado === "activo" ? "checked" : "";
  
          const fila = `
            <tr>
              <td>${nombreCompleto}</td>
              <td>${documento}</td>
              <td>
                <a href="../views/editar_userpark.html?id=${usuario.id_userPark}" class="btn btn-sm btn-outline-secondary">Editar</a>
                <label class="switch">
                  <input type="checkbox" onchange="cambiarEstado(${usuario.id_userPark}, this.checked)" ${checked}>
                  <span class="slider"></span>
                </label>
              </td>
            </tr>
          `;
          tabla.innerHTML += fila;
        });
      })
      .catch(error => console.error("❌ Error al cargar usuarios:", error));
  });
  
  function cambiarEstado(id_userPark, isChecked) {
    const nuevoEstado = isChecked ? "activo" : "inactivo";
  
    fetch("/SENAParking/backend/controllers/UsuarioParqueaderoController.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id_userPark, estado: nuevoEstado })
    })
      .then(response => response.json())
      .then(data => {
        alert(data.message);
        location.reload();
      })
      .catch(error => console.error("❌ Error al cambiar estado:", error));
  }
  


