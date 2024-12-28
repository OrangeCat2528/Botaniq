<?php
session_start();
require_once '../helper/connection.php';

if (isset($_SESSION['login']['id'])) {
    $user_id = $_SESSION['login']['id'];
    $stmt = $connection->prepare("DELETE FROM user_tokens WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
}

unset($_SESSION['login']);
$_SESSION['login'] = null;

setcookie('remember_token', '', time() - 3600, '/', '', true, true);

header('Location: login');
exit();
