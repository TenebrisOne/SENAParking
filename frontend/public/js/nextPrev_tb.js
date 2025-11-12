document.addEventListener("DOMContentLoaded", function () {
    const filasPorPagina = 4;
    let paginaActual = 1;

    const tabla = document.getElementById("tablaVehiculos").getElementsByTagName("tbody")[0];
    const filas = tabla.getElementsByTagName("tr");
    const totalPaginas = Math.ceil(filas.length / filasPorPagina);

    const btnAnterior = document.getElementById("btnAnterior");
    const btnSiguiente = document.getElementById("btnSiguiente");
    const infoPagina = document.getElementById("infoPagina");

    function mostrarPagina(pagina) {
        // Validar rango
        if (pagina < 1) pagina = 1;
        if (pagina > totalPaginas) pagina = totalPaginas;

        // Ocultar todas las filas
        for (let i = 0; i < filas.length; i++) {
            filas[i].style.display = "none";
        }

        // Mostrar solo las de esta página
        const inicio = (pagina - 1) * filasPorPagina;
        const fin = inicio + filasPorPagina;
        for (let i = inicio; i < fin && i < filas.length; i++) {
            filas[i].style.display = "";
        }

        // Actualizar info
        infoPagina.textContent = `Página ${pagina} de ${totalPaginas}`;
        paginaActual = pagina;

        // Desactivar/activar botones según la página
        btnAnterior.disabled = paginaActual === 1;
        btnSiguiente.disabled = paginaActual === totalPaginas;
    }

    btnAnterior.addEventListener("click", function () {
        if (paginaActual > 1) {
            mostrarPagina(paginaActual - 1);
        }
    });

    btnSiguiente.addEventListener("click", function () {
        if (paginaActual < totalPaginas) {
            mostrarPagina(paginaActual + 1);
        }
    });

    // Mostrar la primera página al cargar
    mostrarPagina(paginaActual);
});

// Funcionalidad Botones paginación tabla dinámica dashboard admin.
document.addEventListener("DOMContentLoaded", function () {
    const filasPorPagina = 5; // 👈 Cambia este valor según lo que necesites
    let paginaActual = 1;

    const tabla = document.getElementById("tablaDinamica").getElementsByTagName("tbody")[0];
    const filas = tabla.getElementsByTagName("tr");
    const totalPaginas = Math.ceil(filas.length / filasPorPagina);

    const btnAnterior = document.getElementById("btnAnterior");
    const btnSiguiente = document.getElementById("btnSiguiente");
    const infoPagina = document.getElementById("infoPagina");

    function mostrarPagina(pagina) {
      const inicio = (pagina - 1) * filasPorPagina;
      const fin = inicio + filasPorPagina;

      for (let i = 0; i < filas.length; i++) {
        filas[i].style.display = (i >= inicio && i < fin) ? "" : "none";
      }

      infoPagina.textContent = `Página ${pagina} de ${totalPaginas}`;
      btnAnterior.disabled = (pagina === 1);
      btnSiguiente.disabled = (pagina === totalPaginas);
    }

    btnAnterior.addEventListener("click", function () {
      if (paginaActual > 1) {
        paginaActual--;
        mostrarPagina(paginaActual);
      }
    });

    btnSiguiente.addEventListener("click", function () {
      if (paginaActual < totalPaginas) {
        paginaActual++;
        mostrarPagina(paginaActual);
      }
    });

    mostrarPagina(paginaActual); // Inicializar
  });
