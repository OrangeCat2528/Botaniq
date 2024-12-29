<?php
require_once '../helper/connection.php';
require_once '../helper/encryption.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function logoutUser() {
    global $connection;

    try {
        if (isset($_COOKIE['remember_token'])) {
            $encryptedToken = $_COOKIE['remember_token'];
            $rawToken = decryptToken($encryptedToken);

            $stmt = $connection->prepare("DELETE FROM user_tokens WHERE token = ?");
            $stmt->bind_param("s", $rawToken);
            $stmt->execute();

            setcookie('remember_token', '', time() - 3600, '/', '', true, true);
        }

        // Hapus semua session
        $_SESSION = array();
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        session_destroy();

    } catch (Exception $e) {
        error_log("Logout Error: " . $e->getMessage());
    }
}

logoutUser();
header('Location: login.php');
exit();