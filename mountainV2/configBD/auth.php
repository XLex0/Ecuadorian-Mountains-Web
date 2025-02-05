<?php
session_start();
require 'configBD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (empty($fullname) || empty($email) || empty($username) || empty($password)) {
        die("Todos los campos son obligatorios.");
    }

    // Verificar si el usuario o email ya existen
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header('Content-Type: application/json');
        echo json_encode(["status" => "error", "message" => "El usuario o el correo ya están registrados"]);
        exit;
    }

    // Hashear la contraseña
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Insertar usuario en la base de datos
    $stmt = $conn->prepare("INSERT INTO usuarios (username, email, password_hash) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password_hash);
    
    if ($stmt->execute()) {
        header("Location: ../templates2/login.html");
        exit();
    } else {
        die("Error al registrar usuario.");
    }
    $stmt->close();
}

// Función para verificar autenticación con token
function verificarAutenticacion() {
    $headers = getallheaders();
    if (isset($headers['Authorization'])) {
        $token = str_replace('Bearer ', '', $headers['Authorization']);
        return verificarToken($token);
    }
    return false;
}

// Función para verificar token
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

// Función para obtener el ID del usuario desde el token
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

// Función para el inicio de sesión
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

// Función para cerrar sesión
function logout() {
    session_destroy();
    echo json_encode(["success" => true]);
}

?>
