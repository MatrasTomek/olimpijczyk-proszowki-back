<?php
require_once __DIR__ . '/../../config/cors.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../helpers/response.php';
require_once __DIR__ . '/../../helpers/auth.php';

setCors();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) jsonError(400, 'Brak id');

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') jsonError(405, 'Metoda niedozwolona');

requireAuth();

$stmt = getDb()->prepare('SELECT filename FROM gallery_images WHERE id = ?');
$stmt->execute([$id]);
$row = $stmt->fetch();
if (!$row) jsonError(404, 'Nie znaleziono');

$filepath = __DIR__ . '/../../../uploads/gallery/' . $row['filename'];
if (file_exists($filepath)) {
    unlink($filepath);
}

$stmt = getDb()->prepare('DELETE FROM gallery_images WHERE id = ?');
$stmt->execute([$id]);
jsonOk(['deleted' => true]);
