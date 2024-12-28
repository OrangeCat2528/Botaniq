<?php
require_once '../helper/connection.php';
require_once '../helper/encryption.php';
session_start();

$message = '';
$message_type = '';

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($connection) {
        $stmt = $connection->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            if ($password === $row['password']) {
                $linked_id = is_null($row['linked_id']) ? 0 : $row['linked_id'];

                $_SESSION['login'] = [
                    'id' => $row['id'],
                    'username' => $row['username'],
                    'linked_id' => $linked_id
                ];

                $rawToken = bin2hex(random_bytes(32));
                $encryptedToken = encryptToken($rawToken);
                $device_info = $_SERVER['HTTP_USER_AGENT'];
                $expires_at = date('Y-m-d H:i:s', time() + 31536000);
                $stmt_token = $connection->prepare("INSERT INTO user_tokens (user_id, token, device_info, expires_at) VALUES (?, ?, ?, ?)");
                $stmt_token->bind_param("isss", $row['id'], $rawToken, $device_info, $expires_at);
                $stmt_token->execute();

                setcookie('remember_token', $encryptedToken, time() + 31536000, '/', '', true, true);

                if ($linked_id === 0) {
                    $message = "Please link your device to proceed!";
                    $message_type = 'warning';
                } else {
                    header("Location: ../dashboard");
                    exit();
                }
            } else {
                $message = "Invalid username or password. Please try again.";
                $message_type = 'error';
            }
        } else {
            $message = "Invalid username or password. Please try again.";
            $message_type = 'error';
        }

        $stmt->close();
    } else {
        $message = "Database connection failed.";
        $message_type = 'error';
    }
}
?>
