<?php
include 'configBD.php';

// Obtener los datos del POST
$nombre = $_POST['nombre'];

// Eliminar de la base de datos
$sql = "DELETE FROM montanas WHERE nombre = '$nombre'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);  // Mostrar error de MySQL
}

$conn->close();
?>
