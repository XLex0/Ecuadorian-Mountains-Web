// Función que oculta el contenedor con id 'initialAdmin' cuando se hace clic en los botones
function hideInitialAdmin(action) {
    const initialAdmin = document.getElementById("initialAdmin");
    if (initialAdmin) {
        initialAdmin.style.display = "none";
        
        if (action === 'add') {
            const form = document.getElementById("addForm");
            form.innerHTML = `
                <div id="addAdmin" style="display: block; max-width: 600px; margin: 0 auto; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); background-color: #fff;">
                    <div style="text-align: center;">
                        <h2>Agregar Montaña</h2>
            <form id="addMountainForm" style="display: flex; flex-direction: column; align-items: center; gap: 15px;">
                <input type="text" id="mountainName" placeholder="Nombre de la montaña" required style="padding: 12px; margin: 5px; border-radius: 8px; border: 1px solid rgb(200, 200, 200); width: 100%; max-width: 400px;">
                
                <div style="display: flex; align-items: center; gap: 10px; width: 100%; max-width: 400px;">
                    <input type="text" id="mountainImage" placeholder="URL de la imagen" required style="padding: 12px; margin: 5px; border-radius: 8px; border: 1px solid rgb(200, 200, 200); width: 80%;">
                    <button type="button" onclick="testImage(document.getElementById('mountainImage').value)" style="padding: 12px 20px; background-color: rgb(62, 135, 95); color: white; border: none; border-radius: 8px; cursor: pointer;">Test</button>
                </div>
                
                <textarea id="descripcion" placeholder="Descripción" required style="padding: 12px; margin: 5px; border-radius: 8px; border: 1px solid rgb(200, 200, 200); width: 100%; max-width: 400px; height: 100px;"></textarea>
                
                <div style="display: flex; gap: 10px; width: 100%; max-width: 400px;">
                    <input type="text" id="longitud" placeholder="Longitud" required style="padding: 12px; margin: 5px; border-radius: 8px; border: 1px solid rgb(200, 200, 200); width: 48%;">
                    <input type="text" id="latitud" placeholder="Latitud" required style="padding: 12px; margin: 5px; border-radius: 8px; border: 1px solid rgb(200, 200, 200); width: 48%;">
                </div>

                <div style="display: flex; gap: 10px; width: 100%; max-width: 400px;">
                    <input type="text" id="ubicacion" placeholder="Ubicación" required style="padding: 12px; margin: 5px; border-radius: 8px; border: 1px solid rgb(200, 200, 200); width: 48%;">
                    <input type="text" id="altura" placeholder="Altura" required style="padding: 12px; margin: 5px; border-radius: 8px; border: 1px solid rgb(200, 200, 200); width: 48%;">
                </div>
                
                <input type="text" id="tipo" placeholder="Tipo" required style="padding: 12px; margin: 5px; border-radius: 8px; border: 1px solid rgb(200, 200, 200); width: 100%; max-width: 400px;">
                
                <div style="display: flex; align-items: center; gap: 10px; width: 100%; max-width: 400px;">
                    <input type="text" id="mapsEmbeded" placeholder="URL de mapa (Embebido)" required style="padding: 12px; margin: 5px; border-radius: 8px; border: 1px solid rgb(200, 200, 200); width: 80%;">
                    <button type="button" onclick="testMap(document.getElementById('mapsEmbeded').value)" style="padding: 12px 20px; background-color: rgb(62, 135, 95); color: white; border: none; border-radius: 8px; cursor: pointer;">Test</button>
                </div>

                <button type="button" onclick="insertarMontaña()" style="padding: 12px 20px; background-color: rgb(62, 135, 95); color: white; border: none; border-radius: 8px; cursor: pointer; margin-top: 20px;">Guardar Montaña</button>
            </form>

                    </div>
                </div>
            `;


        } else if (action === 'delete') {
            const form = document.getElementById("addForm");
            form.innerHTML = `
            <div id="deleteAdmin" style="display: block; max-width: 600px; margin: 0 auto; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); background-color: #fff;">
                <div style="text-align: center;">
                    <h2>Borrar Montaña</h2>
                    <form id="deleteMountainForm" style="display: flex; flex-direction: column; align-items: center; gap: 15px;">
                        <input type="text" id="deleteMountainName" placeholder="Nombre de la montaña a borrar" required style="padding: 12px; margin: 5px; border-radius: 8px; border: 1px solid rgb(200, 200, 200); width: 100%; max-width: 400px;">
                        
                        <button type="button" onclick="confirmDelete()" style="padding: 12px 20px; background-color: rgb(255, 85, 85); color: white; border: none; border-radius: 8px; cursor: pointer; margin-top: 20px;">Borrar Montaña</button>
                    </form>
                </div>
            </div>
            `;
            
        }
    }
}

function testImage(imageURL) {
    const contenedor = document.getElementById('testAddForm');
    contenedor.innerHTML = `
    <div style="display: inline-block; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 10px; background-color: #fff; text-align: center; margin-top: 20px;">
        <img src="${imageURL}" 
             style="max-width: 100%; max-height: 200px; border-radius: 8px;" 
             onerror="this.onerror=null; this.src=''; this.alt='No cargó correctamente la URL';">
        <div style="color: red; font-size: 14px; margin-top: 10px;" id="error-message"></div>
    </div>
`;
}

