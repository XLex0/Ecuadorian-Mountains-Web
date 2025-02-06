<?php
include 'configBD.php';

header('Content-Type: application/json');

if (isset($_POST['searchText'])) {
    $id = $conn->real_escape_string($_POST['searchText']);
    $is_anonymous = !isset($_SESSION['user_id']);
    
    // Para usuarios anónimos, verificar si ya han comentado usando la IP
    if ($is_anonymous) {
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $check_ip = "SELECT COUNT(*) as count FROM comentarios_anonimos WHERE ip_address = ? AND montana_id = ?";
        $stmt = $conn->prepare($check_ip);
        $stmt->bind_param("si", $ip_address, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result->fetch_assoc()['count'];
        
        if ($count > 0) {
            echo json_encode(['error' => 'Ya has dejado un comentario anónimo para esta montaña']);
            exit;
        }
    }

    // Obtener comentarios existentes
    $sql = "SELECT c.comentario, 
            COALESCE(u.username, 'Anónimo') as nombreUsuario,
            c.calificacion, 
            c.fecha_comentario as fecha
            FROM montanas m
            LEFT JOIN comentarios c ON m.id = c.montana_id
            LEFT JOIN usuarios u ON c.usuario_id = u.id
            WHERE m.id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = array();

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
} else {
    echo json_encode(['error' => 'Datos POST no recibidos']);
}

$conn->close();
?>