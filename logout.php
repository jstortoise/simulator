<?php
require_once __DIR__ . '/security.php';

$_SESSION = array();
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    $name = session_name();
    if (PHP_VERSION_ID >= 70300) {
        setcookie($name, '', array(
            'expires' => time() - 42000,
            'path' => $params['path'],
            'domain' => $params['domain'],
            'secure' => $params['secure'],
            'httponly' => $params['httponly'],
            'samesite' => 'Lax',
        ));
    } else {
        setcookie($name, '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
}
session_destroy();
header('Location: login.php');
exit;
