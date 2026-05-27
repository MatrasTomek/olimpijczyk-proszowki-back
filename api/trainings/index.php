<?php
require_once __DIR__ . '/../../config/cors.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../helpers/response.php';
require_once __DIR__ . '/../../helpers/auth.php';

setCors();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $db = getDb();
        $groups = $db->query('SELECT * FROM training_groups ORDER BY sort_order ASC, id ASC')->fetchAll();
        foreach ($groups as &$group) {
            $stmt = $db->prepare(
                'SELECT * FROM training_sessions WHERE group_id = ? ORDER BY sort_order ASC, id ASC'
            );
            $stmt->execute([$group['id']]);
            $group['sessions'] = $stmt->fetchAll();
        }
        jsonOk($groups);

    case 'POST':
        requireAuth();
        $body      = json_decode(file_get_contents('php://input'), true) ?? [];
        $name      = trim($body['name'] ?? '');
        $age_range = trim($body['age_range'] ?? '');
        $level     = trim($body['level'] ?? '');
        $sort_order = (int)($body['sort_order'] ?? 0);
        if ($name === '') jsonError(400, 'Nazwa grupy jest wymagana');
        $stmt = getDb()->prepare(
            'INSERT INTO training_groups (name, age_range, level, sort_order) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$name, $age_range, $level, $sort_order]);
        jsonOk(['id' => (int)getDb()->lastInsertId(), 'name' => $name], 201);

    default:
        jsonError(405, 'Metoda niedozwolona');
}
