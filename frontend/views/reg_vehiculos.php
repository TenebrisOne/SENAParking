<?php
session_start();

if (!isset($_SESSION['rol'])) {
    header("location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="AdsoDeveloperSolutions801">
    <meta name="course" content="ADSO 2873801">

    <link rel="icon" type="x-icon" href="../public/images/favicon.ico">

    <title>Registro de vehículos | SENAParking</title>

    <link href="../public/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../public/css/sityles_views.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <div id="header-container"></div>

    <img src="../public/images/logo_sena.png" alt="Logo SENA"
        style="position: absolute; top: 100px; right: 70px; width: 100px;">
    </div>

    <div class="back-arrow" onclick="goBack()">
        <i class="fas fa-arrow-left"></i>
    </div>

    <div class="container-form text-center">
        <br>
        <h2>Registro de vehículos</h2>

        <div class="container mt-5">
            <form id="formVehiculo" method="POST" action="../../backend/controllers/VehicleRegisterController.php" novalidate>
                <div class="row mb-3">

                    <div class="col-md-6 text-start">

                        <!-- Campo Placa -->
                        <label for="placa">Placa:</label>
                        <input type="text" id="placa" name="placa" class="form-control mb-2"
                            placeholder="Ingrese la placa" required>
                        <div class="invalid-feedback text-start">Formato inválido. Ej: ABC123</div>

                        <!-- Campo Tarjeta de propiedad o Serial-->
                        <label for="tarjeta_propiedad">Tarjeta de propiedad o Serial:</label>
                        <input type="text" id="tarjeta_propiedad" name="tarjeta_propiedad" class="form-control mb-2"
                            placeholder="Ingrese la Tarjeta de propiedad o el Serial" required>

                        <div class="invalid-feedback text-start">Formato inválido. Ej: ABC123</div>

                        <label for="tipo">Tipo de vehículo:</label>
                        <select id="tipo" name="tipo" class="form-control mb-2" required>
                            <option value="" selected disabled>Seleccione una opción</option>
                            <option value="Automóvil">Automóvil</option>
                            <option value="Motocicleta">Motocicleta</option>
                            <option value="Bicicleta">Bicicleta</option>
                            <option value="Oficial">Vehiculo SENA</option>
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
                        <input type="text" id="color" name="color" class="form-control mb-2"
                            placeholder="Ingrese el color" required>
                        <div class="invalid-feedback">El color debe tener al menos 3 caracteres</div>

                        <label for="modelo">Modelo:</label>

                        <input type="text" id="modelo" name="modelo" class="form-control mb-2"
                            placeholder="Ingrese el modelo" required>
                        <div class="invalid-feedback">El modelo debe estar entre 1900 y 2025</div>


                        <label for="observaciones">Observaciones:</label>
                        <textarea id="observaciones" name="observaciones" class="form-control" style="resize: none;"
                            placeholder="Ingrese una observacion"></textarea>


                    </div>
                </div>

                        
                    </div>
                    
                <input type="submit" value="Confirmar"
                onclick="window.location.href='/frontend/views/crud_vehiculos.php'" class="btn btn-primary">
                </div>

                <!-- Botón de enviar -->


            </form>
        </div>
    </div>

        <a href="controllers/ActividadController.php?accion=crear_vehiculo"></a>

    <!-- JS que carga el header dinámicamente -->
    <script src="./../public/js/scriptsDOM.js"></script>

    <script src="../public/js/reg_vehiculos.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadPropietarios(); // Call the function to load proprietors when the DOM is ready
        });

        function goBack() {
                window.location.href = '/SENAParking/frontend/views/crud_vehiculos.php';
        }

        async function loadPropietarios() {
            const propietarioSelect = document.getElementById('propietario');
            propietarioSelect.innerHTML = '<option value="" selected disabled>Cargando propietarios...</option>'; // Show loading

            try {
                const response = await fetch('../../backend/controllers/UserParkController.php');
                const data = await response.json();

                if (data.success && data.data.length > 0) {
                    propietarioSelect.innerHTML = '<option value="" selected disabled>Seleccione una opción</option>'; // Reset
                    data.data.forEach(propietario => {
                        const option = document.createElement('option');
                        option.value = propietario.id_userPark; // IMPORTANT: Value is the ID
                        option.textContent = propietario.nombre_completo; // Display full name
                        propietarioSelect.appendChild(option);
                    });
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
    </script>
    <script src="../public/js/scriptsDOM.js"></script>

    <!-- script para que cuando se cierre la sesion refresque la ventana -->
    <script src="../public/js/ref_cierre.js"></script>

</body>

</html>