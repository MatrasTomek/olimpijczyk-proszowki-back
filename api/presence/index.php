<?php
require_once __DIR__ . '/../../config/cors.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../helpers/response.php';
require_once __DIR__ . '/../../helpers/auth.php';

setCors();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $rows = getDb()->query(
            'SELECT * FROM presence_items WHERE active = 1 ORDER BY sort_order ASC, id ASC'
        )->fetchAll();
        jsonOk($rows);

    case 'POST':
        requireAuth();
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        $title       = trim($body['title'] ?? '');
        $subtitle    = trim($body['subtitle'] ?? '');
        $description = trim($body['description'] ?? '');
        $metric      = trim($body['metric'] ?? '');
        $icon        = trim($body['icon'] ?? '');
        $image_path  = trim($body['image_path'] ?? '');
        $sort_order  = (int)($body['sort_order'] ?? 0);
        $active      = isset($body['active']) ? (int)(bool)$body['active'] : 1;
        if ($title === '') jsonError(400, 'Tytuł jest wymagany');
        $stmt = getDb()->prepare(
            'INSERT INTO presence_items (title, subtitle, description, metric, icon, image_path, sort_order, active)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$title, $subtitle, $description, $metric, $icon, $image_path ?: null, $sort_order, $active]);
        jsonOk(['id' => (int)getDb()->lastInsertId(), 'title' => $title], 201);

    default:
        jsonError(405, 'Metoda niedozwolona');
}
