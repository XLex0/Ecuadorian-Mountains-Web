<?php
session_start();
include 'configBD.php';

header('Content-Type: application/json');

$montana_id = $_POST['montana_id'];
$comentario = $_POST['comentario'];
$calificacion = $_POST['calificacion'];

if (!isset($_SESSION['user_id'])) {
    // Usuario anónimo
    $ip_address = $_SERVER['REMOTE_ADDR'];
    
    // Verificar si ya ha comentado
    $check_ip = "SELECT COUNT(*) as count FROM comentarios_anonimos WHERE ip_address = ? AND montana_id = ?";
    $stmt = $conn->prepare($check_ip);
    $stmt->bind_param("si", $ip_address, $montana_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['count'];
    
    if ($count > 0) {
        echo json_encode(['success' => false, 'error' => 'Ya has dejado un comentario anónimo para esta montaña']);
        exit;
    }

    // Insertar comentario anónimo
    $sql = "INSERT INTO comentarios (montana_id, comentario, calificacion, es_anonimo) 
            VALUES (?, ?, ?, 1)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $montana_id, $comentario, $calificacion);
    
    if ($stmt->execute()) {
        // Registrar IP
        $comentario_id = $stmt->insert_id;
        $sql_ip = "INSERT INTO comentarios_anonimos (comentario_id, montana_id, ip_address) VALUES (?, ?, ?)";
        $stmt_ip = $conn->prepare($sql_ip);
        $stmt_ip->bind_param("iis", $comentario_id, $montana_id, $ip_address);
        $stmt_ip->execute();
        
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
} else {
    // Usuario registrado
    $usuario_id = $_SESSION['user_id'];
    
    $sql = "INSERT INTO comentarios (montana_id, usuario_id, comentario, calificacion) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisi", $montana_id, $usuario_id, $comentario, $calificacion);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}

$conn->close();
?>