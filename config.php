<?php
ini_set('display_errors', '0');
mysqli_report(MYSQLI_REPORT_OFF);

$dbHost = getenv('SIMULATOR_DB_HOST');
$dbUser = getenv('SIMULATOR_DB_USER');
$dbPass = getenv('SIMULATOR_DB_PASSWORD');
$dbName = getenv('SIMULATOR_DB_NAME');

if ($dbHost === false || $dbHost === '') {
    $dbHost = 'localhost';
}
if ($dbUser === false || $dbUser === '') {
    $dbUser = 'root';
}
if ($dbPass === false) {
    $dbPass = 'root';
}
if ($dbName === false || $dbName === '') {
    $dbName = 'simulator';
}

try {
    $db = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
} catch (Exception $e) {
    $db = false;
}

if (!$db) {
    http_response_code(500);
    exit('Database connection failed');
}

if (!mysqli_set_charset($db, 'utf8mb4')) {
    mysqli_set_charset($db, 'utf8');
}

require_once __DIR__ . '/security.php';
