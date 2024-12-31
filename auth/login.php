<?php
// login.php
require_once '../helper/auth_helper.php';

$auth = AuthHelper::getInstance();

if ($auth->isLogged()) {
   header("Location: ../dashboard");
   exit();
}

$message = '';
$message_type = '';

if (isset($_POST['submit'])) {
   try {
       $username = trim($_POST['username']);
       $password = trim($_POST['password']);

       if (empty($username) || empty($password)) {
           throw new Exception("Please fill in all fields");
       }

       $user = $auth->login($username, $password);
       $linked_id = is_null($user['linked_id']) ? 0 : $user['linked_id'];

       if ($linked_id === 0) {
           header("Location: ../device/link.php");
       } else {
           header("Location: ../dashboard");
       }
       exit();

   } catch (Exception $e) {
       $message = $e->getMessage();
       $message_type = 'error';
   }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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

<script>
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('../PWA/service-worker.js')
      .then(registration => {
        console.log('Service Worker registered with scope:', registration.scope);
      })
      .catch(error => {
        console.error('Service Worker registration failed:', error);
      });
  }
</script>
<link rel="manifest" href="/manifest.json">
</head>

<body class="flex flex-col h-screen text-center scroll-smooth bg-white">

  <!-- Notification 
  <div class="m-5 p-4 bg-yellow-400 rounded-3xl shadow-lg text-center flex flex-col justify-center items-center">
    <div class="flex items-center justify-center">
      <i class="fas fa-exclamation-triangle text-xl mr-2"></i>
      <span class="font-bold text-lg">Information</span>
    </div>
    <p class="mt-2">
      Our application is under heavy development. Some features are not yet available and will be added soon. Thank you for your attention.
    </p>
  </div> -->

  <!-- Success or Error Notification -->
  <?php if ($message): ?>
    <div class="m-5 p-4 rounded-3xl shadow-lg text-center flex flex-col justify-center items-center 
                <?= $message_type === 'success' ? 'bg-green-100 border border-green-500 text-green-700' : ($message_type === 'error' ? 'bg-red-100 border border-red-500 text-red-700' : 'bg-yellow-100 border border-yellow-500 text-yellow-700') ?>">
      <div class="flex items-center justify-center">
        <i class="fas <?= $message_type === 'success' ? 'fa-check-circle' : ($message_type === 'error' ? 'fa-times-circle' : 'fa-exclamation-circle') ?> text-xl mr-2"></i>
        <span class="font-bold text-lg"><?= $message_type === 'success' ? 'Success' : ($message_type === 'error' ? 'Error' : 'Warning') ?></span>
      </div>
      <p class="mt-2"><?= htmlspecialchars($message) ?></p>
    </div>
  <?php endif; ?>

  <div class="flex-1 flex flex-col justify-center items-center">
    <div class="text-center mb-8">
      <img src="/assets/img/superapp-login-logo-only.png" alt="Botaniq SuperApp Logo" class="w-32 h-32 mx-auto">
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
        &copy;2024 Botaniq Start-Up. All rights reserved.
      </p>
      <div class="mt-4 text-center">
        <span class="text-gray-600">Just bought new Pots?</span>
        <a href="register" class="text-green-600 hover:text-green-700 font-bold">Sign Up</a>
      </div>
    </div>
  </div>

</body>

</html>
