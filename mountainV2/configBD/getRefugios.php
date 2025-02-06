<?php
include 'configBD.php';

header('Content-Type: application/json');

$sql = "SELECT r.*, m.nombre as montana_nombre 
        FROM refugios r 
        LEFT JOIN montanas m ON r.montana_id = m.id
        ORDER BY m.nombre";

$result = $conn->query($sql);
$data = array();

if ($result) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>