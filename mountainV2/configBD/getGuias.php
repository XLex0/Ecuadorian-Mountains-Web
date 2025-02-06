<?php
include 'configBD.php';

header('Content-Type: application/json');

$sql = "SELECT g.*, m.nombre as montana_nombre 
        FROM guias_operadores g 
        LEFT JOIN montanas m ON g.montana_id = m.id";

$result = $conn->query($sql);
$data = array();

if ($result) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>