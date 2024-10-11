<?php
// cookie setahun
session_set_cookie_params([
  'lifetime' => 31536000,
  'path' => '/',
  'domain' => '',
  'secure' => true,
  'httponly' => true,
  'samesite' => 'Lax'
]);
session_start();
require_once 'connection.php'; // Assuming you have a database connection file

function isLogin()
{
  // Check if the user is logged in
  if (!isset($_SESSION['login'])) {
    header('Location: ../index');
    exit;
  }

  // Assuming 'login' contains user ID, retrieve user details from the database
  $userId = $_SESSION['login'];

  // Prepare the SQL query to get linked_id for the logged-in user
  $query = "SELECT linked_id FROM users WHERE id = ?";
  
  // Initialize the statement using the global $connection object
  $stmt = mysqli_prepare($GLOBALS['connection'], $query);

  // Bind the userId to the prepared statement
  mysqli_stmt_bind_param($stmt, "i", $userId);  // "i" denotes an integer parameter

  // Execute the statement
  mysqli_stmt_execute($stmt);

  // Bind the result to a variable
  mysqli_stmt_bind_result($stmt, $linked_id);

  // Fetch the result
  mysqli_stmt_fetch($stmt);

  // Close the statement
  mysqli_stmt_close($stmt);

  // If linked_id is NULL, redirect to connect.php
  if (is_null($linked_id)) {
    header('Location: connect');
    exit;
  }
}
