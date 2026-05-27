<?php
require_once __DIR__ . '/../../config/cors.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../helpers/response.php';
require_once __DIR__ . '/../../helpers/auth.php';

setCors();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $rows = getDb()->query('SELECT * FROM results ORDER BY competition_date DESC, place ASC')->fetchAll();
        jsonOk($rows);

    case 'POST':
        requireAuth();
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        $athlete     = trim($body['athlete'] ?? '');
        $competition = trim($body['competition'] ?? '');
        $discipline  = trim($body['discipline'] ?? '');
        $result_time = trim($body['result_time'] ?? '');
        $place       = isset($body['place']) ? (int)$body['place'] : null;
        $comp_date   = trim($body['competition_date'] ?? '') ?: null;
        if ($athlete === '') jsonError(400, 'Nazwisko zawodnika jest wymagane');
        $stmt = getDb()->prepare(
            'INSERT INTO results (athlete, competition, discipline, result_time, place, competition_date) VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$athlete, $competition, $discipline, $result_time, $place, $comp_date]);
        jsonOk(['id' => (int)getDb()->lastInsertId(), 'athlete' => $athlete], 201);

    default:
        jsonError(405, 'Metoda niedozwolona');
}
