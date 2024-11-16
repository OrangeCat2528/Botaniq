<?php
require_once '../helper/connection.php';
session_start();

// Check if the request is an AJAX POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = [];

    // Retrieve the current user ID from the session
    $userId = $_SESSION['login']['id'];
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];

    // Fetch the user's current password from the database
    $stmt = $connection->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        // Directly compare the plain text passwords
        if ($oldPassword === $row['password']) {
            // Update the password in the database
            $updateStmt = $connection->prepare("UPDATE users SET password = ? WHERE id = ?");
            $updateStmt->bind_param("si", $newPassword, $userId);

            if ($updateStmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Password updated successfully.';
                session_destroy(); // Log out the user after updating the password
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Failed to update the password.';
            }

            $updateStmt->close();
        } else {
            // Old password is incorrect
            $response['status'] = 'error';
            $response['message'] = 'Incorrect old password.';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'User not found.';
    }

    $stmt->close();
    echo json_encode($response);
    exit();
} else {
    header("Location: ../profile");
    exit();
}
