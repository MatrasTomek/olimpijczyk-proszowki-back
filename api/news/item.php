<?php
require_once __DIR__ . '/../../config/cors.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../helpers/response.php';
require_once __DIR__ . '/../../helpers/auth.php';

setCors();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) jsonError(400, 'Brak id');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $row = getDb()->prepare('SELECT * FROM news WHERE id = ?');
        $row->execute([$id]);
        $item = $row->fetch();
        if (!$item) jsonError(404, 'Nie znaleziono');
        jsonOk($item);

    case 'PUT':
        requireAuth();
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        $title      = trim($body['title'] ?? '');
        $excerpt    = trim($body['excerpt'] ?? '');
        $content    = trim($body['content'] ?? '');
        $category   = trim($body['category'] ?? 'Ogólne');
        $image_path = trim($body['image_path'] ?? '');
        if ($title === '') jsonError(400, 'Tytuł jest wymagany');
        $stmt = getDb()->prepare(
            'UPDATE news SET title=?, excerpt=?, content=?, category=?, image_path=? WHERE id=?'
        );
        $stmt->execute([$title, $excerpt, $content, $category, $image_path ?: null, $id]);
        jsonOk(['updated' => true]);

    case 'DELETE':
        requireAuth();
        $stmt = getDb()->prepare('DELETE FROM news WHERE id = ?');
        $stmt->execute([$id]);
        jsonOk(['deleted' => true]);

    default:
        jsonError(405, 'Metoda niedozwolona');
}
