<?php
include 'configBD.php';

header('Content-Type: application/json');

$sql = "SELECT t.*, m.nombre as montana_nombre 
        FROM temporadas t 
        LEFT JOIN montanas m ON t.montana_id = m.id
        ORDER BY m.nombre, t.mes_inicio";

$result = $conn->query($sql);
$data = array();

if ($result) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>