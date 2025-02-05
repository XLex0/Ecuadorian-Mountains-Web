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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        ob_end_clean();
        echo json_encode(["status" => "faltan valores"]);
        exit;
    }

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (empty($username) || empty($password)) {
        ob_end_clean();
        echo json_encode(["status" => "campos vacíos"]);
        exit;
    }

    // Conexión a la base de datos
    if (!$conn) {
        ob_end_clean();
        echo json_encode(["status" => "error de conexión"]);
        exit;
    }

    // Verificar si el usuario existe
    $stmt = $conn->prepare("SELECT id, username, password_hash FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row["password_hash"])) {
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["username"] = $username;

            $response["status"] = "ok";
            $response["redirect"] = "../templates2/index.php";
        } else {
            $response["status"] = "contraseña incorrecta";
        }
    } else {
        $response["status"] = "usuario no encontrado";
    }

    $stmt->close();
    $conn->close();
} else {
    $response["status"] = "método no permitido";
}    
// Limpiar y enviar JSON correctamente
ob_end_clean();
echo json_encode($response);
exit;
?>