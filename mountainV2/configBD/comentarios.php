<?php
include 'configBD.php';

// Verificar si los datos POST están presentes
if (isset($_POST['searchText'])) {
    // Evitar inyección SQL
    $id = $conn->real_escape_string($_POST['searchText']);

    // Primera consulta: buscar comentarios por ID de montaña
    $sql = "SELECT c.comentario as comentario, u.username as nombreUsuario,
            c.calificacion as calificacion, c.fecha_comentario as fecha
            FROM montanas m
            LEFT JOIN comentarios c ON m.id = c.montana_id
            LEFT JOIN usuarios u ON c.usuario_id = u.id
            WHERE m.id = '$id'"; 

    $result = $conn->query($sql);
    $data = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    } else {
        // Segunda consulta: buscar comentarios por nombre de montaña
        $sql = "SELECT c.comentario as comentario, u.username as nombreUsuario,
                c.calificacion as calificacion, c.fecha_comentario as fecha
                FROM montanas m
                LEFT JOIN comentarios c ON m.id = c.montana_id
                LEFT JOIN usuarios u ON c.usuario_id = u.id
                WHERE m.nombre LIKE '$id%'"; 

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data[] = array("error" => "No se encontraron resultados");
        }
    }

    echo json_encode($data);
} else {
    echo json_encode(array("error" => "Datos POST no recibidos"));
}

$conn->close();
?>