<?php
session_start();
require_once 'connection.php';
require_once 'encryption.php';

// Helper untuk mengecek apakah user login
function isUserLoggedIn() {
    if (isset($_SESSION['login'])) {
        return true;
    }

    if (isset($_COOKIE['remember_token'])) {
        global $connection;

        $encryptedToken = $_COOKIE['remember_token'];
        $rawToken = decryptToken($encryptedToken);

        $stmt = $connection->prepare("SELECT user_id FROM user_tokens WHERE token = ? AND expires_at > NOW()");
        $stmt->bind_param("s", $rawToken);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            // Set session dan return true
            $_SESSION['login'] = ['id' => $row['user_id']];
            return true;
        }
    }

    return false;
}

function ensureAuthenticated() {
    if (!isUserLoggedIn()) {
        header('Location: ../auth/login');
        exit();
    }
}
