document.addEventListener("DOMContentLoaded", function() {
    const formLogin = document.getElementById('formLogin');

    if (formLogin) {
        formLogin.addEventListener('submit', function(event) {
            event.preventDefault(); // Evita la recarga
            console.log('Formulario enviado');
            verificarUsuario();
        });
    }
});


let currentMontanaId = null;

function checkUserStatus() {
    fetch('../configBD/check_session.php')
        .then(response => response.json())
        .then(data => {
            const userStatusContainer = document.querySelector('.comment-user-status');
            if (userStatusContainer) {
                userStatusContainer.innerHTML = `
                    <div class="user-status-badge ${data.logged_in ? 'logged-in' : 'anonymous'}">
                        <i class="fas ${data.logged_in ? 'fa-user' : 'fa-user-secret'}"></i>
                        <span class="status-text">
                            ${data.logged_in 
                                ? `Comentando como: <strong>${data.username}</strong>` 
                                : `Comentando como anónimo - <a href="login.html">Iniciar sesión</a>`}
                        </span>
                    </div>
                `;
            }
        })
        .catch(error => console.error('Error checking user status:', error));
}

function initializeRatingSystem() {
    const stars = document.querySelectorAll('.stars i');
    if (!stars.length) return;

    // Revisar el estado del usuario
    checkUserStatus();
    
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.dataset.rating;
            document.getElementById('rating-value').value = rating;
            updateStars(rating);
        });

        star.addEventListener('mouseover', function() {
            const rating = this.dataset.rating;
            highlightStars(rating);
        });

        star.addEventListener('mouseout', function() {
            const currentRating = document.getElementById('rating-value').value;
            highlightStars(currentRating);
        });
    });

    const submitBtn = document.getElementById('submit-comentario');
    if (submitBtn) {
        submitBtn.removeEventListener('click', submitComentario);
        submitBtn.addEventListener('click', submitComentario);
    }
}

function verificarUsuario() {
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;

    console.log(username, password);    

fetch('../configBD/login.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'username=' + encodeURIComponent(username) + '&password=' + encodeURIComponent(password)
})
.then(response => response.text()) // Obtener como texto primero
.then(text => {
    console.log("Respuesta del servidor:", text); // Depurar respuesta
    return JSON.parse(text); // Intentar convertir a JSON
})
.then(data => {
    console.log(data);
    console.log(data.contra + " " + data.contraEnviada);

    if (data.status === 'ok') {
        window.location.href = 'index.php';
    } else {
        alert('Usuario o contraseña incorrectos');
    }
})
.catch(error => console.error('Error en la solicitud:', error));

}

function highlightStars(rating) {
    const stars = document.querySelectorAll('.stars i');
    stars.forEach(star => {
        const value = star.dataset.rating;
        if (value <= rating) {
            star.classList.add('active');
        } else {
            star.classList.remove('active');
        }
    });
}

function updateStars(rating) {
    document.getElementById('rating-value').value = rating;
    highlightStars(rating);
}

function submitComentario() {
    const comentario = document.getElementById('comentario-texto').value;
    const rating = document.getElementById('rating-value').value;

    if (!comentario || rating === '0') {
        alert('Por favor, escribe un comentario y selecciona una calificación');
        return;
    }

    fetch('../configBD/insertComentario.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `montana_id=${currentMontanaId}&comentario=${encodeURIComponent(comentario)}&calificacion=${rating}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('comentario-texto').value = '';
            document.getElementById('rating-value').value = '0';
            updateStars(0);
            cargarComentarios(currentMontanaId);
        } else {
            alert(data.error || 'Error al enviar el comentario');
        }
    })
    .catch(error => console.error('Error:', error));
}

function cargarComentarios(id) {
    if (!id) return;
    
    currentMontanaId = id;
    const comentariosContainer = document.getElementById('comentarios');
    if (!comentariosContainer) return;

    console.log('Cargando comentarios para montaña ID/Nombre:', id); // Debug

    fetch('../configBD/comentarios.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'searchText=' + encodeURIComponent(id)
    })
    .then(response => {
        console.log('Respuesta recibida:', response); // Debug
        return response.json();
    })
    .then(data => {
        console.log('Datos recibidos:', data); // Debug
        comentariosContainer.innerHTML = '';
        
        if (data && Array.isArray(data)) {
            if (data.length === 0) {
                // Mostrar mensaje cuando no hay comentarios
                const row = document.createElement('tr');
                row.innerHTML = '<td colspan="4" style="text-align: center; padding: 20px;">No hay comentarios aún. ¡Sé el primero en comentar!</td>';
                comentariosContainer.appendChild(row);
                return;
            }

            data.forEach(item => {
                if (!item.error) {
                    const row = document.createElement('tr');
                    const fecha = new Date(item.fecha);
                    const fechaFormateada = fecha.toLocaleDateString('es-ES', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    row.innerHTML = `
                        <td>${item.nombreUsuario}</td>
                        <td>${item.comentario}</td>
                        <td>${'★'.repeat(item.calificacion)}${'☆'.repeat(5-item.calificacion)}</td>
                        <td>${fechaFormateada}</td>
                    `;
                    comentariosContainer.appendChild(row);
                }
            });
        } else if (data.error) {
            comentariosContainer.innerHTML = `<tr><td colspan="4" style="text-align: center; color: #666;">${data.error}</td></tr>`;
        } else {
            comentariosContainer.innerHTML = '<tr><td colspan="4" style="text-align: center; color: #666;">No se encontraron comentarios</td></tr>';
        }
    })
    .catch(error => {
        console.error('Error al cargar comentarios:', error);
        comentariosContainer.innerHTML = '<tr><td colspan="4" style="text-align: center; color: red;">Error al cargar los comentarios</td></tr>';
    });
}