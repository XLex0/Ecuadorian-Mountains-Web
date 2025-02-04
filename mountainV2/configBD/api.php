<?php
require 'configBD.php';
require 'auth.php'; // Archivo para manejar autenticación

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Manejar solicitudes OPTIONS (CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Obtener el endpoint
$endpoint = $_GET['endpoint'] ?? '';

// Manejador de peticiones GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    switch ($endpoint) {
        case 'montanas':
            obtenerMontanas($conn);
            break;
        case 'montana':
            isset($_GET['id']) ? obtenerMontana($conn, $_GET['id']) : errorRespuesta("ID de montaña requerido");
            break;
        case 'rutas':
            isset($_GET['id']) ? obtenerRutas($conn, $_GET['id']) : errorRespuesta("ID de montaña requerido");
            break;
        case 'equipo':
            isset($_GET['id']) ? obtenerEquipo($conn, $_GET['id']) : errorRespuesta("ID de montaña requerido");
            break;
        case 'temporadas':
            isset($_GET['id']) ? obtenerTemporadas($conn, $_GET['id']) : errorRespuesta("ID de montaña requerido");
            break;
        case 'refugios':
            isset($_GET['id']) ? obtenerRefugios($conn, $_GET['id']) : errorRespuesta("ID de montaña requerido");
            break;
        case 'comentarios':
            isset($_GET['montana_id']) ? obtenerComentarios($conn, $_GET['montana_id']) : errorRespuesta("ID de montaña requerido");
            break;
        case 'calificacion_promedio':
            isset($_GET['montana_id']) ? obtenerCalificacionPromedio($conn, $_GET['montana_id']) : errorRespuesta("ID de montaña requerido");
            break;
        default:
            errorRespuesta("Endpoint desconocido", 404);
    }
}

// Manejador de peticiones POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    switch ($endpoint) {
        case 'login':
            login($conn, $data);
            break;
        case 'register':
            register($conn, $data);
            break;
        case 'comentar':
            verificarAutenticacion() ? agregarComentario($conn, $data) : errorRespuesta("Usuario no autenticado", 401);
            break;
        default:
            errorRespuesta("Método no permitido en este endpoint", 405);
    }
}

// Funciones para obtener datos
function obtenerMontanas($conn) {
    $sql = "SELECT * FROM montanas";
    $result = $conn->query($sql);
    enviarRespuesta($result);
}

function obtenerMontana($conn, $id) {
    ejecutarConsulta($conn, "SELECT * FROM montanas WHERE id = ?", "i", [$id]);
}

function obtenerRutas($conn, $id) {
    ejecutarConsulta($conn, "SELECT * FROM rutas WHERE montana_id = ?", "i", [$id]);
}

function obtenerEquipo($conn, $id) {
    ejecutarConsulta($conn, "SELECT * FROM equipo WHERE montana_id = ?", "i", [$id]);
}

function obtenerTemporadas($conn, $id) {
    ejecutarConsulta($conn, "SELECT * FROM temporadas WHERE montana_id = ?", "i", [$id]);
}

function obtenerRefugios($conn, $id) {
    ejecutarConsulta($conn, "SELECT * FROM refugios WHERE montana_id = ?", "i", [$id]);
}

function obtenerComentarios($conn, $montana_id) {
    ejecutarConsulta($conn, "SELECT * FROM comentarios WHERE montana_id = ?", "i", [$montana_id]);
}

function agregarComentario($conn, $data) {
    $montana_id = $data['montana_id'] ?? 0;
    $usuario_id = $_SESSION['user_id'] ?? 0;
    $comentario = $data['comentario'] ?? '';
    $calificacion = $data['calificacion'] ?? 0;

    if ($usuario_id == 0) {
        errorRespuesta("Usuario no autenticado", 401);
    }

    $sql = "INSERT INTO comentarios (montana_id, usuario_id, comentario, calificacion) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisi", $montana_id, $usuario_id, $comentario, $calificacion);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        errorRespuesta("Error al insertar comentario: " . $conn->error, 500);
    }
}

function obtenerCalificacionPromedio($conn, $montana_id) {
    ejecutarConsulta($conn, "SELECT AVG(calificacion) as promedio FROM comentarios WHERE montana_id = ?", "i", [$montana_id]);
}

// **Funciones auxiliares para simplificar el código**
function ejecutarConsulta($conn, $sql, $types, $params) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    enviarRespuesta($result);
}

function enviarRespuesta($result) {
    if ($result === false) {
        errorRespuesta("Error en la consulta: " . $GLOBALS['conn']->error, 500);
    } elseif ($result->num_rows > 0) {
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    } else {
        errorRespuesta("No hay datos disponibles", 404);
    }
}

function errorRespuesta($mensaje, $codigo = 400) {
    http_response_code($codigo);
    echo json_encode(["error" => $mensaje]);
    exit();
}
?>
