// Cargar el contenido del header desde un archivo HTML externo
fetch("./../views/layouts/header.html")

    .then(response => response.text())
    .then(data => {
        document.getElementById('header-container').innerHTML = data;
    })
    .catch(error => console.error('Error al cargar el header:', error));
    

// Función para retroceder en el historial del navegador
function goBack() {
    window.history.back();

}


// Cargar el contenido del header desde un archivo HTML externo
fetch("./frontend/views/layouts/headerIdx.html")

    .then(response => response.text())
    .then(data => {
        document.getElementById('header-containerIdx').innerHTML = data;
    })
    .catch(error => console.error('Error al cargar el header:', error));
    

// Función para retroceder en el historial del navegador
function goBack() {
    window.history.back();

}