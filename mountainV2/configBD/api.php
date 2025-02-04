<?php
require 'configBD.php';
require 'auth.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Manejo de solicitudes OPTIONS (CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$endpoint = $_GET['endpoint'] ?? '';
$data = json_decode(file_get_contents('php://input'), true);

// Manejador de peticiones GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    switch ($endpoint) {
        case 'montanas':
            obtenerMontanas($conn);
            break;
        case 'montana':
            isset($_GET['id']) ? obtenerMontana($conn, $_GET['id']) : errorRespuesta("ID de montaña requerido");
            break;
        case 'comentarios':
            isset($_GET['montana_id']) ? obtenerComentarios($conn, $_GET['montana_id']) : errorRespuesta("ID de montaña requerido");
            break;
        default:
            errorRespuesta("Endpoint desconocido", 404);
    }
}

// Manejador de peticiones POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($endpoint) {
        case 'login':
            login($conn, $data);
            break;
        case 'register':
            register($conn, $data);
            break;
        case 'logout':
            $headers = getallheaders();
            $token = $headers['Authorization'] ?? '';
            logout($conn, $token);
            break;
        case 'comentar':
            $headers = getallheaders();
            $token = $headers['Authorization'] ?? '';
            $usuario_id = verificarAutenticacion($conn, $token);
            if ($usuario_id) {
                agregarComentario($conn, $usuario_id, $data);
            } else {
                errorRespuesta("Usuario no autenticado", 401);
            }
            break;
        default:
            errorRespuesta("Método no permitido en este endpoint", 405);
    }
}

// Manejador de peticiones DELETE
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && $endpoint === 'delete_user') {
    $headers = getallheaders();
    $token = $headers['Authorization'] ?? '';
    deleteUser($conn, $token);
}

// Función para agregar comentarios autenticados
function agregarComentario($conn, $usuario_id, $data) {
    $montana_id = $data['montana_id'] ?? 0;
    $comentario = $data['comentario'] ?? '';
    $calificacion = $data['calificacion'] ?? 0;

    $sql = "INSERT INTO comentarios (montana_id, usuario_id, comentario, calificacion) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisi", $montana_id, $usuario_id, $comentario, $calificacion);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        errorRespuesta("Error al insertar comentario: " . $conn->error, 500);
    }
}

// Función para obtener montañas
function obtenerMontanas($conn) {
    $sql = "SELECT * FROM montanas";
    enviarRespuesta($conn->query($sql));
}

// Función para obtener comentarios
function obtenerComentarios($conn, $montana_id) {
    ejecutarConsulta($conn, "SELECT * FROM comentarios WHERE montana_id = ?", "i", [$montana_id]);
}

// Función auxiliar para ejecutar consultas
function ejecutarConsulta($conn, $sql, $types, $params) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    enviarRespuesta($stmt->get_result());
}

// Función para enviar respuesta JSON
function enviarRespuesta($result) {
    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    } else {
        errorRespuesta("No hay datos disponibles", 404);
    }
}

// Función para enviar errores
function errorRespuesta($mensaje, $codigo = 400) {
    http_response_code($codigo);
    echo json_encode(["error" => $mensaje]);
    exit();
}
?>
