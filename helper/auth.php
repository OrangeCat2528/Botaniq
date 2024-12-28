<?php
session_start();

require_once 'connection.php';
require_once 'encryption.php';

function isLogin() {
    if (!isset($_SESSION['login']) && isset($_COOKIE['remember_token'])) {
        $rememberToken = decryptToken($_COOKIE['remember_token']);
        if ($rememberToken) {
            // Check remember_token in the database
            $stmt = $pdo->prepare("SELECT * FROM users WHERE remember_token = ?");
            $stmt->execute([$rememberToken]);
            $user = $stmt->fetch();
            if ($user) {
                $_SESSION['login'] = $user;
            }
        }
    }

    if (!isset($_SESSION['login'])) {
        header('Location: auth/login');
        exit;
    }
}

function login($username, $password, $rememberMe) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['login'] = $user;

        if ($rememberMe) {
            $token = encryptToken($user['id'] . ':' . time());
            setcookie('remember_token', $token, time() + (86400 * 30), '/');
            $stmt = $pdo->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
            $stmt->execute([$token, $user['id']]);
        }

        header('Location: dashboard');
        exit;
    } else {
        return false;
    }
}

function encryptToken($data) {
    $iv = random_bytes(16);
    return base64_encode($iv . openssl_encrypt($data, 'aes-256-cbc', ENCRYPTION_KEY, 0, $iv));
}

function decryptToken($encryptedData) {
    $data = base64_decode($encryptedData);
    $iv = substr($data, 0, 16);
    $encrypted = substr($data, 16);
    return openssl_decrypt($encrypted, 'aes-256-cbc', ENCRYPTION_KEY, 0, $iv);
}

?>