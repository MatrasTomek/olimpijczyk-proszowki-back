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
        $stmt = getDb()->prepare('SELECT * FROM camps WHERE id = ?');
        $stmt->execute([$id]);
        $item = $stmt->fetch();
        if (!$item) jsonError(404, 'Nie znaleziono');
        jsonOk($item);

    case 'PUT':
        requireAuth();
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        $stmt = getDb()->prepare(
            'UPDATE camps SET name=?, date_from=?, date_to=?, location=?, description=?, status=?, image_path=?, spots_total=?, spots_left=? WHERE id=?'
        );
        $stmt->execute([
            trim($body['name'] ?? ''),
            trim($body['date_from'] ?? ''),
            trim($body['date_to'] ?? ''),
            trim($body['location'] ?? ''),
            trim($body['description'] ?? ''),
            $body['status'] ?? 'upcoming',
            trim($body['image_path'] ?? '') ?: null,
            (int)($body['spots_total'] ?? 0),
            (int)($body['spots_left'] ?? 0),
            $id,
        ]);
        jsonOk(['updated' => true]);

    case 'DELETE':
        requireAuth();
        $stmt = getDb()->prepare('DELETE FROM camps WHERE id = ?');
        $stmt->execute([$id]);
        jsonOk(['deleted' => true]);

    default:
        jsonError(405, 'Metoda niedozwolona');
}
