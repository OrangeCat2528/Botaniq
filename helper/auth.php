<?php
require_once 'connection.php';
require_once 'encryption.php';

function getUserFromToken($token) {
    global $connection;
    $rawToken = decryptToken($token);
    $stmt = $connection->prepare("SELECT u.* FROM users u 
                                JOIN user_tokens ut ON u.id = ut.user_id 
                                WHERE ut.token = ? AND ut.expires_at > NOW()");
    $stmt->bind_param("s", $rawToken);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}

if (isset($_COOKIE['remember_token'])) {
    $user = getUserFromToken($_COOKIE['remember_token']);
    if ($user) {
        session_start();
        $_SESSION['login'] = [
            'id' => $user['id'],
        ];
    } else {
        setcookie('remember_token', '', time() - 3600, '/');
        header('Location: ../index');
        exit();
    }
} else {
    session_start();
    if (!isset($_SESSION['login'])) {
        header('Location: ../index');
        exit();
    }
}
?>