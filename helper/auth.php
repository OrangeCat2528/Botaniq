<?php
session_start();
require_once 'connection.php';
require_once 'encryption.php';

// Cek apakah user sudah login
if (!isset($_SESSION['login'])) {
    if (isset($_COOKIE['remember_token'])) {
        $encryptedToken = $_COOKIE['remember_token'];
        $rawToken = decryptToken($encryptedToken);
        $stmt = $connection->prepare("SELECT user_id FROM user_tokens WHERE token = ? AND expires_at > NOW()");
        $stmt->bind_param("s", $rawToken);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            $_SESSION['login'] = [
                'id' => $row['user_id']
            ];
        }
    }

    if (!isset($_SESSION['login'])) {
        header('Location: ../index');
        exit();
    }
}
?>
