<?php
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    forbid(405);
}
require_admin_api();

$action = isset($_POST['action']) && is_string($_POST['action']) ? $_POST['action'] : '';

if ($action === 'autofieldid') {
    $result = mysqli_query($db, 'SELECT field_id, new_field FROM fields WHERE id = (SELECT MAX(id) FROM fields)');
    $resultData = $result ? mysqli_fetch_assoc($result) : null;
    if (!$resultData) {
        json_out(array('fieldid' => 'f1'));
    }
    if (!empty($resultData['new_field'])) {
        json_out(array('fieldid' => 'f35'));
    }
    $parts = explode('f', isset($resultData['field_id']) ? (string)$resultData['field_id'] : '');
    $next = (isset($parts[1]) ? (int)$parts[1] : 0) + 1;
    if ($next < 1 || $next > 999999) {
        $next = 1;
    }
    json_out(array('fieldid' => 'f' . $next));
}

if ($action === 'getformular') {
    $fieldId = isset($_POST['fieldid']) ? $_POST['fieldid'] : '';
    if (!is_safe_field_id($fieldId)) {
        forbid(400);
    }
    $stmt = db_prepare('SELECT formula FROM formulas WHERE field_id = ? LIMIT 1');
    if (!$stmt) {
        forbid(500);
    }
    mysqli_stmt_bind_param($stmt, 's', $fieldId);
    $ok = mysqli_stmt_execute($stmt);
    $rows = $ok ? stmt_fetch_all_assoc($stmt) : array();
    mysqli_stmt_close($stmt);
    $formula = count($rows) === 1 ? $rows[0]['formula'] : null;
    json_out(array('formula' => $formula));
}

$id = isset($_POST['edit_id']) ? (int)$_POST['edit_id'] : 0;
if ($id < 1) {
    forbid(400);
}

$stmt = db_prepare('SELECT id, field_id, field_label, value, unit, type, description, new_field, editable FROM fields WHERE id = ? LIMIT 1');
if (!$stmt) {
    forbid(500);
}
mysqli_stmt_bind_param($stmt, 'i', $id);
$ok = mysqli_stmt_execute($stmt);
$rows = $ok ? stmt_fetch_all_assoc($stmt) : array();
mysqli_stmt_close($stmt);
if (count($rows) !== 1) {
    json_out(array('success' => false));
}
json_out($rows[0]);
