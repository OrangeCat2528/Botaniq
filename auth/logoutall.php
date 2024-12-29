<?php
require_once '../helper/auth_helper.php';

$auth = AuthHelper::getInstance();
$currentUser = $auth->getCurrentUser();

if ($currentUser) {
    try {
        // Delete all tokens for this user
        $stmt = $connection->prepare("DELETE FROM user_tokens WHERE user_id = ?");
        $stmt->bind_param("i", $currentUser['id']);
        $stmt->execute();
        $stmt->close();
        
        // Logout current session
        $auth->logout();
        
        header('Location: login.php');
        exit();
    } catch (Exception $e) {
        error_log("LogoutAll Error: " . $e->getMessage());
    }
}

header('Location: login.php');
exit();