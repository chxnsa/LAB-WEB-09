<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

require_once 'data.php';

$input_username = $_POST['username'] ?? '';
$input_password = $_POST['password'] ?? '';

$authenticated = false;
$userData = null;

foreach ($users as $user) {
    if ($user['username'] === $input_username) {
        if (password_verify($input_password, $user['password'])) {
            $authenticated = true;
            $userData = $user;
            break;
        }
    }
}

if ($authenticated) {
    unset($userData['password']); 
    $_SESSION['user'] = $userData;
    header('Location: dashboard.php');
    exit;
} else {
    $_SESSION['login_error'] = 'Username atau password salah!';
    header('Location: login.php');
    exit;
}