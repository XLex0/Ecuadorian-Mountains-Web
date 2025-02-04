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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $montana_id = $_POST["montana_id"];
    $comentario = $_POST["comentario"];
    $usuario = $_POST["usuario"];
    $calificacion = $_POST["calificacion"];

    $sql = "INSERT INTO comentarios (montana_id, usuario_id, comentario, calificacion) 
            VALUES (?, (SELECT id FROM usuarios WHERE username = ?), ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issi", $montana_id, $usuario, $comentario, $calificacion);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Error al agregar comentario"]);
    }
}



?>