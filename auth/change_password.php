<?php
require_once '../helper/auth_helper.php';

$auth = AuthHelper::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = [];

    if (!$auth->isLogged()) {
        echo json_encode([
            'status' => 'error',
            'message' => 'User not logged in'
        ]);
        exit;
    }

    try {
        $currentUser = $auth->getCurrentUser();
        $oldPassword = trim($_POST['oldPassword']);
        $newPassword = trim($_POST['newPassword']);

        // Verify old password
        $stmt = $connection->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $currentUser['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user || $oldPassword !== $user['password']) {
            throw new Exception('Incorrect old password.');
        }

        // Update password
        $updateStmt = $connection->prepare("UPDATE users SET password = ? WHERE id = ?");
        $updateStmt->bind_param("si", $newPassword, $currentUser['id']);

        if ($updateStmt->execute()) {
            // Delete all tokens
            $deleteStmt = $connection->prepare("DELETE FROM user_tokens WHERE user_id = ?");
            $deleteStmt->bind_param("i", $currentUser['id']);
            $deleteStmt->execute();
            
            // Logout user
            $auth->logout();
            
            $response['status'] = 'success';
            $response['message'] = 'Password updated successfully. Please login again.';
        } else {
            throw new Exception('Failed to update password.');
        }
        
        $stmt->close();
        $updateStmt->close();
        if(isset($deleteStmt)) $deleteStmt->close();
        
    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = $e->getMessage();
    }

    echo json_encode($response);
    exit();
} else {
    header("Location: ../profile");
    exit();
}