<?php

session_start();

$loginController = new \App\Controllers\LoginController();

if (!isset($_SESSION['nome'])) {
    header('Location: ?secao=login');
    exit;
}

if (isset($_SESSION['login_time']) && isset($_SESSION['session_expire'])) {
    if ((time() - $_SESSION['login_time']) > $_SESSION['session_expire']) {
        $loginController->Logout();
    } else {
        $_SESSION['login_time'] = time();
    }
} else {
    $loginController->Logout();
}
