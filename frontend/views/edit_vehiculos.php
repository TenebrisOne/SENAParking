<?php
session_start();

// Mostrar vista dependiendo del estado de la sesion
if ($_SESSION["rol"] != 1) {
    header("Location: /SENAParking/login.php");
}

// cargamos totales
require_once __DIR__ . '/../../backend/controllers/MostrarDatosController.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="AdsoDeveloperSolutions801">
    <meta name="course" content="ADSO 2873801">

    <link rel="icon" type="x-icon" href="../public/images/favicon.ico">

    <title>Editar vehículo | SENAParking</title>

    <link href="../public/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../public/css/sityles_views.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <div id="header-container"></div>

    <img src="../public/images/logo_sena.png" alt="Logo SENA"
        style="position: absolute; top: 100px; right: 70px; width: 100px;">
    </div>
    
        <!-- Botón para volver atrás -->
        <div class="back-arrow" onclick="goBack()">
            <i class="fas fa-arrow-left"></i>
            <a class="nav-link" href="../../frontend/views/dashboard_guardia.php"></a>
        </div>

    <div class="container-form text-center">
        <br>
        <h2>Editar vehículo</h2>

        <div class="container mt-5">
            <form id="formVehiculo" method="POST" action="../../backend/controllers/EditVehicleController.php" novalidate>
                <input type="hidden" id="id_vehiculo" name="id_vehiculo">

                <div class="row mb-3">

                    <div class="col-md-6 text-start">
                        <label for="placa">Placa:</label>
                        <input type="text" id="placa" name="placa" class="form-control mb-2" placeholder="Ingrese la placa" required>
                        <div class="invalid-feedback text-start">Formato inválido. Ej: ABC123</div>

                        <label for="tipo">Tipo de vehículo:</label>
                        <select id="tipo" name="tipo" class="form-control mb-2" required>
                            <option value="" selected disabled>Seleccione una opción</option>
                            <option value="Automóvil">Automóvil</option>
                            <option value="Motocicleta">Motocicleta</option>
                            <option value="Bicicleta">Bicicleta</option>
                            <option value="Oficial">Oficial</option>
                            <option value="Aula móvil">Aula móvil</option>
                        </select>
                        <div class="invalid-feedback text-start">Seleccione un tipo de vehículo</div>

                        <label for="propietario">Propietario:</label>
                        <select id="propietario" name="propietario" class="form-control mb-2" required>
                            <option value="" selected disabled>Cargando propietarios...</option>
                        </select>
                        <div class="invalid-feedback text-start">Seleccione un propietario de vehículo</div>
                    </div>

                    <div class="col-md-6 text-start">
                        <label for="color">Color:</label>
                        <input type="text" id="color" name="color" class="form-control mb-2" placeholder="Ingrese el color" required>
                        <div class="invalid-feedback">El color debe tener al menos 3 caracteres</div>

                        <label for="modelo">Modelo:</label>
                        <input type="text" id="modelo" name="modelo" class="form-control mb-2" placeholder="Ingrese el modelo" required>
                        <div class="invalid-feedback">Longitud no puede superar 10</div>
                    </div>
                </div>

                <input type="submit" value="Guardar Cambios" class="btn btn-primary">

            </form>
        </div>
    </div>

    <script src="../public/js/reg_vehiculos.js"></script> 
    <a href="controllers/ActividadController.php?accion=editar_vehiculo">Edito un vehiculo</a>

    <!-- JS que carga el header dinámicamente -->
    <script src="./../public/js/scriptsDOM.js"></script>
    
    <script>
         function goBack() {
                window.location.href = './crud_vehiculos.html'; 
            }
        document.addEventListener('DOMContentLoaded', function() {
            // Función para volver atrás
           

            loadPropietarios(); 

            // Obtener el ID del vehículo de la URL
            const urlParams = new URLSearchParams(window.location.search);
            const vehicleId = urlParams.get('id');

            if (vehicleId) {
                // Si hay un ID, cargar los datos del vehículo
                document.getElementById('id_vehiculo').value = vehicleId; // Asignar el ID al campo oculto
                loadVehicleData(vehicleId);
            } else {
                // Si no hay ID, quizás redirigir o mostrar un error
                alert('No se especificó un ID de vehículo para editar.');
                window.location.href = './crud_vehiculos.html'; // O a una página de error
            }
        });

        // Función para cargar los propietarios (igual que en reg_vehiculos.html)
        async function loadPropietarios(selectedPropietarioId = null) {
            const propietarioSelect = document.getElementById('propietario');
            propietarioSelect.innerHTML = '<option value="" selected disabled>Cargando propietarios...</option>';

            try {
                const response = await fetch('../../backend/controllers/UserParkController.php');
                const data = await response.json();

                if (data.success && data.data.length > 0) {
                    propietarioSelect.innerHTML = '<option value="" disabled>Seleccione una opción</option>';
                    data.data.forEach(propietario => {
                        const option = document.createElement('option');
                        option.value = propietario.id_userPark;
                        option.textContent = propietario.nombre_completo;
                        if (selectedPropietarioId && propietario.id_userPark == selectedPropietarioId) {
                            option.selected = true;
                        }
                        propietarioSelect.appendChild(option);
                    });
                     // Asegurarse de que una opción esté seleccionada si no se encontró el ID o si es un nuevo formulario
                    if (!selectedPropietarioId && propietarioSelect.value === "") {
                        propietarioSelect.querySelector('option[value=""]').selected = true;
                    }
                } else {
                    propietarioSelect.innerHTML = '<option value="" selected disabled>No se encontraron propietarios</option>';
                    console.error('Error al cargar propietarios:', data.message || 'Datos no disponibles');
                    alert('No se encontraron propietarios para seleccionar. Por favor, asegúrate de que haya usuarios registrados en tb_userPark.');
                }
            } catch (error) {
                console.error('Error de conexión al cargar propietarios:', error);
                propietarioSelect.innerHTML = '<option value="" selected disabled>Error al cargar propietarios</option>';
                alert('Error de conexión al cargar propietarios. Asegúrate de que el servidor PHP esté funcionando.');
            }
        }

        // Función para cargar los datos del vehículo a editar
        async function loadVehicleData(vehicleId) {
            try {
                // Este endpoint lo crearemos en el próximo paso en EditVehicleController.php
                const response = await fetch(`../../backend/controllers/EditVehicleController.php?id=${vehicleId}`);
                const data = await response.json();

                if (data.success && data.data) {
                    const vehicle = data.data;
                    document.getElementById('placa').value = vehicle.placa;
                    document.getElementById('tipo').value = vehicle.tipo;
                    document.getElementById('color').value = vehicle.color;
                    document.getElementById('modelo').value = vehicle.modelo;
                    
                    // Asegurarse de que el propietario se seleccione correctamente después de cargar la lista
                    // Aquí llamamos a loadPropietarios de nuevo pero pasándole el ID para que lo seleccione
                    await loadPropietarios(vehicle.id_userPark);

                } else {
                    alert('Error al cargar los datos del vehículo: ' + (data.message || 'Vehículo no encontrado.'));
                    window.location.href = './crud_vehiculos.html';
                }
            } catch (error) {
                console.error('Error de conexión al cargar datos del vehículo:', error);
                alert('Error de conexión al cargar los datos del vehículo. Asegúrate de que el servidor PHP esté funcionando.');
                window.location.href = './crud_vehiculos.html';
            }
        }
    </script>

    <!-- script para que cuando se cierre la sesion refresque la ventana -->
    <script src="../public/js/ref_cierre.js"></script>
</body>
</html>