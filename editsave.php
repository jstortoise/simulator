<?php
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    forbid(405);
}
require_admin_api();

$action = isset($_POST['action']) && is_string($_POST['action']) ? $_POST['action'] : '';

if ($action === 'updateformula') {
    $fieldId = isset($_POST['field_id']) ? $_POST['field_id'] : '';
    $formula = isset($_POST['formula']) ? $_POST['formula'] : null;
    if (!is_safe_field_id($fieldId) || !is_string($formula)) {
        forbid(400);
    }
    $formula = trim($formula);
    if ($formula !== '' && !is_safe_formula($formula)) {
        json_out(array(
            'success' => false,
            'error' => 'Formula may only contain numbers, field ids, and + - * / ( ).',
        ));
    }
    if (!field_exists($fieldId)) {
        json_out(array('success' => false));
    }

    if ($formula === '') {
        $stmt = db_prepare('DELETE FROM formulas WHERE field_id = ?');
        if (!$stmt) {
            forbid(500);
        }
        mysqli_stmt_bind_param($stmt, 's', $fieldId);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        json_out(array('success' => (bool)$ok));
    }

    $stmt = db_prepare('SELECT id FROM formulas WHERE field_id = ? LIMIT 1');
    if (!$stmt) {
        forbid(500);
    }
    mysqli_stmt_bind_param($stmt, 's', $fieldId);
    $ok = mysqli_stmt_execute($stmt);
    $rows = $ok ? stmt_fetch_all_assoc($stmt) : array();
    mysqli_stmt_close($stmt);

    if (count($rows) === 1) {
        $stmt = db_prepare('UPDATE formulas SET formula = ? WHERE field_id = ?');
    } else {
        $stmt = db_prepare('INSERT INTO formulas (field_id, formula) VALUES (?, ?)');
    }
    if (!$stmt) {
        forbid(500);
    }
    if (count($rows) === 1) {
        mysqli_stmt_bind_param($stmt, 'ss', $formula, $fieldId);
    } else {
        mysqli_stmt_bind_param($stmt, 'ss', $fieldId, $formula);
    }
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    json_out(array('success' => (bool)$ok));
}

$data = isset($_POST['data']) && is_array($_POST['data']) ? $_POST['data'] : null;
if ($data === null || ($action !== 'Update' && $action !== 'Add')) {
    forbid(400);
}

$fieldId = isset($data['field_id']) ? $data['field_id'] : '';
$fieldLabel = clean_text(isset($data['field_label']) ? $data['field_label'] : null, 255);
$value = clean_text(isset($data['value']) ? $data['value'] : null, 255);
$unit = clean_text(isset($data['unit']) ? $data['unit'] : null, 255);
$description = clean_text(isset($data['description']) ? $data['description'] : null, 5000);
$typeRaw = isset($data['type']) ? $data['type'] : null;
$editableRaw = isset($data['editable']) ? $data['editable'] : null;

if (!is_safe_field_id($fieldId) || $fieldLabel === null || $value === null || $unit === null || $description === null) {
    forbid(400);
}
if (!is_string($typeRaw) || !preg_match('/^[01]$/', $typeRaw) || !is_string($editableRaw) || !preg_match('/^[01]$/', $editableRaw)) {
    forbid(400);
}
$type = (int)$typeRaw;
$editable = (int)$editableRaw;

if ($action === 'Update') {
    if (!field_exists($fieldId)) {
        json_out(array('success' => false));
    }
    $stmt = db_prepare('UPDATE fields SET field_label = ?, value = ?, unit = ?, type = ?, editable = ?, description = ? WHERE field_id = ?');
    if (!$stmt) {
        forbid(500);
    }
    mysqli_stmt_bind_param($stmt, 'sssiiss', $fieldLabel, $value, $unit, $type, $editable, $description, $fieldId);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    json_out(array('success' => (bool)$ok));
}

if (field_exists($fieldId)) {
    json_out(array('success' => false, 'error' => 'That field id already exists.'));
}

$newField = 1;
$stmt = db_prepare('INSERT INTO fields (field_label, field_id, value, unit, type, editable, description, new_field) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
if (!$stmt) {
    forbid(500);
}
mysqli_stmt_bind_param($stmt, 'ssssiisi', $fieldLabel, $fieldId, $value, $unit, $type, $editable, $description, $newField);
$ok = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
json_out(array('success' => (bool)$ok));

function field_exists($fieldId) {
    $stmt = db_prepare('SELECT id FROM fields WHERE field_id = ? LIMIT 1');
    if (!$stmt) {
        return false;
    }
    mysqli_stmt_bind_param($stmt, 's', $fieldId);
    $ok = mysqli_stmt_execute($stmt);
    $rows = $ok ? stmt_fetch_all_assoc($stmt) : array();
    mysqli_stmt_close($stmt);
    return count($rows) === 1;
}
