<?php
require_once '../helper/connection.php';
session_start();

$message = '';

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($connection) {
        $stmt = $connection->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            if ($password === $row['password']) {  // Compare passwords (unencrypted, use hash comparison in production)
                $_SESSION['login'] = $row;
                $message = "<script>
                              Swal.fire({
                                  icon: 'success',
                                  title: 'Logged In',
                                  text: 'Welcome Back, " . $username . "!',
                                  showConfirmButton: false,
                                  timer: 1500
                              }).then(() => {
                                  window.location.href = '../dashboard';
                              });
                            </script>";
            } else {
                $message = "<script>
                              Swal.fire({
                                  icon: 'error',
                                  title: 'Invalid username or password.',
                                  text: 'Please try again!'
                              });
                            </script>";
            }
        } else {
            $message = "<script>
                          Swal.fire({
                              icon: 'error',
                              title: 'Invalid username or password.',
                              text: 'Please try again!'
                          });
                        </script>";
        }

        $stmt->close();
    } else {
        $message = "Database connection failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Botaniq SuperApp - Login</title>

  <link rel="stylesheet" href="/assets/tailwind.css">
  <link href="/style/style.css" rel="stylesheet">
  <link href="/assets/fontawesome/css/all.css" rel="stylesheet">

  <style>
    @font-face {
      font-family: 'Montserrat';
      src: url('/assets/montserrat.woff2') format('woff2');
      font-weight: 100 900;
      font-style: normal;
    }
  </style>

  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body class="flex flex-col h-screen text-center scroll-smooth bg-white">

<div class="flex-1 flex flex-col justify-center items-center">
    <div class="text-center mb-8">
      <!-- Replace the FontAwesome icon with an image -->
      <img src="/assets/img/superapp-login-logo-only.png" alt="Botaniq SuperApp Logo" class="w-32 h-32 mx-auto"> <!-- Adjust width and height as needed -->
      <div class="mt-3 text-gray-600">
        <span class="font-extrabold text-3xl">Botaniq SuperApp</span>
      </div>
    </div>

    <!-- LOGIN AND SIGNUP SECTION -->
    <div class="w-full max-w-sm">
      <form method="POST" action="" class="px-8 pt-6 pb-8 mb-4 mx-5">
        <div class="mb-4">
          <label class="block text-gray-600 text-sm font-bold mb-2" for="username">
            Username
          </label>
          <div class="flex items-center border border-gray-300 rounded-md shadow-sm">
            <div class="px-3">
              <i class="fas fa-user text-gray-400"></i>
            </div>
            <input
              class="w-full py-2 px-3 text-gray-600 leading-tight focus:outline-none focus:shadow-outline rounded-r-md"
              id="username" name="username" type="text" placeholder="Username" required>
          </div>
        </div>
        <div class="mb-6">
          <label class="block text-gray-600 text-sm font-bold mb-2" for="password">
            Password
          </label>
          <div class="flex items-center border border-gray-300 rounded-md shadow-sm">
            <div class="px-3">
              <i class="fas fa-lock text-gray-400"></i>
            </div>
            <input
              class="w-full py-2 px-3 text-gray-600 leading-tight focus:outline-none focus:shadow-outline rounded-r-md"
              id="password" name="password" type="password" placeholder="**********" required>
          </div>
        </div>
        <div class="flex items-center justify-between">
          <button type="submit" name="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-8 rounded-full focus:outline-none focus:shadow-outline">
            Login
          </button>
          <a class="inline-block align-baseline font-bold text-sm text-green-600 hover:text-green-700" href="#">
            Forgot Password?
          </a>
        </div>
      </form>
      <p class="text-center text-gray-600 text-xs">
        &copy;2024 SmartPlants. All rights reserved.
      </p>
      <div class="mt-4 text-center">
        <span class="text-gray-600">Don't have an account?</span>
        <a href="#" class="text-green-600 hover:text-green-700 font-bold">Sign Up</a>
      </div>
    </div>
  </div>

  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <?php if (isset($message)) echo $message; ?>

</body>
</html>
