<?php
include 'configBD.php';

// Verificar si los datos POST están presentes
if (isset($_POST['extraer']) && isset($_POST['searchText'])) {
    $id = $_POST['searchText'];

    // Sanitizar la entrada para evitar inyección SQL
    $id = $conn->real_escape_string($id);

    // Primera consulta: buscar por ID
    $sql = "SELECT nombre, longitud, latitud, ubicacion, altura, tipo, descripcion, urlImagenPrincipal, mapsEmbeded 
            FROM montanas 
            WHERE id = '$id'";

    $result = $conn->query($sql);
    $data = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    } else {
        // Segunda consulta: buscar por nombre si no se encontró por ID
        $sql = "SELECT nombre, longitud, latitud, ubicacion, altura, tipo, descripcion, urlImagenPrincipal, mapsEmbeded 
                FROM montanas 
                WHERE nombre LIKE '$id%'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data[] = array("error" => "No se encontraron resultados");
        }
    }

    echo json_encode($data);
} else {
    echo json_encode(array("error" => "Datos POST no recibidos"));
}

$conn->close();
?>