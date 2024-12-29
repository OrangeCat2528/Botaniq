<?php
session_start();
require_once 'connection.php';
require_once 'encryption.php';

// Helper utama untuk mengelola otentikasi
function isUserLoggedIn() {
    global $connection;

    // Cek apakah sesi login ada
    if (isset($_SESSION['login'])) {
        return true;
    }

    // Cek cookie remember_token
    if (isset($_COOKIE['remember_token'])) {
        $encryptedToken = $_COOKIE['remember_token'];
        $rawToken = decryptToken($encryptedToken);

        $stmt = $connection->prepare("SELECT user_id FROM user_tokens WHERE token = ? AND expires_at > NOW()");
        $stmt->bind_param("s", $rawToken);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            // Set ulang sesi jika valid
            $_SESSION['login'] = ['id' => $row['user_id']];
            return true;
        }
    }

    // Jika tidak memenuhi kedua kondisi di atas, return false
    return false;
}

function ensureAuthenticated() {
    // Gabungkan logika: Jika tidak login, redirect ke halaman login
    if (!isUserLoggedIn()) {
        header('Location: ../auth/login.php');
        exit();
    }
}
