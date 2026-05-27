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
            'UPDATE results SET athlete=?, competition=?, discipline=?, result_time=?, place=?, competition_date=? WHERE id=?'
        );
        $stmt->execute([
            trim($body['athlete'] ?? ''),
            trim($body['competition'] ?? ''),
            trim($body['discipline'] ?? ''),
            trim($body['result_time'] ?? ''),
            isset($body['place']) ? (int)$body['place'] : null,
            trim($body['competition_date'] ?? '') ?: null,
            $id,
        ]);
        jsonOk(['updated' => true]);

    case 'DELETE':
        requireAuth();
        $stmt = getDb()->prepare('DELETE FROM results WHERE id = ?');
        $stmt->execute([$id]);
        jsonOk(['deleted' => true]);

    default:
        jsonError(405, 'Metoda niedozwolona');
}
