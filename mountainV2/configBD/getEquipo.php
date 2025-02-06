<?php
include 'configBD.php';

header('Content-Type: application/json');

$sql = "SELECT e.*, m.nombre as montana_nombre 
        FROM equipo e 
        LEFT JOIN montanas m ON e.montana_id = m.id
        ORDER BY m.nombre, e.obligatorio DESC";

$result = $conn->query($sql);
$data = array();

if ($result) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>