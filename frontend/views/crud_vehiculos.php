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
    <title>Gestión de Vehículos | SENAParking</title>
    <link rel="icon" type="x-icon" href="../public/images/favicon.ico">
    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/crudVehiculos.css">
    <link rel="stylesheet" href="../public/css/sityles_views.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-light">

    <div id="header-container"></div>
    <!-- Botón para volver atrás -->
    <div class="back-arrow" onclick="goBack()">
        <i class="fas fa-arrow-left"></i>
        <a class="nav-link" href="../../frontend/views/dashboard_guardia.php"></a>
    </div>

    <div class="container-fluid">
        <div class="container">
            <div class="search-section">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-3 mb-md-0">
                            <i class="fas fa-search me-2 text-primary"></i>
                            Buscar Vehículos
                        </h5>
                    </div>
                    <div class="col-md-4">
                        <div class="search-container">
                            <input type="text" class="form-control search-input" id="searchInput"
                                placeholder="Buscar por propietario o placa..." oninput="searchVehicles(this.value)">
                            <button class="clear-search" id="clearSearch" onclick="clearSearch()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="toggle-section">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-3 mb-md-0">
                            <i class="fas fa-exchange-alt me-2 text-primary"></i>
                            Estado del Movimiento
                        </h5>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center justify-content-md-end">
                            <span class="me-3" id="statusText">Entrada</span>
                            <div class="form-check form-switch form-check-reverse">
                                <input class="form-check-input status-toggle" type="checkbox" id="movementToggle"
                                    onchange="toggleMovement()">
                                <label class="form-check-label" for="movementToggle"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <button class="btn btn-success btn-lg" onclick="addNewVehicle()">
                        <i class="fas fa-plus me-2"></i>Añadir Nuevo Vehículo
                    </button>
                </div>
            </div>

            <div class="loading text-center" id="loadingIndicator">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2">Cargando vehículos...</p>
            </div>

            <div class="row" id="vehiclesList">
            </div>

            <div class="no-results d-none" id="noResults">
                <i class="fas fa-search fa-3x mb-3"></i>
                <h4>No se encontraron vehículos</h4>
                <p>Intenta con otros términos de búsqueda</p>
            </div>

            <div class="row mt-4 mb-5">
                <div class="col-12 text-center">
                    <button class="btn btn-primary btn-lg px-5" onclick="continueProcess()">
                        <i class="fas fa-arrow-right me-2"></i>Continuar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        // Variables globales
        let selectedVehicle = null;
        let allVehicles = [];

        // Inicializar página
        document.addEventListener('DOMContentLoaded', function() {
            loadVehicles();
            toggleMovement();
        });

        // Cargar vehículos desde el backend
        async function loadVehicles() {
            try {
                showLoading(true);
                // La URL apunta al controlador PHP que devuelve JSON
                const response = await fetch('../../backend/controllers/VehicleController.php');
                const data = await response.json(); // Se sigue esperando JSON aquí

                if (data.success) {
                    allVehicles = data.data; // Almacena todos los vehículos
                    displayVehicles(allVehicles);
                } else {
                    showError(data.message || 'Error al cargar vehículos');
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Error de conexión al cargar vehículos. Asegúrate de que el servidor PHP esté funcionando.');
            } finally {
                showLoading(false);
            }
        }

        // Mostrar vehículos en la interfaz
        function displayVehicles(vehicles) {
            const vehiclesList = document.getElementById('vehiclesList');
            const noResults = document.getElementById('noResults');

            if (vehicles.length === 0) {
                vehiclesList.innerHTML = '';
                noResults.classList.remove('d-none');
                return;
            }

            noResults.classList.add('d-none');

            vehiclesList.innerHTML = vehicles.map(vehicle => `
               <div class="col-12 mb-3">
                <div class="card vehicle-card h-100" onclick="selectVehicle(this, ${vehicle.id_vehiculo}, '${vehicle.placa}')">
                    <div class="card-body position-relative">
                        <div class="d-flex align-items-start">
                            <div class="vehicle-info flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="flex-grow-1">
                                        <h5 class="card-title mb-2">
                                            <i class="${getVehicleIcon(vehicle.tipo)} text-primary me-2"></i>
                                            ${vehicle.placa}
                                        </h5>
                                        <p class="card-text mb-1">
                                            <strong>Modelo:</strong> ${vehicle.modelo}
                                        </p>
                                        <p class="card-text mb-1">
                                            <strong>Color:</strong> ${vehicle.color}
                                        </p>
                                        <p class="card-text mb-0">
                                            <strong>Propietario:</strong> ${vehicle.propietario_nombre_completo}
                                        </p>
                                        <p class="card-text mb-0">
                                            <span class="badge ${getStatusBadgeClass(vehicle.estado)}">${getStatusText(vehicle.estado)}</span>
                                        </p>
                                 
                                    </div>
                                    <button class="btn btn-outline-primary btn-sm btn-edit" onclick="editVehicle(event, ${vehicle.id_vehiculo})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            `).join('');

            // Animar entrada de tarjetas
            const cards = document.querySelectorAll('.vehicle-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'opacity 0.5s, transform 0.5s';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        }

        // Funciones auxiliares para obtener iconos e imágenes
        function getVehicleIcon(tipo) {
            const icons = {
                'carro': 'fas fa-car',
                'moto': 'fas fa-motorcycle',
                'camion': 'fas fa-truck',
                'bicicleta': 'fas fa-bicycle'
            };
            return icons[tipo] || 'fas fa-car';
        }

        function getVehicleImage(tipo) {
            const colors = {
                'carro': '007bff',
                'moto': 'dc3545',
                'camion': '6f42c1',
                'bicicleta': '28a745'
            };
            const text = tipo.toUpperCase().substring(0, 4);
            return `https://via.placeholder.com/80x60/${colors[tipo] || '007bff'}/ffffff?text=${text}`;
        }

        function getStatusBadgeClass(estado) {
            return estado === 'activo' ? 'bg-success' : 'bg-secondary';
        }

        function getStatusText(estado) {
            return estado === 'activo' ? 'Activo' : 'Inactivo';
        }

        // Buscar vehículos
        function searchVehicles(searchTerm) {
            const clearButton = document.getElementById('clearSearch');

            if (searchTerm.length > 0) {
                clearButton.style.display = 'block';
            } else {
                clearButton.style.display = 'none';
            }

            // Debounce para evitar muchas búsquedas
            //clearTimeout(searchTimeout);
            searchTimeout = setTimeout(async () => {
                if (searchTerm.trim() === '') {
                    displayVehicles(allVehicles);
                    return;
                }

                try {
                    showLoading(true);
                    // La URL apunta al controlador PHP que devuelve JSON
                    const response = await fetch(`../../backend/controllers/VehicleController.php?search=${encodeURIComponent(searchTerm)}`);
                    const data = await response.json();

                    if (data.success) {
                        displayVehicles(data.data);
                    } else {
                        displayVehicles([]);
                        showAlert(data.message || 'No se encontraron resultados para la búsqueda.', 'info');
                    }
                } catch (error) {
                    console.error('Error en búsqueda:', error);
                    showAlert('Error de conexión durante la búsqueda. Intenta nuevamente.', 'danger');
                    displayVehicles([]);
                } finally {
                    showLoading(false);
                }
            }, 300);
        }

        // Limpiar búsqueda
        function clearSearch() {
            document.getElementById('searchInput').value = '';
            document.getElementById('clearSearch').style.display = 'none';
            loadVehicles(); // Recargar todos los vehículos
        }

        // Seleccionar vehículo
        function selectVehicle(cardElement, vehicleId, plateNumber) {
            // Remover selección previa
            if (selectedVehicle && selectedVehicle.element !== cardElement) {
                selectedVehicle.element.classList.remove('selected');
            }

            if (cardElement.classList.contains('selected')) {
                cardElement.classList.remove('selected');
                selectedVehicle = null;
            } else {
                cardElement.classList.add('selected');
                selectedVehicle = {
                    element: cardElement,
                    id: vehicleId,
                    plateNumber: plateNumber
                };
            }

            console.log('Vehículo seleccionado:', selectedVehicle);
        }

        // Toggle estado del movimiento
        function toggleMovement() {
            const toggle = document.getElementById('movementToggle');
            const statusText = document.getElementById('statusText');

            if (toggle.checked) {
                statusText.textContent = 'Salida';
                statusText.className = 'me-3 text-danger fw-bold';
            } else {
                statusText.textContent = 'Entrada';
                statusText.className = 'me-3 text-success fw-bold';
            }
        }

        // Continuar proceso
        async function continueProcess() {
            if (!selectedVehicle) {
                showAlert('Por favor selecciona un vehículo para continuar.', 'warning');
                return;
            }

            const toggle = document.getElementById('movementToggle');
            const movementType = toggle.checked ? 'salida' : 'ingreso';

            if (!confirm(`¿Continuar con el proceso de ${movementType} para el vehículo ${selectedVehicle.plateNumber}?`)) {
                return;
            }

            // Crear un formulario para enviar los datos como POST
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '../../backend/controllers/AccessController.php';

            const idVehiculoInput = document.createElement('input');
            idVehiculoInput.type = 'hidden';
            idVehiculoInput.name = 'id_vehiculo';
            idVehiculoInput.value = selectedVehicle.id;
            form.appendChild(idVehiculoInput);

            const tipoAccionInput = document.createElement('input');
            tipoAccionInput.type = 'hidden';
            tipoAccionInput.name = 'tipo_accion';
            tipoAccionInput.value = movementType;
            form.appendChild(tipoAccionInput);

            document.body.appendChild(form);
            form.submit();
        }

        // Añadir nuevo vehículo
        function addNewVehicle() {
            showAlert('Redirigiendo a la pantalla de añadir nuevo vehículo...', 'info');
            window.location.href = '/SENAParking/frontend/views/reg_vehiculos.php';
        }

        // Función para editar vehículo
        function editVehicle(event, vehicleId) {
            window.location.href = `./edit_vehiculos.php?id=${vehicleId}`;
        }

        // Funciones auxiliares
        function showLoading(show) {
            const loader = document.getElementById('loadingIndicator');
            loader.style.display = show ? 'block' : 'none';
        }

        function showError(message) {
            console.error(message);
            showAlert(message, 'danger');
        }

        function showAlert(message, type = 'info') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(alertDiv);

            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }

        //=== funcio para redirigir al dashboard_guardia desde la flecha de retroceso !
        function goBack() {
            window.location.href = "/frontend/views/dashboard_guardia.php";
        }
    </script>
    <script src="../public/js/scriptsDOM.js"></script>

    <!-- script para que cuando se cierre la sesion refresque la ventana -->
    <script src="../public/js/ref_cierre.js"></script>
</body>

</html>