function testMap(mapsEmbeded){
    const contenedor = document.getElementById('testAddForm');
    contenedor.innerHTML = `
    <div style="display: inline-block; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 10px; background-color: #fff; text-align: center; margin-top: 20px;">
        "${mapsEmbeded}" 
                style="width: 100%; height: 300px; border-radius: 8px;" 
                onerror="this.onerror=null; this.src=''; this.alt='No cargó correctamente la URL';"></iframe>
        <div style="color: red; font-size: 14px; margin-top: 10px;" id="error-message"></div>
    </div>
`;
}

function testMap(mapsEmbeded) {
    const contenedor = document.getElementById('testAddForm');
    contenedor.innerHTML = `
        <div style="display: inline-block; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 10px; background-color: #fff; text-align: center; margin-top: 20px;">
            <div id="mapContainer" style="width: 100%; height: 300px; border-radius: 8px; overflow: hidden;">
                ${mapsEmbeded}
            </div>
            <div style="color: red; font-size: 14px; margin-top: 10px;" id="error-message"></div>
        </div>
    `;

    const iframe = document.querySelector('#mapContainer iframe');
    if (iframe) {
        iframe.onerror = function() {
            document.getElementById('error-message').innerText = 'No cargó correctamente el mapa.';
        };
    }
}



function confirmDelete() {
    const mountainName = document.getElementById('deleteMountainName').value;

    // Verifica si el nombre de la montaña está vacío
    if (!mountainName) {
        alert('Por favor, ingrese el nombre de la montaña.');
        return;
    }

    // Confirmar la acción
    const confirmDelete = confirm(`¿Está seguro de que desea eliminar la montaña "${mountainName}"?`);

    if (confirmDelete) {
        deleteMountain(mountainName);
    }
}

function insertarMontaña() {
    // Capturar los valores de los campos del formulario
    const nombre = document.getElementById('mountainName').value;
    const descripcion = document.getElementById('descripcion').value;
    const longitud = parseFloat(document.getElementById('longitud').value); // Convertir a float
    const latitud = parseFloat(document.getElementById('latitud').value); // Convertir a float
    const ubicacion = document.getElementById('ubicacion').value;
    const altura = parseInt(document.getElementById('altura').value); // Convertir a int
    const tipo = document.getElementById('tipo').value;
    const urlImagenPrincipal = document.getElementById('mountainImage').value;
    const mapsEmbeded = document.getElementById('mapsEmbeded').value;

    // Verifica si todos los campos están completos
    if (!nombre || !descripcion || !longitud || !latitud || !ubicacion || !altura || !tipo || !urlImagenPrincipal || !mapsEmbeded) {
        alert('Por favor, complete todos los campos.');
        return;
    }

    // Verificar que longitud, latitud y altura sean números válidos
    if (isNaN(longitud) || isNaN(latitud) || isNaN(altura)) {
        alert('Por favor, ingrese valores válidos para longitud, latitud y altura.');
        return;
    }

    // Enviar los datos al archivo PHP para insertar la montaña
    fetch('../configBD/insertMontana.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'nombre=' + encodeURIComponent(nombre)
            + '&descripcion=' + encodeURIComponent(descripcion)
            + '&longitud=' + encodeURIComponent(longitud)
            + '&latitud=' + encodeURIComponent(latitud)
            + '&ubicacion=' + encodeURIComponent(ubicacion)
            + '&altura=' + encodeURIComponent(altura)
            + '&tipo=' + encodeURIComponent(tipo)
            + '&urlImagenPrincipal=' + encodeURIComponent(urlImagenPrincipal)
            + '&mapsEmbeded=' + encodeURIComponent(mapsEmbeded)
    })
    .then(response => response.text())  // Usamos text() para ver la respuesta
    .then(data => {
        try {
            const jsonData = JSON.parse(data);  // Intentamos convertirlo a JSON
            if (jsonData.success) {
                alert('Montaña insertada correctamente');
                
                // Hacer visible el 'initialAdmin'
                const initialAdmin = document.getElementById("initialAdmin");
                if (initialAdmin) {
                    initialAdmin.style.display = "block";  // Mostrarlo nuevamente
                }
    
                // Limpiar el contenido de 'testAddForm'
                const contenedor = document.getElementById('testAddForm');
                contenedor.innerHTML = '';  // Vaciar el contenedor
    
                // Limpiar el formulario 'addForm'
                const form = document.getElementById("addForm");
                form.innerHTML = '';  // Vaciar el formulario
    
            } else {
                alert('Hubo un error al insertar la montaña');
            }
        } catch (error) {

        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error de conexión');
    });

}


function deleteMountain(mountainName) {
    // Enviar los datos al archivo PHP para eliminar la montaña
    fetch('../configBD/deleteMontana.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'nombre=' + encodeURIComponent(mountainName)
    })
    .then(response => response.text())  // Usamos text() para ver la respuesta
    .then(data => {
        try {
            const jsonData = JSON.parse(data);  // Intentamos convertirlo a JSON
            if (jsonData.success) {
                alert('Montaña eliminada correctamente');
                
                // Hacer visible el 'initialAdmin'
                const initialAdmin = document.getElementById("initialAdmin");
                if (initialAdmin) {
                    initialAdmin.style.display = "block";  // Mostrarlo nuevamente
                }
    
                // Limpiar el contenido de 'testAddForm'
                const contenedor = document.getElementById('testAddForm');
                contenedor.innerHTML = '';  // Vaciar el contenedor
    
                // Limpiar el formulario 'addForm'
                const form = document.getElementById("addForm");
                form.innerHTML = '';  // Vaciar el formulario
    
            } else {
                alert('Hubo un error al eliminar la montaña');
            }
        } catch (error) {
           
        }
    })
    .catch(error => {
        console.error('Error de conexión o al procesar la solicitud:', error);
        alert('Error de conexión');
    });
}
