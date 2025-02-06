<?php
session_start();
include 'configBD.php';

header('Content-Type: application/json');

$montana_id = $_POST['montana_id'];
if (!is_numeric($montana_id)) {
    $sql = "SELECT id FROM montanas WHERE nombre LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchPattern = "%$montana_id%";
    $stmt->bind_param("s", $searchPattern);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $montana_id = $row['id'];
    } else {
        echo json_encode(['success' => false, 'error' => 'Montaña no encontrada']);
        exit;
    }
}

$comentario = $_POST['comentario'];
$calificacion = $_POST['calificacion'];

try {
    $conn->begin_transaction();

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
            echo json_encode([
                'success' => false, 
                'error' => 'Ya has realizado un comentario anónimo para esta montaña. Solo se permite un comentario por IP.'
            ]);
            exit;
        }

        // Insertar comentario anónimo
        $sql = "INSERT INTO comentarios (montana_id, usuario_id, comentario, calificacion, es_anonimo) 
                VALUES (?, NULL, ?, ?, 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isi", $montana_id, $comentario, $calificacion);
        
        if ($stmt->execute()) {
            $comentario_id = $stmt->insert_id;
            
            // Registrar IP
            $sql_ip = "INSERT INTO comentarios_anonimos (comentario_id, montana_id, ip_address) 
                       VALUES (?, ?, ?)";
            $stmt_ip = $conn->prepare($sql_ip);
            $stmt_ip->bind_param("iis", $comentario_id, $montana_id, $ip_address);
            
            if ($stmt_ip->execute()) {
                $conn->commit();
                echo json_encode(['success' => true]);
            } else {
                throw new Exception('Error al registrar IP');
            }
        } else {
            throw new Exception('Error al insertar comentario');
        }
    } else {
        // Usuario registrado
        $usuario_id = $_SESSION['user_id'];
        
        $sql = "INSERT INTO comentarios (montana_id, usuario_id, comentario, calificacion, es_anonimo) 
                VALUES (?, ?, ?, ?, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisi", $montana_id, $usuario_id, $comentario, $calificacion);
        
        if ($stmt->execute()) {
            $conn->commit();
            echo json_encode(['success' => true]);
        } else {
            throw new Exception('Error al insertar comentario');
        }
    }
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        'success' => false, 
        'error' => $e->getMessage()
    ]);
} finally {
    $conn->close();
}
?>