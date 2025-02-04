<?php
require 'configBD.php';
require 'auth.php'; // Archivo para manejar autenticación

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Manejar solicitudes OPTIONS (CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Obtener el endpoint y datos
$endpoint = $_GET['endpoint'] ?? '';
$data = json_decode(file_get_contents('php://input'), true);

// **Manejador de solicitudes**
try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if ($endpoint === 'montanas') {
            obtenerMontanas($conn);
        } elseif ($endpoint === 'comentarios' && isset($_GET['montana_id'])) {
            obtenerComentarios($conn, $_GET['montana_id']);
        } else {
            errorRespuesta("Endpoint desconocido o falta parámetro", 404);
        }
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
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        switch ($endpoint) {
            case 'login':
                login($conn, $data);
                break;
            case 'register':
                register($conn, $data);
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
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && $endpoint === 'delete_user') {
        $headers = getallheaders();
        $token = $headers['Authorization'] ?? '';
        deleteUser($conn, $token);
    } else {
        errorRespuesta("Método HTTP no válido", 405);
    }
} catch (Exception $e) {
    errorRespuesta("Error interno del servidor: " . $e->getMessage(), 500);
}

// **Funciones para obtener datos**
function obtenerMontanas($conn) {
    ejecutarConsulta($conn, "SELECT * FROM montanas");
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
    ejecutarConsulta($conn, "SELECT c.comentario, u.username AS nombreUsuario, c.calificacion, c.fecha_comentario 
        FROM comentarios c 
        JOIN usuarios u ON c.usuario_id = u.id 
        WHERE c.montana_id = ?", "i", [$montana_id]);
}

function obtenerCalificacionPromedio($conn, $montana_id) {
    ejecutarConsulta($conn, "SELECT AVG(calificacion) AS promedio FROM comentarios WHERE montana_id = ?", "i", [$montana_id]);
}

// **Funciones para manejar comentarios**
function agregarComentario($conn, $usuario_id, $data) {
    $montana_id = $data['montana_id'] ?? 0;
    $comentario = $data['comentario'] ?? '';
    $calificacion = $data['calificacion'] ?? 0;

    if ($montana_id == 0 || empty($comentario)) {
        errorRespuesta("Datos incompletos", 400);
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

// **Funciones auxiliares para simplificar el código**
function ejecutarConsulta($conn, $sql, $types = "", $params = []) {
    $stmt = $conn->prepare($sql);
    if (!empty($types) && !empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
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
