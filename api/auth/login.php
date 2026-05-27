<?php
require_once __DIR__ . '/../../config/cors.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../middleware/jwt.php';

setCors();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$body     = json_decode(file_get_contents('php://input'), true);
$username = trim($body['username'] ?? '');
$password = $body['password'] ?? '';

if ($username === '' || $password === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Login i hasło są wymagane']);
    exit;
}

try {
    $stmt = getDb()->prepare('SELECT id, password_hash FROM users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch();
} catch (PDOException) {
    http_response_code(500);
    echo json_encode(['error' => 'Błąd bazy danych']);
    exit;
}

if (!$user || !password_verify($password, $user['password_hash'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Nieprawidłowy login lub hasło']);
    exit;
}

$token = JWT::encode([
    'sub'      => $user['id'],
    'username' => $username,
    'iat'      => time(),
    'exp'      => time() + JWT_EXPIRY,
], JWT_SECRET);

echo json_encode(['token' => $token]);
