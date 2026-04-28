<?php
// procesar-contacto.php
require_once 'config.php';

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Manejar peticiones OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Obtener y validar los datos
$nombre = trim($_POST['nombre'] ?? '');
$email = trim($_POST['email'] ?? '');
$asunto = trim($_POST['asunto'] ?? '');
$mensaje = trim($_POST['mensaje'] ?? '');

// Validaciones
$errores = [];

if (empty($nombre)) {
    $errores[] = 'El nombre es requerido';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = 'El email no es válido';
}

if (empty($asunto)) {
    $errores[] = 'El asunto es requerido';
}

if (empty($mensaje)) {
    $errores[] = 'El mensaje es requerido';
}

// Si hay errores, responder con error
if (!empty($errores)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Error en los datos', 'errores' => $errores]);
    exit;
}

// Obtener IP
$ip = $_SERVER['REMOTE_ADDR'];

// Preparar y ejecutar consulta SQL usando prepared statements (seguro contra SQL injection)
$stmt = $conexion->prepare("INSERT INTO contactos (nombre, email, asunto, mensaje, ip) VALUES (?, ?, ?, ?, ?)");

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos']);
    exit;
}

// Vincular parámetros: s = string
$stmt->bind_param("sssss", $nombre, $email, $asunto, $mensaje, $ip);

// Ejecutar la consulta
if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Tu mensaje ha sido enviado correctamente. Te contactaremos pronto.',
        'id' => $stmt->insert_id
    ]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al guardar el mensaje: ' . $stmt->error]);
}

$stmt->close();
$conexion->close();
?>

