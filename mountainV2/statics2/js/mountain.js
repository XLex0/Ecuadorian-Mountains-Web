

function loadMountain(texto) {
        var searchText = texto;
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
            // Limpiar el contenido previo (usando JavaScript vanilla)
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

            
            });
        })
        .catch(error => console.error('Error:', error));


        
}



