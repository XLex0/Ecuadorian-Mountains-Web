<?php
session_start();
include 'configBD.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'No has iniciado sesión']);
    exit;
}

$montana_id = $_POST['montana_id'];
$comentario = $_POST['comentario'];
$calificacion = $_POST['calificacion'];
$usuario_id = $_SESSION['user_id'];

$sql = "INSERT INTO comentarios (montana_id, usuario_id, comentario, calificacion, fecha_comentario) 
        VALUES (?, ?, ?, ?, NOW())";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iisi", $montana_id, $usuario_id, $comentario, $calificacion);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
}

$stmt->close();
$conn->close();
?>