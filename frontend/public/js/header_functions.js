const username = "juanperez";
document.getElementById("user-initial").textContent = username.charAt(0).toUpperCase();

function accion1() {
    window.location.href = '../../logout.php';
}

function accion2() {
    console.log('Ejecutando Opción 2...');
    // Puedes redirigir, abrir modal, etc.
}

function accion3() {
    window.location.href = 'https://ejemplo.com';
}