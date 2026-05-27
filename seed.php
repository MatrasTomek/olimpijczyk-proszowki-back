<?php
/**
 * Seeder — wypełnia tabele danymi startowymi (mock z frontendu).
 * USUŃ TEN PLIK po uruchomieniu!
 *
 * Użycie: php seed.php
 */

require_once __DIR__ . '/config/db.php';

$db = getDb();

// --- Aktualności ---
$news = [
    ['Mistrzostwa Małopolski 2026', 'Nasi zawodnicy zdobyli 12 medali na Mistrzostwach Małopolski.', 'Szczegółowa treść aktualności o Mistrzostwach Małopolski 2026...', 'Wyniki', '2026-04-20'],
    ['Nowy trener dołącza do kadry', 'Z przyjemnością informujemy o dołączeniu do naszego zespołu trenera Marka Kowalskiego.', 'Szczegółowe informacje o nowym trenerze...', 'Ogólne', '2026-02-10'],
    ['Zapisy na obóz letni 2026', 'Otwieramy zapisy na letni obóz szkoleniowy w Krynica-Zdrój (7–21 lipca).', 'Szczegóły obozu: koszt, program, wymagania...', 'Obozy', '2026-04-01'],
];
$stmtNews = $db->prepare('INSERT IGNORE INTO news (title, excerpt, content, category, published_at) VALUES (?, ?, ?, ?, ?)');
foreach ($news as $n) {
    $stmtNews->execute($n);
}
echo "Aktualności: " . count($news) . " wpisów\n";

// --- Obozy ---
$camps = [
    ['Letni Obóz Szkoleniowy 2026', '2026-07-07', '2026-07-21', 'Krynica-Zdrój', 'Intensywny obóz szkoleniowy dla grup junior i młodzik.', 'open', 20, 8],
    ['Obóz Zimowy Wałcz', '2026-08-30', '2026-09-12', 'Wałcz', 'Zgrupowanie techniczne przed sezonem halowym.', 'upcoming', 16, 16],
    ['Obóz Egipt — Hurghada', '2026-03-05', '2026-03-11', 'Hurghada, Egipt', 'Obóz w słońcu dla najlepszych zawodników.', 'past', 12, 0],
    ['Obóz Szczyrk', '2026-02-07', '2026-02-15', 'Szczyrk', 'Obóz kondycyjny — zima w górach.', 'past', 14, 0],
];
$stmtCamps = $db->prepare(
    'INSERT IGNORE INTO camps (name, date_from, date_to, location, description, status, spots_total, spots_left) VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
);
foreach ($camps as $c) {
    $stmtCamps->execute($c);
}
echo "Obozy: " . count($camps) . " wpisów\n";

// --- Wyniki ---
$results = [
    ['Amelia Wąs', 'Mistrzostwa Małopolski', '100m dowolny', '1:02.34', 1, '2026-03-15'],
    ['Amelia Wąs', 'Mistrzostwa Małopolski', '50m klasyczny', '0:34.12', 2, '2026-03-15'],
    ['Hanna Jurek', 'Ogólnopolska Liga Pływacka', '200m zmienny', '2:28.50', 3, '2026-02-20'],
    ['Maks Wąs', 'Puchar Krakowa', '100m grzbietowy', '1:05.80', 1, '2026-01-18'],
    ['Hanna Jurek', 'Mistrzostwa Małopolski', '50m dowolny', '0:29.44', 1, '2026-03-15'],
    ['Amelia Wąs', 'Ogólnopolska Liga Pływacka', '200m dowolny', '2:15.20', 2, '2026-02-20'],
    ['Maks Wąs', 'Mistrzostwa Małopolski', '100m motyl', '1:02.10', 2, '2026-03-15'],
];
$stmtResults = $db->prepare(
    'INSERT IGNORE INTO results (athlete, competition, discipline, result_time, place, competition_date) VALUES (?, ?, ?, ?, ?, ?)'
);
foreach ($results as $r) {
    $stmtResults->execute($r);
}
echo "Wyniki: " . count($results) . " wpisów\n";

// --- Presence Items ---
$presence = [
    ['Zawody krajowe i międzynarodowe', 'Starty w całej Polsce i za granicą', 'Regularnie bierzemy udział w zawodach na wszystkich szczeblach rozgrywkowych.', '150+ zawodów', 'pi-trophy', 0],
    ['Medale i wyróżnienia', 'Ponad 300 medali w historii klubu', 'Nasi zawodnicy regularnie stają na podium mistrzostw regionalnych i krajowych.', '300+ medali', 'pi-medal', 1],
    ['Zasięg terytorialny', 'Reprezentujemy klub w całej Polsce', 'Startujemy w zawodach w ponad 30 miastach w Polsce i za granicą.', '30+ miast', 'pi-map-marker', 2],
    ['Doświadczenie i tradycja', 'Klub z wieloletnią historią', 'Przez lata wychowaliśmy wielu utytułowanych zawodników.', '5+ lat', 'pi-star', 3],
    ['Międzynarodowe reprezentacje', 'Starty poza granicami Polski', 'Nasi zawodnicy reprezentowali Polskę na zawodach w 6 krajach.', '6+ krajów', 'pi-globe', 4],
    ['Aktywna społeczność', 'Razem tworzymy klub', 'Dołącz do nas i rozwijaj swoje umiejętności w przyjaznej atmosferze.', '100+ zawodników', 'pi-users', 5],
];
$stmtPresence = $db->prepare(
    'INSERT IGNORE INTO presence_items (title, subtitle, description, metric, icon, sort_order) VALUES (?, ?, ?, ?, ?, ?)'
);
foreach ($presence as $p) {
    $stmtPresence->execute($p);
}
echo "Presence items: " . count($presence) . " wpisów\n";

// --- Grupy treningowe ---
$groups = [
    ['Dzieci', '6–9 lat', null, 0],
    ['Dzieci starsze', '10–11 lat', null, 1],
    ['Młodzik', '12–13 lat', null, 2],
    ['Junior Młodszy', '14–15 lat', null, 3],
];
$stmtGroup = $db->prepare('INSERT IGNORE INTO training_groups (name, age_range, level, sort_order) VALUES (?, ?, ?, ?)');
foreach ($groups as $g) {
    $stmtGroup->execute($g);
}
echo "Grupy: " . count($groups) . " wpisów\n";

// Sesje dla grupy "Dzieci" (id=1)
$sessionsDzieci = [
    [1, 'Wtorek', '16:00', null, null, 'Proszówki 25m', 'Proszówki', null, null, 0],
    [1, 'Środa',  '16:00', null, null, 'Proszówki 25m', 'Proszówki', null, null, 1],
    [1, 'Piątek', '16:00', null, null, 'Proszówki 25m', 'Proszówki', null, null, 2],
];
$stmtSess = $db->prepare(
    'INSERT IGNORE INTO training_sessions (group_id, day_of_week, time_start, time_morning, workout_type, pool, location, pool_summer, location_summer, sort_order)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
);
foreach ($sessionsDzieci as $s) {
    $stmtSess->execute($s);
}
echo "Sesje dzieci: " . count($sessionsDzieci) . " wpisów\n";

echo "\nGotowe. USUŃ TEN PLIK!\n";
