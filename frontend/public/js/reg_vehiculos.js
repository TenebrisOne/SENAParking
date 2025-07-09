document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formVehiculo');

    const placa = document.getElementById('placa');
    const tipo = document.getElementById('tipo');
    const color = document.getElementById('color');
    const modelo = document.getElementById('modelo');

    const añoActual = new Date().getFullYear();

    // ======== Funciones reutilizables ========
    const mostrarError = (input, mensaje) => {
        input.classList.add('is-invalid');
        const feedback = input.nextElementSibling;
        if (feedback) feedback.textContent = mensaje;
    };

    const limpiarError = (input) => {
        input.classList.remove('is-invalid');
    };

    const validarPlaca = () => {
        const valor = placa.value.trim().toUpperCase();
        placa.value = valor; // Convertir a mayúsculas automáticamente
        const regex = "/^[A-Z]{3}\d{3}$/";
        if (!regex.test(valor)) {
            mostrarError(placa, 'Formato inválido. Ej: ABC123');
            return false;
        }
        limpiarError(placa);
        return true;
    };

    const validarColor = () => {
        const valor = color.value.trim();
        if (valor.length < 3) {
            mostrarError(color, 'Mínimo 3 caracteres');
            return false;
        }
        limpiarError(color);
        return true;
    };

    const validarModelo = () => {
        const valor = parseInt(modelo.value.trim());
        if (isNaN(valor) || valor < 1900 || valor > añoActual) {
            mostrarError(modelo, `Entre 1900 y ${añoActual}`);
            return false;
        }
        limpiarError(modelo);
        return true;
    };

    const validarTipo = () => {
        if (tipo.value === '') {
            mostrarError(tipo, 'Seleccione un tipo');
            return false;
        }
        limpiarError(tipo);
        return true;
    };

    // ======== Validaciones en tiempo real ========
    placa.addEventListener('input', validarPlaca);
    color.addEventListener('input', validarColor);
    modelo.addEventListener('input', validarModelo);
    tipo.addEventListener('change', validarTipo);

    // ======== Validación final al enviar ========
    form.addEventListener('submit', function (e) {
        const esValido =
            validarPlaca() &
            validarColor() &
            validarModelo() &
            validarTipo(); // & en lugar de && para que se ejecuten todas

        if (!esValido) {
            e.preventDefault();
        }
    });
});
//=== funcio para redirigir al dashboard_guardia desde la flecha de retroceso !
function goBack() {
    window.location.href = "/frontend/views/dashboard_guardia.html";
}
