<?php
session_start();
require 'configBD.php';

// Registro de usuario
function register($conn, $data) {
    if (!isset($data['username']) || !isset($data['password'])) {
        echo json_encode(["error" => "Faltan datos"]);
        return;
    }

    $username = $data['username'];
    $password = password_hash($data['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (username, password_hash) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Error al registrar usuario"]);
    }
}

// Inicio de sesión
function login($conn, $data) {
    if (!isset($data['username']) || !isset($data['password'])) {
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

            $stmt = $conn->prepare("INSERT INTO sesiones (usuario_id, token, fecha_expiracion) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $row['id'], $token, $expiracion);
            $stmt->execute();

            echo json_encode(["success" => true, "token" => $token]);
            return;
        }
    }
    echo json_encode(["error" => "Credenciales inválidas"]);
}

// Cerrar sesión
function logout($conn, $token) {
    $stmt = $conn->prepare("DELETE FROM sesiones WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    echo json_encode(["success" => true]);
}

// Verificar si el usuario está autenticado
function verificarAutenticacion($conn, $token) {
    $stmt = $conn->prepare("SELECT usuario_id FROM sesiones WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row['usuario_id'];
    }
    return false;
}

// Eliminar usuario
function deleteUser($conn, $token) {
    $user_id = verificarAutenticacion($conn, $token);
    if ($user_id) {
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // Eliminar sesión del usuario
        $stmt = $conn->prepare("DELETE FROM sesiones WHERE usuario_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Usuario no autenticado"]);
    }
}
?>
