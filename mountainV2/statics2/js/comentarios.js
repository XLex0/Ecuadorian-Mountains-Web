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

let currentMontanaId = null;

function initializeRatingSystem() {
    const stars = document.querySelectorAll('.stars i');
    if (!stars.length) return; // Si no hay estrellas, no hacemos nada
    
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
        submitBtn.removeEventListener('click', submitComentario); // Removemos listener previo si existe
        submitBtn.addEventListener('click', submitComentario);
    }
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
    if (!id) return; // Si no hay ID, no hacemos nada
    
    currentMontanaId = id;
    const comentariosContainer = document.getElementById('comentarios');
    if (!comentariosContainer) return;

    fetch('../configBD/comentarios.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'searchText=' + encodeURIComponent(id)
    })
    .then(response => response.json())
    .then(data => {
        comentariosContainer.innerHTML = '';
        
        if (data && Array.isArray(data)) {
            data.forEach(item => {
                if (!item.error) {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.nombreUsuario || 'Anónimo'}</td>
                        <td>${item.comentario}</td>
                        <td>${'★'.repeat(item.calificacion)}${'☆'.repeat(5-item.calificacion)}</td>
                        <td>${new Date(item.fecha).toLocaleDateString()}</td>
                    `;
                    comentariosContainer.appendChild(row);
                }
            });
        }
    })
    .catch(error => console.error('Error:', error));
}