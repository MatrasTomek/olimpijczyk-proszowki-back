<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../middleware/jwt.php';
require_once __DIR__ . '/response.php';

function requireAuth(): array {
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if (!str_starts_with($header, 'Bearer ')) {
        jsonError(401, 'Brak tokena autoryzacyjnego');
    }
    $token = substr($header, 7);
    $payload = JWT::decode($token, JWT_SECRET);
    if ($payload === null) {
        jsonError(401, 'Token nieprawidłowy lub wygasł');
    }
    return $payload;
}
