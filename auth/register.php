<?php    
require_once '../helper/auth_helper.php';    
    
$auth = AuthHelper::getInstance();    
    
if ($auth->isLogged()) {    
    header("Location: ../dashboard");    
    exit();    
}    
    
$message = '';    
$message_type = '';    
    
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {    
    try {    
        $username = trim($_POST['username']);    
        $email = trim($_POST['email']);    
        $password = trim($_POST['password']);    
        $confirm_password = trim($_POST['confirm_password']);    
    
        if ($password !== $confirm_password) {    
            throw new Exception("Password mismatch. Please re-enter your password.");    
        }    
    
        $stmt = $connection->prepare("SELECT * FROM users WHERE username = ? OR email = ?");    
        $stmt->bind_param("ss", $username, $email);    
        $stmt->execute();    
        $result = $stmt->get_result();    
    
        if ($result->num_rows > 0) {    
            throw new Exception("Username or email already exists. Please try again.");    
        }    
    
        $stmt = $connection->prepare("INSERT INTO users (username, email, password, linked_id) VALUES (?, ?, ?, NULL)");    
        $stmt->bind_param("sss", $username, $email, $password);    
    
        if ($stmt->execute()) {    
            $message = "Registration successful. You may now log in.";    
            $message_type = 'success';    
        } else {    
            throw new Exception("Registration failed. Please try again.");    
        }    
            
        $stmt->close();    
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
  <title>Botaniq SuperApp - Register</title>    
    
  <!-- Stylesheets -->    
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
<?php if ($message): ?>    
    <div class="m-5 p-4 rounded-xl text-center     
                <?= $message_type === 'success' ? 'bg-green-100 border border-green-500 text-green-700' : 'bg-red-100 border border-red-500 text-red-700' ?>">    
      <div class="flex items-center justify-center">    
        <i class="fas <?= $message_type === 'success' ? 'fa-check-circle' : 'fa-times-circle' ?> text-xl mr-2"></i>    
        <span class="font-bold text-lg"><?= $message_type === 'success' ? 'Success' : 'Error' ?></span>    
      </div>    
      <p class="mt-2"><?= htmlspecialchars($message) ?></p>    
    </div>    
<?php endif; ?>    
    
  <!-- Registration Section -->    
  <main class="flex-1 flex flex-col justify-center items-center px-4 py-4">    
    <div class="text-center mb-4 relative">    
      <div class="absolute inset-0 bg-green-100 rounded-full scale-150 opacity-20"></div>    
      <div class="relative">    
        <img src="/assets/img/superapp-login-logo-only.png" alt="Botaniq SuperApp Logo" class="w-24 h-24 mx-auto mb-2">    
      </div>    
      <h1 class="font-extrabold text-3xl text-gray-700 mt-2">Botaniq SuperApp</h1>    
    </div>    
    
    <div class="w-full max-w-sm">    
      <form method="POST" action="register.php" class="space-y-4 bg-white/50 backdrop-blur-sm p-6 rounded-3xl border border-gray-100">    
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
          
        <!-- Email Input -->    
        <div class="space-y-1">    
          <label class="block text-gray-600 text-sm font-bold" for="email">Email</label>    
          <div class="input-group flex items-center bg-white border border-gray-200 rounded-2xl overflow-hidden">    
            <div class="px-4">    
              <i class="fas fa-envelope text-gray-400"></i>    
            </div>    
            <input class="login-input w-full py-2 px-2 text-gray-700 text-sm leading-tight focus:outline-none"    
                id="email" name="email" type="email" placeholder="Enter your email" required>    
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
          
        <!-- Confirm Password Input -->    
        <div class="space-y-1">    
          <label class="block text-gray-600 text-sm font-bold" for="confirm_password">Confirm Password</label>    
          <div class="input-group flex items-center bg-white border border-gray-200 rounded-2xl overflow-hidden">    
            <div class="px-4">    
              <i class="fas fa-lock text-gray-400"></i>    
            </div>    
            <input class="login-input w-full py-2 px-2 text-gray-700 text-sm leading-tight focus:outline-none"    
                id="confirm_password" name="confirm_password" type="password" placeholder="Re-enter your password" required>    
          </div>    
        </div>    
    
        <!-- Button Section -->    
        <button type="submit" name="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-2xl transition-all duration-200 flex items-center justify-center gap-2 shadow-lg shadow-green-500/20">    
          <i class="fas fa-user-plus"></i>    
          Register    
        </button>    
          
        <!-- Divider -->    
        <div class="flex items-center gap-2">    
          <hr class="flex-1 border-gray-200">    
          <span class="text-sm text-gray-400">or</span>    
          <hr class="flex-1 border-gray-200">    
        </div>    
          
        <!-- Google Login -->    
        <button type="button" class="w-full bg-white hover:bg-gray-50 text-gray-700 font-semibold py-2 px-4 rounded-2xl transition-all duration-200 flex items-center justify-center gap-2 border border-gray-200">    
          <i class="fab fa-google text-red-500"></i>    
          Continue with Google    
        </button>    
      </form>    
        
      <!-- Login Link -->    
      <div class="mt-4 text-center">    
        <p class="text-gray-500">Already have an account?    
          <a href="login" class="text-green-600 hover:text-green-700 font-semibold ml-1">Login</a>    
        </p>    
      </div>    
    </div>    
  </main>    
    
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>    
</body>    
    
</html>    
