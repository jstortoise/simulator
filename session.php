<?php
include('config.php');

$user = load_session_user();
if (!$user) {
    unset($_SESSION['login_user'], $_SESSION['login_role']);
    header('Location: login.php');
    exit;
}

$login_user = $user['username'];
$login_role = (int)$user['role'];
