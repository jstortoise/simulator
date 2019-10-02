<?php
    include('config.php');
    session_start();
   
    $user_check = $_SESSION['login_user'];
    $role_check = $_SESSION['login_role'];
    $ses_sql = mysqli_query($db, "select username, role from users where username = '$user_check' AND role = '$role_check' ");
    $row = mysqli_fetch_array($ses_sql, MYSQLI_ASSOC);
    $login_user = $row['username'];
    $login_role = $row['role'];

    if (!isset($_SESSION['login_user'])) {
        header("location:login.php");
        die();
    }
?>

<!DOCTYPE html>
<!-- saved from url=(0033)http://demo1.viwiz.net/simulator/ -->
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Simulator</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/font-awesome/css/font-awesome.min.css">
</head>

