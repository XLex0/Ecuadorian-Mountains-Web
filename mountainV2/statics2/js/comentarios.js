function agregarComentario(id) {
    const comentarioTexto = document.getElementById("nuevo-comentario").value;
    const calificacion = document.getElementById("calificacion").value;
    const usuario = localStorage.getItem("username");

    if (!usuario) {
        alert("Debes iniciar sesión para comentar.");
        return;
    }

    fetch("../configBD/comentarios.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `searchText=${encodeURIComponent(id)}&comentario=${encodeURIComponent(comentarioTexto)}&usuario=${encodeURIComponent(usuario)}&calificacion=${encodeURIComponent(calificacion)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Comentario agregado correctamente.");
            cargarComentarios(id);
        } else {
            alert("Error al agregar el comentario.");
        }
    })
    .catch(error => console.error("Error:", error));
}
