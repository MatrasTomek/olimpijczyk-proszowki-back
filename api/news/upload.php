<?php
require_once __DIR__ . '/../../config/cors.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../helpers/response.php';
require_once __DIR__ . '/../../helpers/auth.php';

setCors();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonError(405, 'Metoda niedozwolona');

requireAuth();

if (empty($_FILES['file'])) jsonError(400, 'Brak pliku');

$file = $_FILES['file'];

$allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
if (!in_array($file['type'], $allowed, true)) {
    jsonError(400, 'Niedozwolony typ pliku (jpg, png, webp, gif)');
}

$ext       = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename  = bin2hex(random_bytes(8)) . '.' . strtolower($ext);
$uploadDir = __DIR__ . '/../../uploads/news/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}
$dest = $uploadDir . $filename;

if (!move_uploaded_file($file['tmp_name'], $dest)) {
    jsonError(500, 'Błąd podczas zapisywania pliku');
}

jsonOk(['filename' => $filename, 'path' => 'uploads/news/' . $filename], 201);
