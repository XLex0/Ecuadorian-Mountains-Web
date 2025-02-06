<?php
include 'configBD.php';

// Verificar si los datos POST están presentes
if (isset($_POST['extraer']) && isset($_POST['searchText'])) {
    $extraer = $_POST['extraer'];
    $id = $conn->real_escape_string($_POST['searchText']); // Sanitizar entrada

    $data = array();

    if ($extraer == 'descripcion') {
        // Primera consulta: buscar por ID
        $sql = "SELECT nombre, longitud, latitud, ubicacion, altura, tipo, descripcion, urlImagenPrincipal, mapsEmbeded 
                FROM montanas 
                WHERE id = '$id'";
    } elseif ($extraer == 'mountain') {
        $sql = "SELECT longitud, latitud, ubicacion, altura, tipo 
                FROM montanas  
                WHERE id = '$id'";
    } elseif ($extraer == 'all') {
        $sql = "SELECT id, nombre, longitud, latitud, ubicacion, altura, tipo, urlImagenPrincipal 
                FROM montanas";
    } else {
        echo json_encode(array("error" => "Parámetro 'extraer' no válido"));
        exit;
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    } elseif ($extraer == 'descripcion') {
        // Si no se encuentra por ID, buscar por nombre
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
    } else {
        $data[] = array("error" => "No se encontraron resultados");
    }

    echo json_encode($data);
} else {
    echo json_encode(array("error" => "Datos POST no recibidos"));
}

$conn->close();
