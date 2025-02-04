// script2.js

document.addEventListener("DOMContentLoaded", function () {

 // Evento del botón de búsqueda para cargar dinámicamente la información
    document.getElementById('search').addEventListener('keyup', function(event) {
        event.preventDefault(); // Evita que el formulario recargue la página
        
        var searchText = document.getElementById('search').value.trim();
        if (searchText !== "") {
            // Limpiar el contenido anterior y mostrar mensaje de carga
            const mainContent = document.getElementById('main-content');
            mainContent.innerHTML = "<p>Cargando...</p>";

            // Cargar dinámicamente montañas.html dentro de main-content
            fetch('montañas.html')
                .then(response => response.text())
                .then(data => {
                    mainContent.innerHTML = data; // Carga montañas.html dentro del main
                    loadMountain(searchText); // Llamar a la función para obtener datos de la montaña
                    cargarComentarios(searchText); // Llamar a la función para obtener comentarios
                })
                .catch(error => {
                    console.error("Error cargando la página:", error);
                    mainContent.innerHTML = "<p>Error al cargar la información.</p>";
                });
        }
    });
    
// Cargar la página de inicio de forma predeterminada
loadPage('index.html'); // Esto carga "Inicio" cuando se carga la página


    // Evento para abrir el menú
    document.getElementById('menu-btn').addEventListener('click', function() {
        document.getElementById('menu-lateral').style.left = '0';
        document.getElementById('overlay').style.display = 'block';
    });

    // Evento para cerrar el menú
    document.getElementById('menu-close').addEventListener('click', function() {
        document.getElementById('menu-lateral').style.left = '-250px';
        document.getElementById('overlay').style.display = 'none';
    });

    // Evento para manejar los clics en el menú y cargar el contenido
    const menuLinks = document.querySelectorAll('#menu-list a');
    menuLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const page = this.getAttribute('href');

            // Limpiar el contenido previo antes de cargar el nuevo contenido
            const mainContent = document.getElementById('main-content');
            mainContent.innerHTML = '';  // Limpiar el contenido de la página actual

            // Cargar la página dinámica (AJAX)
            loadPage(page);
        });
    });

    function loadPage(page) {
        // Cargar el contenido de la página solicitada
        const mainContent = document.getElementById('main-content');

        fetch(page)
            .then(response => response.text())
            .then(data => {
                // Insertar el contenido de la nueva página
                mainContent.innerHTML = data;
            })
            .catch(error => {
                console.error("Error cargando la página:", error);
                mainContent.innerHTML = "<p>Hubo un error al cargar la página. Intenta nuevamente.</p>";
            });
    }
});
