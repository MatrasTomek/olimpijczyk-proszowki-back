<?php
require_once __DIR__ . '/../../config/cors.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../helpers/response.php';
require_once __DIR__ . '/../../helpers/auth.php';

setCors();

requireAuth();

$method = $_SERVER['REQUEST_METHOD'];
$id     = (int)($_GET['id'] ?? 0);

switch ($method) {
    case 'POST':
        $body     = json_decode(file_get_contents('php://input'), true) ?? [];
        $group_id = (int)($body['group_id'] ?? 0);
        $day      = trim($body['day_of_week'] ?? '');
        $time     = trim($body['time_start'] ?? '');
        if ($group_id <= 0 || $day === '' || $time === '') {
            jsonError(400, 'group_id, day_of_week i time_start są wymagane');
        }
        $stmt = getDb()->prepare(
            'INSERT INTO training_sessions
             (group_id, day_of_week, time_start, time_morning, workout_type, pool, location, pool_summer, location_summer, sort_order)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $group_id, $day, $time,
            trim($body['time_morning'] ?? '') ?: null,
            trim($body['workout_type'] ?? '') ?: null,
            trim($body['pool'] ?? '') ?: null,
            trim($body['location'] ?? '') ?: null,
            trim($body['pool_summer'] ?? '') ?: null,
            trim($body['location_summer'] ?? '') ?: null,
            (int)($body['sort_order'] ?? 0),
        ]);
        jsonOk(['id' => (int)getDb()->lastInsertId()], 201);

    case 'PUT':
        if ($id <= 0) jsonError(400, 'Brak id');
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        $stmt = getDb()->prepare(
            'UPDATE training_sessions SET day_of_week=?, time_start=?, time_morning=?, workout_type=?,
             pool=?, location=?, pool_summer=?, location_summer=?, sort_order=? WHERE id=?'
        );
        $stmt->execute([
            trim($body['day_of_week'] ?? ''),
            trim($body['time_start'] ?? ''),
            trim($body['time_morning'] ?? '') ?: null,
            trim($body['workout_type'] ?? '') ?: null,
            trim($body['pool'] ?? '') ?: null,
            trim($body['location'] ?? '') ?: null,
            trim($body['pool_summer'] ?? '') ?: null,
            trim($body['location_summer'] ?? '') ?: null,
            (int)($body['sort_order'] ?? 0),
            $id,
        ]);
        jsonOk(['updated' => true]);

    case 'DELETE':
        if ($id <= 0) jsonError(400, 'Brak id');
        $stmt = getDb()->prepare('DELETE FROM training_sessions WHERE id = ?');
        $stmt->execute([$id]);
        jsonOk(['deleted' => true]);

    default:
        jsonError(405, 'Metoda niedozwolona');
}
