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
      header("Location: ../connect");
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

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="/style/style.css" rel="stylesheet">
    <link href="/assets/fontawesome/css/all.css" rel="stylesheet">

    <style>
        @font-face {
            font-family: 'Montserrat';
            src: url('/assets/montserrat.woff2') format('woff2');
            font-weight: 100 900;
            font-style: normal;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        .input-group {
            transition: all 0.2s ease;
        }

        .input-group:focus-within {
            transform: translateY(-2px);
        }

        .login-input {
            transition: all 0.2s ease;
        }

        .login-input:focus {
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
        }
    </style>
</head>

<body class="flex flex-col min-h-screen bg-gradient-to-b from-white to-green-50">
    <!-- Notification Container -->
    <?php if ($message): ?>
        <div id="notification" class="fixed top-5 right-5 left-5 transform opacity-0 transition-all duration-500 ease-out">
            <!-- Notification content -->
        </div>
    <?php endif; ?>

    <main class="flex-1 flex flex-col justify-center items-center px-4 py-8">
         <!-- Logo Section -->  
         <div class="text-center mb-4 relative">  
            <div class="absolute inset-0 bg-green-100 rounded-full scale-150 opacity-20"></div>  
            <div class="relative float-animation">  
                <img src="/assets/img/superapp-login-logo-only.png" alt="Botaniq SuperApp Logo" class="w-24 h-24 mx-auto mb-2">  
            </div>  
            <h1 class="font-extrabold text-3xl text-gray-700 mt-2">Botaniq SuperApp</h1>  
            <p class="text-gray-500 mt-1">Welcome back Gardener!</p>  
        </div>  
  
        <!-- Form Section -->  
        <div class="w-full max-w-sm">  
            <form method="POST" action="" class="space-y-4 bg-white/50 backdrop-blur-sm p-6 rounded-3xl border border-gray-100">  
                <!-- Username Input -->  
                <div class="space-y-1">  
                    <label class="block text-gray-600 text-sm font-bold" for="username">Username</label>  
                    <div class="input-group flex items-center bg-white border border-gray-200 rounded-2xl overflow-hidden">  
                        <div class="px-4">  
                            <i class="fas fa-user text-gray-400"></i>  
                        </div>  
                        <input class="login-input w-full py-2 px-2 text-gray-700 text-sm leading-tight focus:outline-none"  
                            id="username" name="username" type="text" placeholder="Enter your username" required>  
                    </div>  
                </div>  
  
                <!-- Password Input -->  
                <div class="space-y-1">  
                    <label class="block text-gray-600 text-sm font-bold" for="password">Password</label>  
                    <div class="input-group flex items-center bg-white border border-gray-200 rounded-2xl overflow-hidden">  
                        <div class="px-4">  
                            <i class="fas fa-lock text-gray-400"></i>  
                        </div>  
                        <input class="login-input w-full py-2 px-2 text-gray-700 text-sm leading-tight focus:outline-none"  
                            id="password" name="password" type="password" placeholder="Enter your password" required>  
                    </div>  
                </div>
  
                <!-- Login Button -->  
                <button type="submit" name="submit"   
                    class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-2xl transition-all duration-200 flex items-center justify-center gap-2 shadow-lg shadow-green-500/20">  
                    <i class="fas fa-sign-in-alt"></i>  
                    Sign In  
                </button>  
  
                <!-- Divider -->  
                <div class="flex items-center gap-2">  
                    <hr class="flex-1 border-gray-200">  
                    <span class="text-sm text-gray-400">OR</span>  
                    <hr class="flex-1 border-gray-200">  
                </div>  
  
                <!-- Google Login -->  
                <button type="button"   
                    class="w-full bg-white hover:bg-gray-50 text-gray-700 font-semibold py-2 px-4 rounded-2xl transition-all duration-200 flex items-center justify-center gap-2 border border-gray-200">  
                    <i class="fab fa-google text-red-500"></i>  
                    Continue with Google  
                </button>  
            </form>  
  
            <!-- Sign Up Section -->  
            <div class="mt-4 text-center">  
                <p class="text-gray-500">  
                    New to Botaniq?   
                    <a href="register" class="text-green-600 hover:text-green-700 font-semibold ml-1">  
                        Create Account  
                    </a>  
                </p>  
            </div>  
  
            <!-- Copyright -->  
            <p class="text-center text-gray-400 text-xs mt-4">  
                &copy;2025 Botaniq Start-Up. All rights reserved.  
            </p>  
        </div>  
    </main>  
</body>  
  
</html>  