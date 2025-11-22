<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Usuarios | SENAParking</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/sityles_views.css">
    <style>
        .header-section {
            background: #4CAF50;
            color: white;
            padding: 1.5rem 0;
            margin-bottom: 2rem;
            border-radius: 8px;
        }

        .search-section,
        .table-section,
        .pagination-section {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .search-input {
            border-radius: 25px;
            padding: 0.75rem 1.5rem;
            border: 2px solid #e9ecef;
            transition: border-color 0.2s;
        }

        .search-input:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }

        .clear-search {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: none;
            color: #6c757d;
            cursor: pointer;
            display: none;
        }

        .search-container {
            position: relative;
        }

        .no-results {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }

        .table-responsive {
            margin-top: 1rem;
        }

        .table thead th {
            background-color: #e9ecef;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .btn-detail {
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem;
        }

        .pagination .page-link {
            border-radius: 0.5rem;
            margin: 0 0.2rem;
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
        }

        /* Loading indicator */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
    </style>
</head>

<body class="bg-light">
    <div id="loadingIndicator" class="loading-overlay" style="display: none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>
    <div id="header-container"></div>

    <div class="container-fluid">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <button class="btn btn-secondary" onclick="goBack()">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </button>
                <img src="../public/images/logo_sena.png" alt="Logo SENA" style="width: 80px;">
            </div>

            <div class="header-section text-center">
                <h1 class="mb-2"><i class="fas fa-file-alt me-3"></i>Reporte de Usuarios</h1>
                <p class="lead">Lista y gestión de usuarios registrados en el parqueadero.</p>
            </div>

            <div class="search-section">
                <form id="searchForm" class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-3 mb-md-0">
                            <i class="fas fa-search me-2 text-primary"></i>
                            Buscar Usuarios
                        </h5>
                    </div>
                    <div class="col-md-4">
                        <div class="search-container">
                            <input type="text" class="form-control search-input" id="searchInput"
                                placeholder="Buscar por nombre, apellido o documento..." name="search" value="">
                            <button type="button" class="clear-search" id="clearSearch">
                                <i class="fas fa-times"></i>
                            </button>
                            <button type="submit" class="btn btn-primary d-none">Buscar</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-section">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tipo Usuario</th>
                                <th>Documento</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Contacto</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody">
                            </tbody>
                    </table>
                </div>

                <div class="no-results d-none" id="noResults">
                    <i class="fas fa-users-slash fa-3x mb-3"></i>
                    <h4>No se encontraron usuarios</h4>
                    <p>Intenta con otros términos de búsqueda o verifica los filtros.</p>
                </div>

                <nav aria-label="Page navigation" class="pagination-section">
                    <ul class="pagination justify-content-center" id="paginationControls">
                        </ul>
                </nav>
            </div>


            <div class="row mt-4 mb-5">
                <div class="col-12 text-center">
                    <a href="reportes_generales.php" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-chart-bar me-2"></i>Ver Reportes Generales
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="../public/js/scriptsDOM.js"></script>

    <script>
        const usersTableBody = document.getElementById('usersTableBody');
        const paginationControls = document.getElementById('paginationControls');
        const searchInput = document.getElementById('searchInput');
        const searchForm = document.getElementById('searchForm');
        const clearButton = document.getElementById('clearSearch');
        const noResultsMessage = document.getElementById('noResults');
        const loadingIndicator = document.getElementById('loadingIndicator');

        let currentPage = 1;
        let currentSearchTerm = '';

        function showLoading(show) {
            loadingIndicator.style.display = show ? 'flex' : 'none';
        }

        async function loadUsers(page = 1, searchTerm = '') {
            showLoading(true);
            try {
                const response = await fetch(`../../backend/controllers/ReportesUserController.php?page=${page}&search=${encodeURIComponent(searchTerm)}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();

                usersTableBody.innerHTML = ''; // Clear existing rows
                paginationControls.innerHTML = ''; // Clear existing pagination
                
                currentPage = page;
                currentSearchTerm = searchTerm;

                if (data.users && data.users.length > 0) {
                    noResultsMessage.classList.add('d-none');
                    data.users.forEach(user => {
                        const row = `
                            <tr>
                                <td>${user.id_userPark}</td>
                                <td>${user.tipoUserUpark}</td>
                                <td>${user.numeroDocumentoUpark}</td>
                                <td>${user.nombresUpark}</td>
                                <td>${user.apellidosUpark}</td>
                                <td>${user.numeroContactoUpark || 'N/A'}</td>
                                <td class="text-center">
                                    <a href="reporte_usuario_detalle.php?id=${user.id_userPark}" class="btn btn-info btn-sm btn-detail">
                                        <i class="fas fa-info-circle me-1"></i>Ver Detalles
                                    </a>
                                </td>
                            </tr>
                        `;
                        usersTableBody.insertAdjacentHTML('beforeend', row);
                    });

                    // Render pagination
                    renderPagination(data.totalPages, data.currentPage, data.searchTerm);
                } else {
                    usersTableBody.innerHTML = '<tr><td colspan="7" class="text-center py-4"></td></tr>';
                    noResultsMessage.classList.remove('d-none');
                }
            } catch (error) {
                console.error('Error fetching users:', error);
                usersTableBody.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Error al cargar usuarios: ${error.message}</td></tr>`;
                noResultsMessage.classList.add('d-none');
            } finally {
                showLoading(false);
            }
        }

        function renderPagination(totalPages, currentPage, searchTerm) {
            let paginationHtml = '';

            // Previous button
            paginationHtml += `
                <li class="page-item ${currentPage <= 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage - 1}">Anterior</a>
                </li>
            `;

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                paginationHtml += `
                    <li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>
                `;
            }

            // Next button
            paginationHtml += `
                <li class="page-item ${currentPage >= totalPages ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage + 1}">Siguiente</a>
                </li>
            `;
            paginationControls.innerHTML = paginationHtml;

            // Add event listeners to pagination links
            paginationControls.querySelectorAll('.page-link').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const newPage = parseInt(e.target.dataset.page);
                    if (!isNaN(newPage) && newPage > 0 && newPage <= totalPages) {
                        loadUsers(newPage, currentSearchTerm);
                    }
                });
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (typeof loadHeader === 'function') {
                loadHeader();
            }

            // Initial load of users
            loadUsers(currentPage, currentSearchTerm);

            // Search form submission
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const searchTerm = searchInput.value.trim();
                loadUsers(1, searchTerm); // Reset to first page on new search
            });

            // Clear search button
            clearButton.addEventListener('click', function() {
                searchInput.value = '';
                loadUsers(1, ''); // Load all users from first page
            });

            // Toggle clear search button visibility
            function toggleClearButton() {
                if (searchInput.value.length > 0) {
                    clearButton.style.display = 'block';
                } else {
                    clearButton.style.display = 'none';
                }
            }
            searchInput.addEventListener('input', toggleClearButton);
            toggleClearButton(); // Initial check
        });

        function goBack() {
            window.history.back();
        }
    </script>
</body>

</html>