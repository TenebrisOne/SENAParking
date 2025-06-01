fetch("/SENAParking/backend/models/mostrar_usuarios.php")
    .then(response => response.json())
    .then(data => {
        const tablaUsuarios = document.getElementById("tablaUsuarios");
        tablaUsuarios.innerHTML = ""; // Limpia la tabla antes de actualizar

        data.forEach(usuario => {
            const row = document.createElement("tr");

            row.innerHTML = `
                <td>${usuario.username}</td>
                <td>${obtenerRol(usuario.id_rol)}</td>
                <td>
                    <button class="btn btn-sm btn-outline-secondary" onclick="editarUsuario(${usuario.id_userSys})">Editar</button>
                    <button class="btn btn-sm btn-outline-danger" onclick="eliminarUsuario(${usuario.id_userSys})">Eliminar</button>
                </td>
            `;

            tablaUsuarios.appendChild(row);
        });
    })
    .catch(error => console.error("Error al cargar usuarios:", error));

// Función para traducir el ID del rol al nombre correspondiente
function obtenerRol(idRol) {
    const roles = {
        1: "Administrador",
        2: "Supervisor",
        3: "Guarda de Seguridad"
    };
    return roles[idRol] || "Desconocido";
}

// Función para eliminar usuario
function eliminarUsuario(idUserSys) {
    if (confirm("¿Seguro que deseas eliminar este usuario?")) {
        fetch(`/SENAParking/backend/models/eliminar_usuario.php?id=${idUserSys}`, { method: "GET" })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                location.reload(); // Recargar la página después de eliminar
            })
            .catch(error => console.error("Error al eliminar usuario:", error));
    }
}

