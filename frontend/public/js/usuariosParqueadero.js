document.addEventListener("DOMContentLoaded", function () {
    fetch("/SENAParking/backend/controllers/UsuarioParqueaderoController.php")
        .then(response => response.json())
        .then(data => {
            let tablaUsuarios = document.getElementById("tablaUsuariosParqueadero");
            tablaUsuarios.innerHTML = "";

            data.forEach(usuario => {
                let nombreCompleto = `${usuario.nombres_park} ${usuario.apellidos_park}`;
                let documento = usuario.numero_documento;
                let checked = usuario.estado === "activo" ? "checked" : "";

                let fila = `<tr>
                        <td>${nombreCompleto}</td>
                        <td>${documento}</td>
                         <td>
                           <a href="../views/editar_userpark.html?id=${usuario.id_userSys}" class="btn btn-sm btn-outline-secondary">Editar</a>
                          <label class="switch">
                            <input type="checkbox" onchange="cambiarEstado(${usuario.id_userPark}, this.checked)" ${checked}>
                            <span class="slider"></span>
                          </label>
                        </td>
                      </tr>`;
                tablaUsuarios.innerHTML += fila;
            });
        })
        .catch(error => console.error("Error al obtener los usuarios parqueadero:", error));
});

function cambiarEstado(id_userPark, isChecked) {
    let nuevoEstado = isChecked ? "activo" : "inactivo";

    fetch("/SENAParking/backend/controllers/UsuarioParqueaderoController.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id_userPark: id_userPark, estado: nuevoEstado })
    })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            location.reload(); // ✅ Refresca para mostrar cambio
        })
        .catch(error => console.error("Error al cambiar estado:", error));
}


