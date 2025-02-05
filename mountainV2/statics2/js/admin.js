document.addEventListener("DOMContentLoaded", function () {
    const initialAdmin = document.getElementById("initialAdmin");

    // Función para ocultar el contenedor cuando se hace clic en el botón de agregar
    function hideInitialAdminAdd() {
        initialAdmin.style.display = "none";
    }

    // Función para ocultar el contenedor cuando se hace clic en el botón de borrar
    function hideInitialAdminDelete() {
        initialAdmin.style.display = "none";
    }

    // Asegurarse de que los botones están presentes antes de asignar el evento
    const addButton = document.getElementById("add");
    const deleteButton = document.getElementById("delete");

    // Comprobar si los botones existen antes de asignarles eventos
    if (addButton) {
        addButton.addEventListener("click", hideInitialAdminAdd);
    } else {
        console.error("El botón con id 'add' no se encuentra.");
    }

    if (deleteButton) {
        deleteButton.addEventListener("click", hideInitialAdminDelete);
    } else {
        console.error("El botón con id 'delete' no se encuentra.");
    }
});
