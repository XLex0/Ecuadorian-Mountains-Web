document.addEventListener("DOMContentLoaded", function () {
    // Evento del botón de búsqueda
    document.getElementById('search').addEventListener('keyup', function(event) {
        event.preventDefault();
        var searchText = document.getElementById('search').value.trim();
        if (searchText !== "") {
            const mainContent = document.getElementById('main-content');
            mainContent.innerHTML = "<p>Cargando...</p>";

            fetch('montañas.html')
                .then(response => response.text())
                .then(data => {
                    mainContent.innerHTML = data;
                    loadMountain(searchText);
                    cargarComentarios(searchText);
                })
                .catch(error => {
                    console.error("Error cargando la página:", error);
                    mainContent.innerHTML = "<p>Error al cargar la información.</p>";
                });
        }
    });

    // Evento para cargar el menú de montañas
    document.querySelector('li a[href="MountainsMenu.html"]').parentElement.addEventListener('click', function(event) {
        event.preventDefault();
        console.log("Clic en menú de montañas");
        loadMountainsMenu();
    });

    // Cargar página de inicio por defecto
    loadPage('inicio.html');

    // Eventos del menú lateral
    document.getElementById('menu-btn').addEventListener('click', function() {
        document.getElementById('menu-lateral').style.left = '0';
        document.getElementById('overlay').style.display = 'block';
    });

    document.getElementById('menu-close').addEventListener('click', function() {
        document.getElementById('menu-lateral').style.left = '-250px';
        document.getElementById('overlay').style.display = 'none';
    });

    // Manejar clics en el menú
    const menuLinks = document.querySelectorAll('#menu-list a');
    menuLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            console.log("Clic en menú:", this.getAttribute('href'));
            const page = this.getAttribute('href');
            loadPage(page);
            cerrarMenu();
        });
    });
});

// Función para cargar páginas
function loadPage(page) {
    console.log("Cargando página:", page);
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = "<p>Cargando...</p>";

    fetch(page)
        .then(response => {
            console.log("Respuesta fetch:", response.status);
            return response.text();
        })
        .then(data => {
            mainContent.innerHTML = data;
            console.log("Página cargada, inicializando:", page);
            
            // Inicializar funcionalidad específica según la página
            switch(page) {
                case 'guias.html':
                    cargarGuias();
                    break;
                case 'equipo.html':
                    cargarEquipo();
                    break;
                case 'temporadas.html':
                    cargarTemporadas();
                    break;
                case 'refugios.html':
                    console.log("Iniciando carga de refugios");
                    cargarRefugios();
                    break;
                case 'MountainsMenu.html':
                    loadMountainsMenu();
                    break;
            }
        })
        .catch(error => {
            console.error("Error cargando la página:", error);
            mainContent.innerHTML = "<p>Error al cargar la página. Intenta nuevamente.</p>";
        });
}

