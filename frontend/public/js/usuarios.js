document.addEventListener("DOMContentLoaded", function () {
    fetch('/SENAParking/backend/models/UsuarioSistemaModel.php')
        .then(response => response.json())
        .then(data => {
            let tablaUsuarios = document.getElementById("tablaUsuarios");
            tablaUsuarios.innerHTML = ""; 

            const roles = { 1: "Administrador", 2: "Supervisor", 3: "Guardia de Seguridad" };

            data.forEach(usuario => {
                let rolNombre = roles[usuario.id_rol] || "Desconocido";
                let checked = usuario.estado === "activo" ? "checked" : "";

                let fila = `<tr>
                                <td>${usuario.username}</td>
                                <td>${rolNombre}</td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" onchange="cambiarEstado(${usuario.id_userSys}, this.checked)" ${checked}>
                                        <span class="slider"></span>
                                    </label>
                                </td>
                            </tr>`;
                tablaUsuarios.innerHTML += fila;
            });
        })
        .catch(error => console.error("Error al obtener los usuarios:", error));
});

function cambiarEstado(id_userSys, isChecked) {
    let nuevoEstado = isChecked ? "activo" : "inactivo";

    fetch('/SENAParking/backend/models/UsuarioSistemaModel.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ id_userSys: id_userSys, estado: nuevoEstado })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message); // ✅ Muestra mensaje de actualización correcta
        location.reload();
    })
    .catch(error => console.error("Error al cambiar estado:", error));
}

