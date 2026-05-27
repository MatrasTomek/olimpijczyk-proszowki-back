<?php
require_once __DIR__ . '/../../config/cors.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../helpers/response.php';
require_once __DIR__ . '/../../helpers/auth.php';

setCors();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $status = $_GET['status'] ?? null;
        if ($status) {
            $stmt = getDb()->prepare('SELECT * FROM camps WHERE status = ? ORDER BY date_from DESC');
            $stmt->execute([$status]);
        } else {
            $stmt = getDb()->query('SELECT * FROM camps ORDER BY date_from DESC');
        }
        jsonOk($stmt->fetchAll());

    case 'POST':
        requireAuth();
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        $name        = trim($body['name'] ?? '');
        $date_from   = trim($body['date_from'] ?? '');
        $date_to     = trim($body['date_to'] ?? '');
        $location    = trim($body['location'] ?? '');
        $description = trim($body['description'] ?? '');
        $status      = $body['status'] ?? 'upcoming';
        $image_path  = trim($body['image_path'] ?? '');
        $spots_total = (int)($body['spots_total'] ?? 0);
        $spots_left  = (int)($body['spots_left'] ?? 0);
        if ($name === '' || $date_from === '' || $date_to === '') {
            jsonError(400, 'Nazwa i daty są wymagane');
        }
        $stmt = getDb()->prepare(
            'INSERT INTO camps (name, date_from, date_to, location, description, status, image_path, spots_total, spots_left)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$name, $date_from, $date_to, $location, $description, $status, $image_path ?: null, $spots_total, $spots_left]);
        jsonOk(['id' => (int)getDb()->lastInsertId(), 'name' => $name], 201);

    default:
        jsonError(405, 'Metoda niedozwolona');
}
