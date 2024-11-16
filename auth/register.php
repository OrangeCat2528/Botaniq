<?php
require_once '../helper/connection.php';
session_start();

$message = '';

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $message = "<script>
                      Swal.fire({
                          icon: 'error',
                          title: 'Password mismatch',
                          text: 'Please re-enter your password!'
                      });
                    </script>";
    } else {
        $encrypt_key = getenv('ENCRYPT_DATA');
        $encrypted_password = openssl_encrypt($password, 'AES-256-CBC', $encrypt_key, 0, '1234567890123456');
        $stmt = $connection->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "<script>
                          Swal.fire({
                              icon: 'error',
                              title: 'Username or email already exists',
                              text: 'Please try again!'
                          });
                        </script>";
        } else {
            $stmt = $connection->prepare("INSERT INTO users (username, email, password, linked_id) VALUES (?, ?, ?, NULL)");
            $stmt->bind_param("sss", $username, $email, $encrypted_password);
            $stmt->execute();
            $message = "<script>
                          Swal.fire({
                              icon: 'success',
                              title: 'Registration successful',
                              text: 'Please login to continue!'
                          }).then(() => {
                              window.location.href = '../login';
                          });
                        </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Botaniq SuperApp - Register</title>

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

<div class="m-5 p-4 bg-yellow-400 rounded-3xl shadow-lg text-center flex flex-col justify-center items-center">
  <div class="flex items-center justify-center">
    <i class="fas fa-exclamation-triangle text-xl mr-2"></i>
    <span class="font-bold text-lg">Information</span>
  </div>
  <p class="mt-2">
  Our application is under heavy development. Some features are not yet available and will be added soon. Thank you for your attention.
  </p>
</div>


<div class="flex-1 flex flex-col justify-center items-center">
    <div class="text-center mb-8">
      <!-- Replace the FontAwesome icon with an image -->
      <img src="/assets/img/superapp-login-logo-only.png" alt="Botaniq SuperApp Logo" class="w-32 h-32 mx-auto"> <!-- Adjust width and height as needed -->
      <div class="mt-3 text-gray-600">
        <span class="font-extrabold text-3xl">Botaniq SuperApp</span>
      </div>
    </div>

    <!-- REGISTER SECTION -->
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
        <div class="mb-4">
          <label class="block text-gray-600 text-sm font-bold mb-2" for="email">
            Email
          </label>
          <div class="flex items-center border border-gray-300 rounded-md shadow-sm">
            <div class="px-3">
              <i class="fas fa-envelope text-gray-400"></i>
            </div>
            <input
              class="w-full py-2 px-3 text-gray-600 leading-tight focus:outline-none focus:shadow-outline rounded-r-md"
              id="email" name="email" type="email" placeholder="Email" required>
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
        <div class="mb-6">
          <label class="block text-gray-600 text-sm font-bold mb-2" for="confirm_password">
            Confirm Password
          </label>
          <div class="flex items-center border border-gray-300 rounded-md shadow-sm">
            <div class="px-3">
              <i class="fas fa-lock text-gray-400"></i>
            </div>
            <input
              class="w-full py-2 px-3 text-gray-600 leading-tight focus:outline-none focus:shadow-outline rounded-r-md"
              id="confirm_password" name="confirm_password" type="password" placeholder="**********" required>
          </div>
        </div>
        <div class="flex items-center justify-between">
          <button type="submit" name="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-8 rounded-full focus:outline-none focus:shadow-outline ">
            Register
          </button>
          <a class="inline-block align-baseline font-bold text-sm text-green-600 hover:text-green-700" href="login">
            Already have an account? Login
          </a>
        </div>
      </form>
      <p class="text-center text-gray-600 text-xs">
        &copy;2024 Botaniq Start-Up. All rights reserved.
      </p>
    </div>
  </div>

  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>