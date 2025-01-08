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
  <link rel="stylesheet" href="/assets/tailwind.css">  
  <link href="/style/style.css" rel="stylesheet">  
  <link href="/assets/fontawesome/css/all.css" rel="stylesheet">  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">  
  
  <style>  
    @font-face {  
      font-family: 'Montserrat';  
      src: url('/assets/montserrat.woff2') format('woff2');  
      font-weight: 100 900;  
      font-style: normal;  
    }  
  </style>  
</head>  
  
<body class="flex flex-col h-screen text-center scroll-smooth bg-gray-50">  
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
  <div class="flex-1 flex flex-col justify-center items-center">  
    <div class="text-center mb-8">  
      <img src="/assets/img/superapp-login-logo-only.png" alt="Botaniq SuperApp Logo" class="w-32 h-32 mx-auto">  
      <div class="mt-3 text-gray-600">  
        <span class="font-extrabold text-3xl">Botaniq SuperApp</span>  
      </div>  
    </div>  
  
    <div class="w-full max-w-sm">  
      <form method="POST" action="register.php" class="px-8 pt-6 pb-8 mb-4 mx-5 bg-white rounded-lg">  
        <div class="mb-4">  
          <label class="block text-gray-600 text-sm font-bold mb-2" for="username">Username</label>  
          <div class="flex items-center border border-gray-300 rounded-md">  
            <div class="px-3">  
              <i class="fas fa-user text-gray-400"></i>  
            </div>  
            <input class="w-full py-2 px-3 text-gray-600 focus:outline-none rounded-md" id="username" name="username" type="text" placeholder="Username" required>  
          </div>  
        </div>  
        <div class="mb-4">  
          <label class="block text-gray-600 text-sm font-bold mb-2" for="email">Email</label>  
          <div class="flex items-center border border-gray-300 rounded-md">  
            <div class="px-3">  
              <i class="fas fa-envelope text-gray-400"></i>  
            </div>  
            <input class="w-full py-2 px-3 text-gray-600 focus:outline-none rounded-md" id="email" name="email" type="email" placeholder="Email" required>  
          </div>  
        </div>  
        <div class="mb-6">  
          <label class="block text-gray-600 text-sm font-bold mb-2" for="password">Password</label>  
          <div class="flex items-center border border-gray-300 rounded-md">  
            <div class="px-3">  
              <i class="fas fa-lock text-gray-400"></i>  
            </div>  
            <input class="w-full py-2 px-3 text-gray-600 focus:outline-none rounded-md" id="password" name="password" type="password" placeholder="**********" required>  
          </div>  
        </div>  
        <div class="mb-6">  
          <label class="block text-gray-600 text-sm font-bold mb-2" for="confirm_password">Confirm Password</label>  
          <div class="flex items-center border border-gray-300 rounded-md">  
            <div class="px-3">  
              <i class="fas fa-lock text-gray-400"></i>  
            </div>  
            <input class="w-full py-2 px-3 text-gray-600 focus:outline-none rounded-md" id="confirm_password" name="confirm_password" type="password" placeholder="**********" required>  
          </div>  
        </div>  
  
        <!-- Button Section -->  
        <div class="flex flex-col items-center justify-center space-y-4">  
          <button type="submit" name="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-full focus:outline-none transition duration-200 ease-in-out w-full">  
            Register  
          </button>  
            
          <div class="flex items-center w-full">  
            <hr class="flex-grow border-gray-300">  
            <span class="mx-2 text-gray-600">OR</span>  
            <hr class="flex-grow border-gray-300">  
          </div>  
            
          <button type="button" class="bg-white border border-gray-300 text-gray-600 font-bold py-2 px-6 rounded-full focus:outline-none flex items-center justify-center transition duration-200 ease-in-out w-full">  
            <i class="fab fa-google text-red-500 mr-2"></i>   
            <span>Continue with Google</span>  
          </button>  
  
          <div class="mt-2">  
            <span class="text-gray-600">Already have an account?</span>  
            <a href="login" class="text-green-600 hover:text-green-700 font-bold">Login</a>  
          </div>  
        </div>  
      </form>  
    </div>  
  </div>  
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>  
</body>  
  
</html>  
