<?php
session_start();
require 'configBD.php';

function verificarAutenticacion() {
    $headers = getallheaders();
    if (isset($headers['Authorization'])) {
        $token = str_replace('Bearer ', '', $headers['Authorization']);
        return verificarToken($token);
    }
    return false;
}

function verificarToken($token) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT usuario_id 
        FROM sesiones 
        WHERE token = ? AND fecha_expiracion > NOW()
    ");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    return $stmt->get_result()->num_rows > 0;
}

function obtenerUsuarioIdDesdeToken() {
    global $conn;
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
        return null;
    }

    $token = str_replace('Bearer ', '', $headers['Authorization']);
    $stmt = $conn->prepare("SELECT usuario_id FROM sesiones WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        return $row['usuario_id'];
    }
    
    return null;
}

function login($conn, $data) {
    if (!isset($data['username']) || !isset($data['password'])) {
        http_response_code(400);
        echo json_encode(["error" => "Faltan credenciales"]);
        return;
    }

    $stmt = $conn->prepare("SELECT id, password_hash FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $data['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        if (password_verify($data['password'], $row['password_hash'])) {
            $token = bin2hex(random_bytes(32));
            $expiracion = date('Y-m-d H:i:s', strtotime('+24 hours'));
            
            $stmt = $conn->prepare("
                INSERT INTO sesiones (usuario_id, token, fecha_expiracion) 
                VALUES (?, ?, ?)
            ");
            $stmt->bind_param("iss", $row['id'], $token, $expiracion);
            $stmt->execute();
            
            echo json_encode([
                "success" => true,
                "token" => $token
            ]);
            return;
        }
    }

    http_response_code(401);
    echo json_encode(["error" => "Credenciales inválidas"]);
}

function logout() {
    session_destroy();
    echo json_encode(["success" => true]);
}

// **Evitar el error de "unexpected token 'else'"**
function usuarioAutenticado() {
    return isset($_SESSION['user_id']);
}

?>
