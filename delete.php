<?php
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    forbid(405);
}
require_admin_api();

$id = isset($_POST['delete_id']) ? (int)$_POST['delete_id'] : 0;
if ($id < 1) {
    forbid(400);
}

$stmt = db_prepare('SELECT field_id FROM fields WHERE id = ? AND new_field = 1 LIMIT 1');
if (!$stmt) {
    forbid(500);
}
mysqli_stmt_bind_param($stmt, 'i', $id);
$ok = mysqli_stmt_execute($stmt);
$rows = $ok ? stmt_fetch_all_assoc($stmt) : array();
mysqli_stmt_close($stmt);

if (count($rows) !== 1 || !is_safe_field_id($rows[0]['field_id'])) {
    json_out(array('success' => false));
}
$fieldId = $rows[0]['field_id'];

mysqli_query($db, 'START TRANSACTION');
$delFormula = db_prepare('DELETE FROM formulas WHERE field_id = ?');
$delField = db_prepare('DELETE FROM fields WHERE id = ? AND new_field = 1');
if (!$delFormula || !$delField) {
    mysqli_query($db, 'ROLLBACK');
    forbid(500);
}

mysqli_stmt_bind_param($delFormula, 's', $fieldId);
mysqli_stmt_bind_param($delField, 'i', $id);
$ok = mysqli_stmt_execute($delFormula) && mysqli_stmt_execute($delField);
mysqli_stmt_close($delFormula);
mysqli_stmt_close($delField);

if (!$ok) {
    mysqli_query($db, 'ROLLBACK');
    json_out(array('success' => false));
}

mysqli_query($db, 'COMMIT');
json_out(array('success' => true));
