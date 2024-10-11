<?php
session_set_cookie_params([
    'lifetime' => 31536000,
    'path' => '/',
    'domain' => '',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_name('botaniq_sess');
session_start();

require_once 'connection.php';

function isLogin()
{
  if (!isset($_SESSION['login'])) {
    header('Location: ../index');
    exit;
  }

  $userId = $_SESSION['login'];
  $query = "SELECT linked_id FROM users WHERE id = ?";
  $stmt = mysqli_prepare($GLOBALS['connection'], $query);
  mysqli_stmt_bind_param($stmt, "i", $userId);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $linked_id);
  mysqli_stmt_fetch($stmt);
  mysqli_stmt_close($stmt);

  if (is_null($linked_id)) {
    header('Location: connect');
    exit;
  }
}
