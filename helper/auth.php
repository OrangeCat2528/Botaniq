<?php
// cookie setahun
session_set_cookie_params(31536000);
session_start();

require_once 'connection.php';

function isLogin()
{
  // Check if the user is logged in
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
