<?php
require_once './helper/connection.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['login'])) {
    header('Location: ../index.php');
    exit;
}

// Get user ID from the session
$userId = $_SESSION['login']['id'];

// Check if the user has already linked the device
if ($connection) {
    $stmt = $connection->prepare("SELECT linked_id FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($linked_id);
    $stmt->fetch();
    $stmt->close();

    // If linked_id is already set (not null), redirect to dashboard
    if ($linked_id !== null) {
        header('Location: dashboard');
        exit;
    }
} else {
    die("Database connection failed.");
}

$message = '';

// Handle form submission
if (isset($_POST['submit'])) {
    // Combine the 4-digit OTP code into one number
    $device_id = $_POST['otp_1'] . $_POST['otp_2'] . $_POST['otp_3'] . $_POST['otp_4'];

    // Update the linked_id in the database for the current user
    if ($connection) {
        $stmt = $connection->prepare("UPDATE users SET linked_id = ? WHERE id = ?");
        $stmt->bind_param("si", $device_id, $userId);

        if ($stmt->execute()) {
            $message = "<script>
                          Swal.fire({
                              icon: 'success',
                              title: 'Device Connected!',
                              text: 'Your device has been successfully linked.',
                              showConfirmButton: false,
                              timer: 1500
                          }).then(() => {
                              window.location.href = 'dashboard.php'; // Redirect to dashboard after success
                          });
                        </script>";
        } else {
            $message = "<script>
                          Swal.fire({
                              icon: 'error',
                              title: 'Connection Failed',
                              text: 'There was a problem connecting the device. Please try again.'
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
    <title>Botaniq SuperApp - Connect Pot</title>

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

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        .otp-input {
            background-color: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            transition: all 0.2s ease;
        }

        .otp-input:focus {
            background-color: white;
            border-color: #22c55e;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
        }

        .otp-input.filled {
            border-color: #22c55e;
            background-color: #f0fdf4;
        }
    </style>
</head>

<body class="flex flex-col h-screen text-center scroll-smooth bg-gradient-to-b from-white to-green-50">
    <!-- Back Button -->
    <a href="auth/logout" class="absolute top-6 left-6 w-10 h-10 flex items-center justify-center rounded-full bg-white/80 backdrop-blur-sm border border-gray-100">
        <i class="fas fa-arrow-left text-gray-600"></i>
    </a>

    <div class="flex-1 flex flex-col justify-center items-center px-4">
        <!-- Icon Section -->
        <div class="mb-8 relative">
            <div class="absolute inset-0 bg-green-100 rounded-full scale-150 opacity-20"></div>
            <div class="relative float-animation">
                <div class="fas fa-leaf text-green-500 text-7xl mb-2"></div>
                <div class="absolute -right-1 -top-1 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-plus text-green-500 text-xs"></i>
                </div>
            </div>
        </div>

        <!-- Title Section -->
        <div class="mb-12 text-center">
            <h1 class="font-bold text-2xl text-gray-800 mb-2">Connect Your Device</h1>
            <p class="text-gray-500 max-w-xs mx-auto">Enter the 4-digit device code that was included in your Botaniq package</p>
        </div>

        <!-- OTP Form -->
        <div class="w-full max-w-sm">
            <form id="otp-form" method="POST" action="" class="space-y-8">
                <!-- OTP Inputs -->
                <div class="flex items-center justify-center gap-3">
                    <input name="otp_1" type="text" class="otp-input w-14 h-16 text-center text-2xl font-bold text-gray-700" pattern="\d*" maxlength="1" required />
                    <input name="otp_2" type="text" class="otp-input w-14 h-16 text-center text-2xl font-bold text-gray-700" maxlength="1" required />
                    <input name="otp_3" type="text" class="otp-input w-14 h-16 text-center text-2xl font-bold text-gray-700" maxlength="1" required />
                    <input name="otp_4" type="text" class="otp-input w-14 h-16 text-center text-2xl font-bold text-gray-700" maxlength="1" required />
                </div>

                <!-- Submit Button -->
                <button type="submit" name="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-2xl transition-all duration-200 flex items-center justify-center gap-2">
                    <i class="fas fa-link"></i>
                    Connect Device
                </button>
            </form>

            <!-- Help Text -->
            <div class="mt-6 flex justify-center items-center gap-2 text-gray-500">
                <i class="fas fa-question-circle"></i>
                <span class="text-sm">Need help? <a href="#" class="text-green-600 hover:text-green-700 font-medium">Contact Support</a></span>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if (isset($message)) echo $message; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('otp-form');
            const inputs = [...form.querySelectorAll('input[type=text]')];

            const handleInput = (e, index) => {
                const input = e.target;
                
                // Add/remove filled class
                if (input.value) {
                    input.classList.add('filled');
                } else {
                    input.classList.remove('filled');
                }

                // Handle navigation
                if (input.value && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            };

            const handleKeydown = (e, index) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    setTimeout(() => inputs[index - 1].focus(), 0);
                }
            };

            inputs.forEach((input, index) => {
                input.addEventListener('input', (e) => handleInput(e, index));
                input.addEventListener('keydown', (e) => handleKeydown(e, index));
            });
        });
    </script>
</body>
</html>