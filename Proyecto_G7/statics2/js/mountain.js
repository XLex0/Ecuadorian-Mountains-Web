function loadMountain(texto) {
    var searchText = texto;
    var extraer = 'descripcion';
    currentMontanaId = texto; // Aseguramos que se guarde el ID para los comentarios
    
    fetch('../configBD/mountain.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'searchText=' + encodeURIComponent(searchText)
            + '&extraer=' + encodeURIComponent(extraer)
    })
    .then(response => response.json())
    .then(data => {
        if (data && data.length > 0) {
            const item = data[0];
            
            // Actualizar elementos de la información principal
            document.getElementById('mountainName').textContent = item.nombre;
            document.getElementById('mountainImage').src = item.urlImagenPrincipal;
            document.getElementById('descripcion').textContent = item.descripcion;
            document.getElementById('longitud').textContent = item.longitud;
            document.getElementById('latitud').textContent = item.latitud;
            document.getElementById('ubicacion').textContent = item.ubicacion;
            document.getElementById('altura').textContent = item.altura;
            document.getElementById('tipo').textContent = item.tipo;
            document.getElementById('map').innerHTML = item.mapsEmbeded;

            // Una vez cargada la información, inicializamos el sistema de comentarios
            initializeRatingSystem();
            cargarComentarios(searchText);
        }
    })
    .catch(error => console.error('Error:', error));
}

// cargar tarjetas de menu
 
function loadMountainsMenu() {
    console.log("Cargando montañas...");
    var searchText = "";
    var extraer = 'all'; // Ahora obtendremos todas las montañas
    console.log("Buscando montañas con criterio: " + extraer);
    
    fetch('../configBD/mountain.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'searchText=' + encodeURIComponent(searchText)
            + '&extraer=' + encodeURIComponent(extraer)
    })
    .then(response => response.json())
    .then(data => {
        

        const container = document.getElementById('mountainContainer');

        data.forEach(item => {
            const card = document.createElement('div');
            card.classList.add('mountain-card');
            
            card.innerHTML = `
                <div class="mountain-card" id="mountain-${item.id}" onclick="loadMountainInfo(${item.id})">
                    <div class="mountain-image-container">
                        <img src="${item.urlImagenPrincipal}" alt="Imagen de ${item.nombre}" class="mountain-image">
                    </div>
                    <div class="mountain-info">
                        <h2>${item.nombre}</h2>
                        <div class="mountain-details">
                            <span class="detail-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M12 22s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 8.2c0 7.3-8 11.8-8 11.8z"/>
                                </svg>
                                ${item.ubicacion}
                            </span>
                            <span class="detail-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                                </svg>
                                ${item.altura} m
                            </span>
                        </div>
                        <p class="mountain-type">${item.tipo}</p>
                    </div>
                </div>
            `;
            
            container.appendChild(card);
        });
    })
    .catch(error => console.error('Error:', error));    
    document.getElementById('menu-lateral').style.left = '-250px';
    document.getElementById('overlay').style.display = 'none';

}

function loadMountainInfo(mountainId) {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = "<p>Cargando...</p>";

    fetch('montañas.html')
        .then(response => response.text())
        .then(data => {
            mainContent.innerHTML = data;
            // Primero cargamos la información de la montaña
            loadMountain(mountainId);
        })
        .catch(error => {
            console.error("Error cargando la página:", error);
            mainContent.innerHTML = "<p>Error al cargar la información.</p>";
        });
}