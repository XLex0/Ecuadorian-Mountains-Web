document.addEventListener("DOMContentLoaded", function () {
    fetch("../configBD/api.php?endpoint=rutas")
        .then(response => response.json())
        .then(data => {
            const rutasTable = document.getElementById("rutas-list");

            data.forEach(ruta => {
                const row = document.createElement("tr");

                const cell1 = document.createElement("td");
                cell1.textContent = ruta.nombre;
                row.appendChild(cell1);

                const cell2 = document.createElement("td");
                cell2.textContent = ruta.dificultad;
                row.appendChild(cell2);

                const cell3 = document.createElement("td");
                cell3.textContent = ruta.duracion + " horas";
                row.appendChild(cell3);

                const cell4 = document.createElement("td");
                cell4.textContent = ruta.distancia + " km";
                row.appendChild(cell4);

                rutasTable.appendChild(row);
            });
        })
        .catch(error => console.error("Error al cargar rutas:", error));
});
