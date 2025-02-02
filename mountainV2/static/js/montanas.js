document.addEventListener('DOMContentLoaded', function() {
    cargarMontanas();
    inicializarFiltros();
    inicializarBusqueda();
});

async function cargarMontanas() {
    try {
        const response = await fetch(`/api.php?endpoint=montanas`);
        const montanas = await response.json();

        const grid = document.getElementById('montanasGrid');
        grid.innerHTML = '';

        montanas.forEach(montana => {
            const card = crearTarjetaMontana(montana);
            grid.appendChild(card);
        });
    } catch (error) {
        console.error('Error al cargar montañas:', error);
    }
}


function crearTarjetaMontana(montana) {
    const card = document.createElement('div');
    card.className = 'montana-card';
    card.innerHTML = `
        <img src="${montana.urlImagenPrincipal}" alt="${montana.Nombre}">
        <div class="montana-card-info">
            <h3>${montana.Nombre}</h3>
            <div class="montana-card-stats">
                <span><i class="fas fa-mountain"></i> ${montana.Altura}m</span>
                <span><i class="fas fa-map-marker-alt"></i> ${montana.Ubicacion}</span>
            </div>
            <p>${montana.Description.substring(0, 100)}...</p>
            <a href="/montana/${montana.MontañaID}" class="btn-ver-mas">Ver más</a>
        </div>
    `;
    return card;
}

function inicializarFiltros() {
    const filtros = document.querySelectorAll('.filtro-grupo select, .filtro-grupo input');
    filtros.forEach(filtro => {
        filtro.addEventListener('change', aplicarFiltros);
    });
}

function aplicarFiltros() {
    const filtros = {
        tipo: document.getElementById('filtroTipo').value,
        alturaMinima: document.getElementById('filtroAltura').value,
        ubicacion: document.getElementById('filtroUbicacion').value
    };
    cargarMontanas(filtros);
}

// Búsqueda
let timeoutBusqueda;
function inicializarBusqueda() {
    const inputBusqueda = document.querySelector('.search-input');
    inputBusqueda.addEventListener('input', (e) => {
        clearTimeout(timeoutBusqueda);
        timeoutBusqueda = setTimeout(() => {
            buscarMontanas(e.target.value);
        }, 300);
    });
}

async function buscarMontanas(termino) {
    if (termino.length < 2) {
        cargarMontanas();
        return;
    }
    
    try {
        const response = await fetch(`/api/montanas/buscar?q=${encodeURIComponent(termino)}`);
        const resultados = await response.json();
        actualizarResultadosBusqueda(resultados);
    } catch (error) {
        console.error('Error en la búsqueda:', error);
    }
}