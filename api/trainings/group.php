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
            'UPDATE training_groups SET name=?, age_range=?, level=?, sort_order=? WHERE id=?'
        );
        $stmt->execute([
            trim($body['name'] ?? ''),
            trim($body['age_range'] ?? ''),
            trim($body['level'] ?? ''),
            (int)($body['sort_order'] ?? 0),
            $id,
        ]);
        jsonOk(['updated' => true]);

    case 'DELETE':
        requireAuth();
        // Sesje kasowane kaskadowo przez FK
        $stmt = getDb()->prepare('DELETE FROM training_groups WHERE id = ?');
        $stmt->execute([$id]);
        jsonOk(['deleted' => true]);

    default:
        jsonError(405, 'Metoda niedozwolona');
}
