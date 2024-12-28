<?php
session_start();
require_once '../helper/connection.php';
require_once '../helper/encryption.php';

// Hapus token dari cookie
if (isset($_COOKIE['remember_token'])) {
    $encryptedToken = $_COOKIE['remember_token'];
    $rawToken = decryptToken($encryptedToken);
    $stmt = $connection->prepare("DELETE FROM user_tokens WHERE token = ?");
    $stmt->bind_param("s", $rawToken);
    $stmt->execute();
    
    setcookie('remember_token', '', time() - 3600, '/', '', true, true);
}

unset($_SESSION['login']);
$_SESSION['login'] = null;

header('Location: login');
exit();
