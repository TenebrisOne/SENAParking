document.addEventListener("DOMContentLoaded", function () {
    fetch('/SENAParking/backend/models/UsuarioSistemaModel.php')
        .then(response => response.json())
        .then(data => {
            let tablaUsuarios = document.getElementById("tablaUsuarios");
            tablaUsuarios.innerHTML = ""; // ✅ Limpiar la tabla antes de agregar filas

            // Definir la equivalencia de IDs con los nombres de los roles
            const roles = { 1: "Administrador", 2: "Supervisor", 3: "Guardia de Seguridad" };

            data.forEach(usuario => {
                let rolNombre = roles[usuario.id_rol] || "Desconocido"; // ✅ Convertir ID en nombre de rol
                let estadoBoton = usuario.estado === "activo" ? "Deshabilitar" : "Habilitar";
                let estadoClase = usuario.estado === "activo" ? "btn-danger" : "btn-success";

                let fila = `<tr>
                                <td>${usuario.username}</td>
                                <td>${rolNombre}</td>
                                <td>
                                <label class="switch">
    <input type="checkbox" onchange="cambiarEstado(this, ${usuario.id_userSys}, '${usuario.estado}')" ${usuario.estado === 'activo' ? 'checked' : ''}>
    <span class="slider"></span>
                                </label>
                                </td>
                            </tr>`;
                tablaUsuarios.innerHTML += fila;
            });
        })
        .catch(error => console.error("Error al obtener los usuarios:", error));
});

function cambiarEstado(checkbox, id_userSys, estadoActual) {
    let nuevoEstado = checkbox.checked ? "activo" : "inactivo";

    fetch('/SENAParking/backend/models/UsuarioSistemaModel.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id_userSys: id_userSys, estado: nuevoEstado })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log("Estado cambiado correctamente.");
        } else {
            console.error("Error al cambiar estado:", data.error);
        }
    })
    .catch(error => console.error("Error al cambiar estado:", error));
}



