function cargarComentarios(id){
    searchText = id;
    fetch('../configBD/comentarios.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'searchText=' + encodeURIComponent(searchText)
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
    // Iterar y añadir elementos
    data.forEach(item => {
        const row = document.createElement('tr');

        const cell1 = document.createElement('td');
        cell1.textContent = item.nombreUsuario;
        row.appendChild(cell1);

        const cell2 = document.createElement('td');
        cell2.textContent = item.comentario;
        row.appendChild(cell2);


        const cell3 = document.createElement('td');
        cell3.textContent = item.calificacion;
        row.appendChild(cell3);


        const cell4 = document.createElement('td');
        cell4.textContent = item.fecha;
        row.appendChild(cell4);

        document.getElementById('comentarios').appendChild(row);

        
        });
    })
    .catch(error => console.error('Error:', error));


};


document.addEventListener("DOMContentLoaded", function() {
    const formLogin = document.getElementById('formLogin');

    if (formLogin) {
        formLogin.addEventListener('submit', function(event) {
            event.preventDefault(); // Evita la recarga
            verificarUsuario();
        });
    }
});

function verificarUsuario() {
    var username = document.getElementById('username');
    var password = document.getElementById('password');

    fetch('../configBD/login.php', {
        method: 'POST',
        
        body: 'username=' + encodeURIComponent(username.value) + '&password=' + encodeURIComponent(password.value)
    })
    .then(response => response.json())  // Convertir la respuesta a JSON
    .then(isPasswordCorrect => {  
        console.log('Respuesta del servidor:', isPasswordCorrect);

        if (isPasswordCorrect) {
            console.log('Login exitoso');
            alert('Inicio de sesión exitoso');
            // Guardar usuario en localStorage o redirigir a otra página
            localStorage.setItem("user", username.value);
            window.location.href = "index.html"; // Redirige al inicio
        } else {
            console.error('Login fallido');
            alert('Usuario o contraseña incorrectos');
        }
    })
    .catch(error => console.error('Error en la solicitud:', error));
}