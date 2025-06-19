document.addEventListener("DOMContentLoaded", function () {
    const tabla = document.getElementById("tablaUsuarios");
    if (!tabla) return;
    
    fetch("/SENAParking/backend/controllers/UsuarioSistemaController.php")
        .then(response => response.json())
        .then(data => {
            tabla.innerHTML = "";

            const roles = { 1: "Administrador", 2: "Supervisor", 3: "Guardia de Seguridad" };

            data.forEach(usuario => {
                const rolNombre = roles[usuario.id_rol] || "Desconocido";
                const estado = usuario.estado;
                const checked = estado === "activo" ? "checked" : "";

                const fila = `<tr>
                                <td>${usuario.username}</td>
                                <td>${rolNombre}</td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" onchange="cambiarestadousersys(${usuario.id_userSys}, this.checked)" ${checked}>
                                        <span class="slider"></span>
                                    </label>
                                </td>
                            </tr>`;
                tabla.innerHTML += fila;
            });
        })
        .catch(error => console.error(" ❌ Error al obtener los usuarios:", error));
});

function cambiarestadousersys(id_userSys, isChecked) {
    const nuevoEstado = isChecked ? "activo" : "inactivo";

    fetch('/SENAParking/backend/controllers/UsuarioSistemaController.php', {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id_userSys, estado: nuevoEstado })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message); // ✅ Muestra mensaje de actualización correcta
        location.reload();
    })
    .catch(error => console.error("Error al cambiar estado:", error));
}

