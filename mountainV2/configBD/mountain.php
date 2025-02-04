<?php
require 'configBD.php';

$extraer = $_POST['extraer'] ?? '';
$id = $_POST['searchText'] ?? '';

if (empty($id)) {
    echo json_encode(["error" => "ID de montaña no proporcionado"]);
    exit();
}

$id = intval($id);

$sql = "";
if ($extraer == 'descripcion') {
    $sql = "SELECT nombre, longitud, latitud, ubicacion, altura, tipo, descripcion, urlImagenPrincipal FROM montanas WHERE id = ?";
} else if ($extraer == 'mountain') {
    $sql = "SELECT longitud, latitud, ubicacion, altura, tipo FROM montanas WHERE id = ?";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

if (empty($data)) {
    echo json_encode(["error" => "No se encontraron datos para el ID: " . $id]);
} else {
    echo json_encode($data);
}
?>
