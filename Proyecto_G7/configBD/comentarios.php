<?php
include 'configBD.php';

header('Content-Type: application/json');

if (isset($_POST['searchText'])) {
    // Si es un nombre, primero obtener el ID
    $searchText = $conn->real_escape_string($_POST['searchText']);
    
    // Primero intentar obtener el ID si se pasó un nombre
    if (!is_numeric($searchText)) {
        $sql = "SELECT id FROM montanas WHERE nombre LIKE ?";
        $stmt = $conn->prepare($sql);
        $searchPattern = "%$searchText%";
        $stmt->bind_param("s", $searchPattern);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $id = $row['id'];
        } else {
            echo json_encode(['error' => 'Montaña no encontrada']);
            exit;
        }
    } else {
        $id = $searchText;
    }
    
    // Establecer la zona horaria para Ecuador
    date_default_timezone_set('America/Guayaquil');
    
    // Obtener comentarios y convertir la fecha a la zona horaria local
    $sql = "SELECT 
                c.comentario, 
                CASE 
                    WHEN c.es_anonimo = 1 THEN 'Anónimo'
                    ELSE COALESCE(u.username, 'Usuario')
                END as nombreUsuario,
                c.calificacion, 
                CONVERT_TZ(c.fecha_comentario, @@session.time_zone, '-05:00') as fecha
            FROM comentarios c
            LEFT JOIN usuarios u ON c.usuario_id = u.id
            WHERE c.montana_id = ?
            ORDER BY c.fecha_comentario DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = array();

    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            'nombreUsuario' => $row['nombreUsuario'],
            'comentario' => $row['comentario'],
            'calificacion' => $row['calificacion'],
            'fecha' => $row['fecha']
        );
    }

    echo json_encode($data);
} else {
    echo json_encode(['error' => 'ID de montaña no recibido']);
}

$conn->close();
?>