// Función para cargar guías
function cargarGuias() {
    const container = document.getElementById('guiasContainer');
    if (!container) {
        console.log("No se encontró el contenedor de guías");
        return;
    }

    fetch('../configBD/getGuias.php')
        .then(response => response.json())
        .then(guias => {
            container.innerHTML = '';
            guias.forEach(guia => {
                const card = document.createElement('div');
                card.className = 'guia-card';
                card.innerHTML = `
                    <div class="guia-header">
                        <h2 class="guia-nombre">${guia.nombre}</h2>
                        <span class="guia-cert">${guia.certificaciones}</span>
                    </div>
                    <div class="guia-details">
                        <div class="guia-info">
                            <p><strong>Experiencia:</strong> ${guia.experiencia}</p>
                            <p><strong>Contacto:</strong> ${guia.contacto}</p>
                        </div>
                        <div class="guia-info">
                            <p><strong>Tarifas:</strong> ${guia.tarifas}</p>
                            <p><strong>Montaña:</strong> ${guia.montana_nombre}</p>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = '<p style="text-align: center; color: red;">Error al cargar la información de los guías.</p>';
        });
}

function cargarEquipo() {
    const container = document.getElementById('equipoContainer');
    if (!container) {
        console.log("No se encontró el contenedor de equipo");
        return;
    }

    fetch('../configBD/getEquipo.php')
        .then(response => response.json())
        .then(equipos => {
            container.innerHTML = '';
            
            // Agrupar equipo por montaña
            const equiposPorMontana = {};
            equipos.forEach(equipo => {
                if (!equiposPorMontana[equipo.montana_nombre]) {
                    equiposPorMontana[equipo.montana_nombre] = [];
                }
                equiposPorMontana[equipo.montana_nombre].push(equipo);
            });

            // Crear sección para cada montaña
            for (const montana in equiposPorMontana) {
                const montanaSection = document.createElement('div');
                montanaSection.className = 'montana-grupo';
                
                montanaSection.innerHTML = `
                    <h2 class="montana-titulo">${montana}</h2>
                    <div class="equipo-grid">
                        ${equiposPorMontana[montana].map(equipo => `
                            <div class="equipo-item ${equipo.obligatorio == 1 ? 'obligatorio' : 'opcional'}">
                                <span class="badge ${equipo.obligatorio == 1 ? 'badge-obligatorio' : 'badge-opcional'}">
                                    ${equipo.obligatorio == 1 ? 'Obligatorio' : 'Opcional'}
                                </span>
                                <h3 style="margin-top: 0;">${equipo.nombre}</h3>
                                <p style="color: #666;">${equipo.descripcion}</p>
                            </div>
                        `).join('')}
                    </div>
                `;
                
                container.appendChild(montanaSection);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = '<p style="text-align: center; color: red;">Error al cargar la información del equipo.</p>';
        });
}

function cargarTemporadas() {
    const container = document.getElementById('temporadasContainer');
    if (!container) {
        console.log("No se encontró el contenedor de temporadas");
        return;
    }

    fetch('../configBD/getTemporadas.php')
        .then(response => response.json())
        .then(temporadas => {
            container.innerHTML = '';
            
            const meses = [
                'Enero', 'Febrero', 'Marzo', 'Abril', 
                'Mayo', 'Junio', 'Julio', 'Agosto',
                'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
            ];

            temporadas.forEach(temporada => {
                const card = document.createElement('div');
                card.className = 'montana-card';
                
                // Crear línea de tiempo de meses
                const timelineMeses = meses.map((mes, index) => {
                    const mesNum = index + 1;
                    const activo = mesNum >= temporada.mes_inicio && mesNum <= temporada.mes_fin;
                    return `<div class="mes ${activo ? 'mes-activo' : ''}">${mes.substring(0, 3)}</div>`;
                }).join('');

                card.innerHTML = `
                    <div class="montana-header">
                        <h2 style="margin: 0;">${temporada.montana_nombre}</h2>
                        <span>Temporada: ${meses[temporada.mes_inicio - 1]} - ${meses[temporada.mes_fin - 1]}</span>
                    </div>
                    <div class="temporada-info">
                        <div class="meses-timeline">
                            ${timelineMeses}
                        </div>
                        <p class="temporada-descripcion">${temporada.descripcion}</p>
                        <div class="condiciones">
                            <div class="condiciones-title">Condiciones Climáticas:</div>
                            <p>${temporada.condiciones_climaticas}</p>
                        </div>
                    </div>
                `;
                
                container.appendChild(card);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = '<p style="text-align: center; color: red;">Error al cargar la información de temporadas.</p>';
        });
}

function cargarRefugios() {
    console.log("Iniciando función cargarRefugios");
    const container = document.getElementById('refugiosContainer');
    if (!container) {
        console.log("No se encontró el contenedor de refugios");
        return;
    }

    fetch('../configBD/getRefugios.php')
        .then(response => {
            console.log("Respuesta de getRefugios:", response.status);
            return response.json();
        })
        .then(refugios => {
            console.log("Datos de refugios recibidos:", refugios);
            container.innerHTML = '';
            
            refugios.forEach(refugio => {
                const card = document.createElement('div');
                card.className = 'refugio-card';
                
                // Convertir string de servicios en lista
                const servicios = refugio.servicios.split(',').map(servicio => 
                    `<li>${servicio.trim()}</li>`
                ).join('');

                card.innerHTML = `
                    <div class="refugio-header">
                        <h2 style="margin: 0;">${refugio.nombre}</h2>
                        <span class="montana-badge">${refugio.montana_nombre}</span>
                    </div>
                    <div class="refugio-content">
                        <div class="info-grid">
                            <div class="info-item">
                                <strong>Altitud:</strong> ${refugio.altitud} msnm
                            </div>
                            <div class="info-item">
                                <strong>Capacidad:</strong> ${refugio.capacidad} personas
                            </div>
                            <div class="info-item">
                                <strong>Estado:</strong> 
                                <span class="estado-operativo">${refugio.estado_actual}</span>
                            </div>
                        </div>

                        <div style="margin-top: 20px;">
                            <h3>Servicios Disponibles</h3>
                            <ul class="servicios-lista">
                                ${servicios}
                            </ul>
                        </div>

                        <div class="contacto-info">
                            <strong>Contacto:</strong> 
                            ${refugio.contacto.includes('@') 
                                ? `<a href="mailto:${refugio.contacto}">${refugio.contacto}</a>`
                                : refugio.contacto.startsWith('http') 
                                    ? `<a href="${refugio.contacto}" target="_blank">Sitio web</a>`
                                    : refugio.contacto
                            }
                        </div>
                    </div>
                `;
                
                container.appendChild(card);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = '<p style="text-align: center; color: red;">Error al cargar la información de refugios.</p>';
        });
}

// Función auxiliar para cerrar el menú
function cerrarMenu() {
    document.getElementById('menu-lateral').style.left = '-250px';
    document.getElementById('overlay').style.display = 'none';
}