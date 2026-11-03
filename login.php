<?php
include('config.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!request_csrf_ok()) {
        $error = 'Invalid request. Reload the page and try again.';
    } else {
        $now = time();
        $attempts = isset($_SESSION['login_attempts']) ? (int)$_SESSION['login_attempts'] : 0;
        $attemptAt = isset($_SESSION['login_attempt_at']) ? (int)$_SESSION['login_attempt_at'] : 0;
        if ($attempts >= 8 && ($now - $attemptAt) < 900) {
            $error = 'Too many login attempts. Please try again in a few minutes.';
        } else {
            if ($attemptAt !== 0 && ($now - $attemptAt) >= 900) {
                $attempts = 0;
            }

            $username = isset($_POST['username']) && is_string($_POST['username']) ? $_POST['username'] : '';
            $password = isset($_POST['password']) && is_string($_POST['password']) ? $_POST['password'] : '';
            $valid = false;
            $role = null;
            $canonicalUser = '';

            if ($username !== '' && strlen($username) <= 255 && strpos($username, "\0") === false
                && $password !== '' && strlen($password) <= 255 && strpos($password, "\0") === false) {
                $stmt = db_prepare('SELECT username, password, role FROM users WHERE BINARY username = ? LIMIT 1');
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, 's', $username);
                    $ok = mysqli_stmt_execute($stmt);
                    $rows = $ok ? stmt_fetch_all_assoc($stmt) : array();
                    mysqli_stmt_close($stmt);
                    if (count($rows) === 1) {
                        $canonicalUser = $rows[0]['username'];
                        $role = normalize_role($rows[0]['role']);
                        $valid = $role !== null && verify_and_upgrade_password($canonicalUser, $password, $rows[0]['password']);
                    } else {
                        password_verify($password, '$2y$10$hdHpKhrSzqpTd.Wcd1szg.PZ3P502IuZJmClkJlZkPPkx67CVRns.');
                    }
                }
            } else {
                password_verify($password === '' ? 'invalid' : $password, '$2y$10$hdHpKhrSzqpTd.Wcd1szg.PZ3P502IuZJmClkJlZkPPkx67CVRns.');
            }

            if ($valid) {
                session_regenerate_id(true);
                $_SESSION['login_user'] = $canonicalUser;
                $_SESSION['login_role'] = $role;
                $_SESSION['login_attempts'] = 0;
                unset($_SESSION['login_attempt_at']);
                $_SESSION['csrf_token'] = secure_token(32);
                header('Location: ' . ($role === 0 ? 'admin.php' : 'index.php'));
                exit;
            }

            $_SESSION['login_attempts'] = $attempts + 1;
            $_SESSION['login_attempt_at'] = $now;
            $error = 'Your Login Name or Password is invalid';
        }
    }
}
?>

<html>
    <head>
        <title>Login Page</title>
        <style type = "text/css">
            body {
                font-family:Arial, Helvetica, sans-serif;
                font-size:14px;
            }
            label {
                font-weight:bold;
                width:100px;
                font-size:14px;
            }
            .box {
                border:#666666 solid 1px;
            }
        </style>
    </head>
   
    <body bgcolor = "#FFFFFF">
        <div align = "center">
            <div style = "width:300px; border: solid 1px #333333; " align = "left">
                <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Login</b></div>
                <div style = "margin:30px">
                    <form action = "" method = "post">
                        <input type="hidden" name="csrf_token" value="<?php echo h(csrf_token()); ?>"/>
                        <label>UserName  :</label><input type = "text" name = "username" class = "box" maxlength="255" autocomplete="username"/><br /><br />
                        <label>Password  :</label><input type = "password" name = "password" class = "box" maxlength="255" autocomplete="current-password" /><br/><br />
                        <input type = "submit" value = " Submit "/><br />
                    </form>
                    <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo h($error); ?></div>
                </div>
            </div>
        </div>
    </body>
</html>
