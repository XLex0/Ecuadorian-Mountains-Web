function loadMountain(texto) {
    var searchText = texto;
    var extraer = 'descripcion';
    console.log(`Buscando montaña con ID: ${searchText}`);

    fetch('../configBD/mountain.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `searchText=${encodeURIComponent(searchText)}&extraer=${encodeURIComponent(extraer)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            console.error("Error del servidor:", data.error);
            document.getElementById('descripcion').innerHTML = `<p style="color:red;">${data.error}</p>`;
            return;
        }

        const descripcionElement = document.getElementById('descripcion');
        descripcionElement.innerHTML = ''; 

        data.forEach(item => {
            const p = document.createElement('p');
            p.textContent = item.descripcion;
            descripcionElement.appendChild(p);

            document.getElementById("longitud").textContent = item.longitud;
            document.getElementById("latitud").textContent = item.latitud;
            document.getElementById("ubicacion").textContent = item.ubicacion;
            document.getElementById("altura").textContent = item.altura;
            document.getElementById("tipo").textContent = item.tipo;
            document.getElementById('mountainImage').src = item.urlImagenPrincipal;
            document.getElementById('mountainName').textContent = item.nombre;
        });
    })
    .catch(error => {
        console.error('Error de red:', error);
        document.getElementById('descripcion').innerHTML = `<p style="color:red;">Error de conexión con el servidor.</p>`;
    });
}


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





