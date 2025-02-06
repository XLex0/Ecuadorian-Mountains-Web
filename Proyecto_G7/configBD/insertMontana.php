<?php
include 'configBD.php';

// Obtener los datos del POST
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$longitud = $_POST['longitud'];
$latitud = $_POST['latitud'];
$ubicacion = $_POST['ubicacion'];
$altura = $_POST['altura'];
$tipo = $_POST['tipo'];
$urlImagenPrincipal = $_POST['urlImagenPrincipal'];
$mapsEmbeded = $_POST['mapsEmbeded'];

// Insertar en la base de datos
$sql = "INSERT INTO montanas (nombre, descripcion, longitud, latitud, ubicacion, altura, tipo, urlImagenPrincipal, mapsEmbeded)
        VALUES ('$nombre', '$descripcion', '$longitud', '$latitud', '$ubicacion', '$altura', '$tipo', '$urlImagenPrincipal', '$mapsEmbeded')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);  // Mostrar error de MySQL
}

$conn->close();
?>
