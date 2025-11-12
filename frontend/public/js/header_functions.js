const username = "juanperez";
document.getElementById("user-initial").textContent = username.charAt(0).toUpperCase();

function accion1() {
    alert('Has hecho clic en Opción 1');
}

function accion2() {
    console.log('Ejecutando Opción 2...');
    // Puedes redirigir, abrir modal, etc.
}

function CerrarSesion() {
    window.location.href = '/logout.php';
}

