document.addEventListener('DOMContentLoaded', function() {
    const montanaId = getMontanaIdFromUrl();
    cargarDetallesMontana(montanaId);
    cargarComentarios(montanaId);

    // Manejar envío de comentarios
    const commentForm = document.getElementById('commentForm');
    if (commentForm) {
        commentForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (!isAuthenticated()) {
                alert('Debes iniciar sesión para comentar');
                window.location.href = '/login';
                return;
            }
            
            const comentario = document.getElementById('comentarioInput').value;
            const calificacion = document.querySelector('input[name="rating"]:checked').value;
            
            await enviarComentario(montanaId, comentario, calificacion);
            await cargarComentarios(montanaId);
            commentForm.reset();
        });
    }

    // Botones de navegación entre montañas
    document.getElementById('prevMontana').addEventListener('click', () => cambiarMontana(-1));
    document.getElementById('nextMontana').addEventListener('click', () => cambiarMontana(1));
});

async function cargarDetallesMontana(id) {
    try {
        const response = await fetch(`/api.php?endpoint=montana&id=${id}`);
        const montana = await response.json();
        mostrarDetallesMontana(montana);
    } catch (error) {
        console.error('Error:', error);
    }
}

async function cargarComentarios(montanaId) {
    try {
        const response = await fetch(`/api.php?endpoint=comentarios&montana_id=${montanaId}`);
        const comentarios = await response.json();
        mostrarComentarios(comentarios);
    } catch (error) {
        console.error('Error:', error);
    }
}

function mostrarDetallesMontana(montana) {
    document.getElementById('montanaImagen').src = montana.urlImagenPrincipal;
    document.getElementById('montanaNombre').innerText = montana.nombre;
    document.getElementById('montanaUbicacion').innerText = `Ubicación: ${montana.ubicacion}`;
    document.getElementById('montanaAltura').innerText = `Altura: ${montana.altura}m`;
    document.getElementById('montanaDescripcion').innerText = montana.descripcion;
    document.getElementById('montanaMapa').innerHTML = montana.mapsEmbeded;
}

function cambiarMontana(direccion) {
    let montanaId = getMontanaIdFromUrl();
    montanaId = parseInt(montanaId) + direccion;
    window.location.href = `/montana-detalle.html?id=${montanaId}`;
}

function getMontanaIdFromUrl() {
    const params = new URLSearchParams(window.location.search);
    return params.get('id');
}

function isAuthenticated() {
    return localStorage.getItem('token') !== null;
}
