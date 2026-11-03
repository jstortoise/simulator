<?php

function send_security_headers() {
    if (headers_sent()) {
        return;
    }
    header('X-Frame-Options: DENY');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: same-origin');
    header('X-XSS-Protection: 1; mode=block');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Pragma: no-cache');
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self' data:; base-uri 'self'; form-action 'self'; frame-ancestors 'none'");
}

function app_cookie_path() {
    $name = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', $_SERVER['SCRIPT_NAME']) : '/';
    $dir = str_replace('\\', '/', dirname($name));
    if ($dir === '/' || $dir === '.' || $dir === '') {
        return '/';
    }
    return rtrim($dir, '/') . '/';
}

function start_secure_session() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_httponly', '1');
    if (PHP_VERSION_ID >= 70300) {
        ini_set('session.cookie_samesite', 'Lax');
    }

    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443);
    $path = app_cookie_path();

    if (PHP_VERSION_ID >= 70300) {
        session_set_cookie_params(array(
            'lifetime' => 0,
            'path' => $path,
            'domain' => '',
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ));
    } else {
        session_set_cookie_params(0, $path, '', $secure, true);
    }

    session_start();
}

function h($value) {
    $flags = ENT_QUOTES;
    if (defined('ENT_SUBSTITUTE')) {
        $flags |= ENT_SUBSTITUTE;
    }
    return htmlspecialchars((string)$value, $flags, 'UTF-8');
}

function secure_token($bytes) {
    if (function_exists('random_bytes')) {
        return bin2hex(random_bytes($bytes));
    }
    if (function_exists('openssl_random_pseudo_bytes')) {
        $strong = false;
        $bytes = openssl_random_pseudo_bytes($bytes, $strong);
        if ($bytes !== false) {
            return bin2hex($bytes);
        }
    }
    return bin2hex(hash('sha256', uniqid((string)mt_rand(), true), true));
}

function csrf_token() {
    if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = secure_token(32);
    }
    return $_SESSION['csrf_token'];
}

function request_csrf_ok() {
    if (!empty($_SERVER['HTTP_ORIGIN'])) {
        $origin = $_SERVER['HTTP_ORIGIN'];
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
        if ($origin !== 'http://' . $host && $origin !== 'https://' . $host) {
            return false;
        }
    }

    $token = '';
    if (isset($_POST['csrf_token']) && is_string($_POST['csrf_token'])) {
        $token = $_POST['csrf_token'];
    } elseif (isset($_SERVER['HTTP_X_CSRF_TOKEN']) && is_string($_SERVER['HTTP_X_CSRF_TOKEN'])) {
        $token = $_SERVER['HTTP_X_CSRF_TOKEN'];
    }

    if ($token === '' || empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], $token);
}

function json_out($data) {
    if (!headers_sent()) {
        header('Content-Type: application/json; charset=UTF-8');
    }
    $flags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
    $json = json_encode($data, $flags);
    echo $json === false ? '{"success":false}' : $json;
    exit;
}

function forbid($code) {
    http_response_code($code);
    json_out(array('success' => false));
}

function normalize_role($role) {
    $role = trim((string)$role);
    if ($role === '0') {
        return 0;
    }
    if ($role === '1') {
        return 1;
    }
    return null;
}

function db_prepare($sql) {
    global $db;
    if (!$db) {
        return false;
    }
    return mysqli_prepare($db, $sql);
}

function stmt_fetch_all_assoc($stmt) {
    if (function_exists('mysqli_stmt_get_result')) {
        $result = mysqli_stmt_get_result($stmt);
        if ($result instanceof mysqli_result) {
            $rows = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
            mysqli_free_result($result);
            return $rows;
        }
    }

    mysqli_stmt_store_result($stmt);
    $meta = mysqli_stmt_result_metadata($stmt);
    if (!$meta) {
        return array();
    }

    $row = array();
    $params = array($stmt);
    while ($field = mysqli_fetch_field($meta)) {
        $row[$field->name] = null;
        $params[] = &$row[$field->name];
    }
    mysqli_free_result($meta);
    call_user_func_array('mysqli_stmt_bind_result', $params);

    $rows = array();
    while (mysqli_stmt_fetch($stmt)) {
        $copy = array();
        foreach ($row as $key => $value) {
            $copy[$key] = $value;
        }
        $rows[] = $copy;
    }
    return $rows;
}

function load_session_user() {
    global $db;
    if (empty($_SESSION['login_user']) || !is_string($_SESSION['login_user'])) {
        return null;
    }

    $username = $_SESSION['login_user'];
    $stmt = db_prepare('SELECT username, role FROM users WHERE BINARY username = ? LIMIT 1');
    if (!$stmt) {
        return null;
    }
    mysqli_stmt_bind_param($stmt, 's', $username);
    $ok = mysqli_stmt_execute($stmt);
    $rows = $ok ? stmt_fetch_all_assoc($stmt) : array();
    mysqli_stmt_close($stmt);
    if (count($rows) !== 1) {
        return null;
    }

    $role = normalize_role($rows[0]['role']);
    if ($role === null) {
        return null;
    }

    $_SESSION['login_user'] = $rows[0]['username'];
    $_SESSION['login_role'] = $role;
    return array(
        'username' => $rows[0]['username'],
        'role' => $role,
    );
}

function require_admin_api() {
    $user = load_session_user();
    if (!$user || (int)$user['role'] !== 0) {
        forbid(403);
    }
    if (!request_csrf_ok()) {
        forbid(403);
    }
    return $user;
}

function clean_text($value, $maxLen) {
    if (!is_string($value) || strlen($value) > $maxLen || strpos($value, "\0") !== false) {
        return null;
    }
    return $value;
}

function is_safe_field_id($fieldId) {
    return is_string($fieldId) && preg_match('/^f\d{1,6}$/', $fieldId) === 1;
}

function is_safe_formula($formula) {
    if (!is_string($formula) || strlen($formula) > 1000) {
        return false;
    }
    return preg_match('/^[0-9+*\/(). f-]+$/', $formula) === 1;
}

function verify_and_upgrade_password($username, $password, $stored) {
    global $db;
    $dummy = '$2y$10$hdHpKhrSzqpTd.Wcd1szg.PZ3P502IuZJmClkJlZkPPkx67CVRns.';
    if (!is_string($stored) || $stored === '') {
        password_verify($password, $dummy);
        return false;
    }

    $info = password_get_info($stored);
    $rehash = false;
    if (!empty($info['algo'])) {
        $valid = password_verify($password, $stored);
        $rehash = $valid && password_needs_rehash($stored, PASSWORD_DEFAULT);
    } else {
        password_verify($password, $dummy);
        $valid = hash_equals($stored, $password);
        $rehash = $valid;
    }

    if ($rehash) {
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = is_string($newHash) ? db_prepare('UPDATE users SET password = ? WHERE BINARY username = ?') : false;
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ss', $newHash, $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    return $valid;
}

function render_page_head() {
    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="' . h(csrf_token()) . '">
    <title>Simulator</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/font-awesome/css/font-awesome.min.css">
</head>
';
}

send_security_headers();
start_secure_session();
