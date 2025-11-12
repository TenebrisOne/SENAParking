// En este archivo js se hacen los llamados para poder reflejar los numeros en tiempo real en el dashboard_admin //
function cargarDatosDashboard() {
    fetch('http://localhost/SENAPARKING/backend/controllers/dashboard_admin_controller.php') // Ruta desde frontend/views/
        .then(response => response.json())
        .then(data => {
            console.log(data); // Aquí va, dentro del .then(), para ver los datos recibidos
            document.getElementById('total-usuarios-sistema').innerText = data.usuariosSistema;
            document.getElementById('total-usuarios-parqueadero').innerText = data.usuariosParqueadero;
            document.getElementById('accesos-hoy').innerText = data.ingresosHoy;
            document.getElementById('salidas-hoy').innerText = data.salidasHoy;
            // Puedes agregar capacidad total si deseas mostrarlo en otra tarjeta
            console.log(data); // Para verificar que llegan los datos
        })
        .catch(error => console.error("Error al cargar datos del dashboard:", error));
}

cargarDatosDashboard();
setInterval(cargarDatosDashboard, 5000);

