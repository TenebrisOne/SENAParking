const username = "juanperez";
document.getElementById("user-initial").textContent = username.charAt(0).toUpperCase();

function accion1() {
    alert('Has hecho clic en Opción 1');
}

function CerrarSesion() {
    window.location.href = '/logout.php';
}

