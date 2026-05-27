<?php
require_once __DIR__ . '/../../config/cors.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../helpers/response.php';
require_once __DIR__ . '/../../helpers/auth.php';

setCors();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : null;
        $sql = 'SELECT id, title, excerpt, category, image_path, published_at FROM news ORDER BY published_at DESC';
        if ($limit) {
            $sql .= ' LIMIT ' . $limit;
        }
        $rows = getDb()->query($sql)->fetchAll();
        jsonOk($rows);

    case 'POST':
        requireAuth();
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        $title   = trim($body['title'] ?? '');
        $excerpt = trim($body['excerpt'] ?? '');
        $content = trim($body['content'] ?? '');
        $category   = trim($body['category'] ?? 'Ogólne');
        $image_path = trim($body['image_path'] ?? '');
        if ($title === '') jsonError(400, 'Tytuł jest wymagany');
        $stmt = getDb()->prepare(
            'INSERT INTO news (title, excerpt, content, category, image_path) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$title, $excerpt, $content, $category, $image_path ?: null]);
        $id = (int)getDb()->lastInsertId();
        jsonOk(['id' => $id, 'title' => $title], 201);

    default:
        jsonError(405, 'Metoda niedozwolona');
}
