 function loadMountain(texto) {
         var searchText = texto;
         var globalId = searchText;
         var extraer = 'descripcion';
         console.log(searchText + " " + extraer);
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
            console.log(data);
             // Limpiar el contenido previo 
         const descripcionElement = document.getElementById('descripcion');
         descripcionElement.innerHTML = ''; 
         // Iterar y añadir elementos
         data.forEach(item => {
             const p = document.createElement('p');
             p.textContent = item.descripcion;
             descripcionElement.appendChild(p);
             document.getElementById("longitud").textContent = item.longitud;
             document.getElementById("latitud").textContent = item.latitud;
             document.getElementById("ubicacion").textContent = item.ubicacion;
             document.getElementById("altura").textContent = item.altura;
             document.getElementById("tipo").textContent = item.tipo;
             // Cargar la imagen de la montaña
             document.getElementById('mountainImage').src = item.urlImagenPrincipal;
             // Cargar el nombre
             document.getElementById('mountainName').textContent = item.nombre;

             document.getElementById("map").innerHTML= item.mapsEmbeded;
          
             });
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
                <div class="mountain-info">
                    <h2>${item.nombre}</h2>
                    <p><strong>Ubicación:</strong> ${item.ubicacion}</p>
                    <p><strong>Altura:</strong> ${item.altura} m</p>
                    <p><strong>Tipo:</strong> ${item.tipo}</p>
                    <p><strong>Coordenadas:</strong> ${item.latitud}, ${item.longitud}</p>
                </div>
                <div class="mountain-image-container">
                    <img src="${item.urlImagenPrincipal}" alt="Imagen de ${item.nombre}" class="mountain-image">
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
    mainContent.innerHTML = "<p>Cargando...</p>"; // Mensaje de carga

 
    fetch('montañas.html')
        .then(response => response.text())
        .then(data => {
            console.log(data);
            mainContent.innerHTML = data; // Carga montañas.html dentro del main
            loadMountain(mountainId); // Llamar a la función con el id de la montaña seleccionada
            cargarComentarios(mountainId); // Llamar a la función para obtener comentarios
        })
        .catch(error => {
            console.error("Error cargando la página:", error);
            mainContent.innerHTML = "<p>Error al cargar la información.</p>";
        });
        document.getElementById('menu-lateral').style.left = '-250px';
        document.getElementById('overlay').style.display = 'none';
}
