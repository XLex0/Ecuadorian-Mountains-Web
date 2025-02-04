<?php
include 'configBD.php';

/*
SELECT c.comentario as comentario, u.username as nombreUsuario,
c.calificacion as calificacion
FROM montanas m
LEFT JOIN comentarios c ON m.id = c.montana_id
LEFT JOIN usuarios u ON c.usuario_id = u.id
WHERE m.id = 1;
*/


$id = $_POST['searchText'];


    $sql = "SELECT c.comentario as comentario, u.username as nombreUsuario,
            c.calificacion as calificacion, c.fecha_comentario as fecha
            FROM montanas m
            LEFT JOIN comentarios c ON m.id = c.montana_id
            LEFT JOIN usuarios u ON c.usuario_id = u.id
            WHERE m.id = $id;";

    $result = $conn->query($sql);
    $data = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    
    echo json_encode($data);



?>