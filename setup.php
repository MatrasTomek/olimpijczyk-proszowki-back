<?php
/**
 * Jednorazowy skrypt konfiguracyjny — tworzy wszystkie tabele i konto admina.
 * USUŃ TEN PLIK po uruchomieniu!
 *
 * Użycie: php setup.php  lub otwórz w przeglądarce: https://twoja-domena.pl/setup.php
 */

require_once __DIR__ . '/config/db.php';

$adminUsername = 'admin';
$adminPassword = 'admin123'; // ZMIEŃ PRZED DEPLOYEM

$db = getDb();

$migrations = [
    __DIR__ . '/migrations/001_create_users.sql',
    __DIR__ . '/migrations/002_create_news.sql',
    __DIR__ . '/migrations/003_create_camps.sql',
    __DIR__ . '/migrations/004_create_gallery.sql',
    __DIR__ . '/migrations/005_create_presence_items.sql',
    __DIR__ . '/migrations/006_create_results.sql',
    __DIR__ . '/migrations/007_create_trainings.sql',
];

foreach ($migrations as $file) {
    $sql = file_get_contents($file);
    // Wykonaj każde polecenie osobno (plik może zawierać wiele CREATE TABLE)
    foreach (array_filter(array_map('trim', explode(';', $sql))) as $stmt) {
        $db->exec($stmt);
    }
    echo "OK: " . basename($file) . "\n";
}

$hash = password_hash($adminPassword, PASSWORD_BCRYPT, ['cost' => 12]);
$stmt = $db->prepare(
    'INSERT INTO users (username, password_hash) VALUES (?, ?)
     ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash)'
);
$stmt->execute([$adminUsername, $hash]);

echo "Gotowe. Konto admina: $adminUsername / $adminPassword\nUSUŃ TEN PLIK!\n";
