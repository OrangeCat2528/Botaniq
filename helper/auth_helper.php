<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'connection.php';
require_once 'encryption.php';

function isUserLoggedIn() {
    global $connection;

    // Prioritaskan session terlebih dahulu
    if (isset($_SESSION['login']) && !empty($_SESSION['login']['id'])) {
        return true;
    }

    // Cek remember token jika session tidak ada
    if (isset($_COOKIE['remember_token'])) {
        try {
            $encryptedToken = $_COOKIE['remember_token'];
            $rawToken = decryptToken($encryptedToken);

            $stmt = $connection->prepare("SELECT ut.user_id, u.username, u.linked_id 
                                       FROM user_tokens ut 
                                       JOIN users u ON ut.user_id = u.id 
                                       WHERE ut.token = ? AND ut.expires_at > NOW()");
            $stmt->bind_param("s", $rawToken);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $row = $result->fetch_assoc()) {
                $_SESSION['login'] = [
                    'id' => $row['user_id'],
                    'username' => $row['username'],
                    'linked_id' => $row['linked_id']
                ];
                return true;
            }
        } catch (Exception $e) {
            error_log("Auth Error: " . $e->getMessage());
            return false;
        }
    }
    return false;
}

function ensureAuthenticated() {
    if (!isUserLoggedIn()) {
        header('Location: ../auth/login.php');
        exit();
    }
}

function getCurrentUser() {
    global $connection;
    
    if (!isUserLoggedIn()) {
        return null;
    }

    $userId = $_SESSION['login']['id'];
    $stmt = $connection->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}