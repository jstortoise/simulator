<?php
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    forbid(405);
}
require_admin_api();

$name = clean_text(isset($_POST['name_add']) ? $_POST['name_add'] : null, 255);
$value = clean_text(isset($_POST['value_add']) ? $_POST['value_add'] : null, 255);
$unit = clean_text(isset($_POST['unit_add']) ? $_POST['unit_add'] : null, 255);
$description = clean_text(isset($_POST['description_add']) ? $_POST['description_add'] : null, 5000);
$typeRaw = isset($_POST['type_add']) ? $_POST['type_add'] : null;

if ($name === null || $value === null || $unit === null || $description === null || !is_string($typeRaw) || !preg_match('/^[01]$/', $typeRaw)) {
    forbid(400);
}
$type = (int)$typeRaw;

$stmt = db_prepare('INSERT INTO fields (field_label, value, unit, type, description, new_field) VALUES (?, ?, ?, ?, ?, 1)');
if (!$stmt) {
    forbid(500);
}
mysqli_stmt_bind_param($stmt, 'sssis', $name, $value, $unit, $type, $description);
$ok = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
json_out(array('success' => $ok));
