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
    case 'PUT':
        requireAuth();
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        $stmt = getDb()->prepare(
            'UPDATE presence_items SET title=?, subtitle=?, description=?, metric=?, icon=?, image_path=?, sort_order=?, active=? WHERE id=?'
        );
        $stmt->execute([
            trim($body['title'] ?? ''),
            trim($body['subtitle'] ?? ''),
            trim($body['description'] ?? ''),
            trim($body['metric'] ?? ''),
            trim($body['icon'] ?? ''),
            trim($body['image_path'] ?? '') ?: null,
            (int)($body['sort_order'] ?? 0),
            isset($body['active']) ? (int)(bool)$body['active'] : 1,
            $id,
        ]);
        jsonOk(['updated' => true]);

    case 'DELETE':
        requireAuth();
        $stmt = getDb()->prepare('SELECT image_path FROM presence_items WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if ($row && $row['image_path']) {
            $filepath = __DIR__ . '/../../../uploads/presence/' . basename($row['image_path']);
            if (file_exists($filepath)) unlink($filepath);
        }
        $stmt = getDb()->prepare('DELETE FROM presence_items WHERE id = ?');
        $stmt->execute([$id]);
        jsonOk(['deleted' => true]);

    default:
        jsonError(405, 'Metoda niedozwolona');
}
