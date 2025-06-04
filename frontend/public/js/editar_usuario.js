document.addEventListener("DOMContentLoaded", function () {
    const params = new URLSearchParams(window.location.search);
    const idUserSys = params.get("id");

    if (!idUserSys) {
        alert("Error: No se proporcionó un usuario válido.");
        window.location.href = "dashboard_admin.html";
        return;
    }

    fetch(`/SENAParking/backend/models/mostrar_usuario_por_id.php?id=${idUserSys}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === "error") {
                alert("Error: Usuario no encontrado.");
                window.location.href = "dashboard_admin.html";
                return;
            }

            // Llenar los campos del formulario con los datos del usuario
            document.getElementById("editar-nombre").value = data.nombres_sys;
            document.getElementById("editar-apellido").value = data.apellidos_sys;
            document.getElementById("editar-documento").value = data.numero_documento;
            document.getElementById("editar-rol").value = data.id_rol;
            document.getElementById("editar-correo").value = data.correo;
            document.getElementById("editar-numero").value = data.numero_contacto;
            document.getElementById("editar-usuario").value = data.username;
            document.getElementById("id_userSys").value = idUserSys;
        })
        .catch(error => {
            console.error("Error al cargar usuario:", error);
            alert("Hubo un problema al cargar los datos.");
        });
});

document.getElementById("formulario").addEventListener("submit", function(event) {
    event.preventDefault();
    
    const formData = new FormData(this);

    console.log("Datos enviados:", Object.fromEntries(formData.entries())); // Depuración

    fetch("/SENAParking/backend/models/editar_usuario.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log("Respuesta del servidor:", data); // Depuración

        if (data.status === "success") {
            alert("Usuario actualizado correctamente.");
            window.location.href = "dashboard_admin.html"; // Redirigir después de guardar
        } else {
            alert("Error al actualizar usuario: " + data.message);
        }
    })
    .catch(error => {
        console.error("Error al actualizar usuario:", error);
        alert("Hubo un problema al actualizar los datos.");
    });
});

