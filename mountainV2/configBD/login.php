<?php
session_start();
include 'configBD.php';

header('Content-Type: application/json');

// Habilitar reporte de errores y capturar salida inesperada
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start(); // Captura cualquier salida antes del JSON

$response = ["status" => "error"];

// Verificar si los datos fueron enviados correctamente
if (!isset($_POST['username']) || !isset($_POST['password'])) {
    ob_end_clean(); // Limpiar cualquier salida inesperada
    echo json_encode(["status" => "falta valores"]);
    exit;
}

$username = trim($_POST['username']);
$password = trim($_POST['password']);

// Verificar usuario en la base de datos
$sql = "SELECT username, email, password_hash FROM usuarios WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $password_hashed = hash('sha256', $password);
if ($password_hashed === $row['password_hash']) {
        $_SESSION['usuario'] = $username;
        $response["status"] = "ok";
    } else {
        $response["status"] = "usuario incorrecto";
    }
} else {
    $response["status"] = "no encontrado";
}

// Limpiar y enviar JSON correctamente
ob_end_clean();
echo json_encode($response);
exit;
?>
