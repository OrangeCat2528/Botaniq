<?php
require_once '../helper/connection.php';
require_once '../helper/encryption.php';
session_start();

// Hapus token dari database dan cookie
function logoutUser() {
    global $connection;

    if (isset($_COOKIE['remember_token'])) {
        $encryptedToken = $_COOKIE['remember_token'];
        $rawToken = decryptToken($encryptedToken);

        $stmt = $connection->prepare("DELETE FROM user_tokens WHERE token = ?");
        $stmt->bind_param("s", $rawToken);
        $stmt->execute();
        $stmt->close();

        // Hapus cookie remember_token
        setcookie('remember_token', '', time() - 3600, '/', '', true, true);
    }

    // Hancurkan sesi
    session_unset();
    session_destroy();
}

// Jalankan proses logout
logoutUser();

// Redirect ke halaman login
header('Location: login');
exit();
