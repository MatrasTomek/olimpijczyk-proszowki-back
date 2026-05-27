<?php
require_once __DIR__ . '/../../config/cors.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../helpers/response.php';

setCors();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') jsonError(405, 'Metoda niedozwolona');

$category = $_GET['category'] ?? null;
if ($category) {
    $stmt = getDb()->prepare('SELECT * FROM gallery_images WHERE category = ? ORDER BY uploaded_at DESC');
    $stmt->execute([$category]);
} else {
    $stmt = getDb()->query('SELECT * FROM gallery_images ORDER BY uploaded_at DESC');
}
jsonOk($stmt->fetchAll());
