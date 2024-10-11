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
    
    input[type="text"] {
      background-color: #f3f4f6; /* Light gray background */
      border: 2px solid #e5e7eb; /* Light gray border */
      border-radius: 8px;
    }

    input[type="text"]:focus {
      background-color: white;
      border-color: #10b981; /* Green border when focused */
    }
  </style>
</head>

<body class="flex flex-col h-screen text-center scroll-smooth bg-white">

  <div class="flex-1 flex flex-col justify-center items-center">
    <div class="text-center">
      <div class="fas fa-leaf text-green-600 text-8xl"></div>
      <div class="mt-0 text-gray-600 px-5">
        <span class="font-extrabold text-3xl">Connect Device</span>
        <p class="text-[15px] text-slate-500 description-text">Enter the 4-digit device code that was included in the package.</p>
      </div>
    </div>

  <!-- OTP VERIFICATION SECTION -->
  <div class="w-full max-w-sm">
      <div class="max-w-md mx-auto text-center bg-white px-2 sm:px-8 py-8"> <!-- Removed rounded-xl here -->
        <form id="otp-form" method="POST" action="" class="px-8 pt-6 pb-8 mb-4 mx-5">
          <div class="flex items-center justify-center gap-3 mb-4">
            <input
              name="otp_1"
              type="text"
              class="w-14 h-14 text-center text-2xl font-extrabold text-gray-600 appearance-none rounded p-4 outline-none focus:bg-white focus:ring-2 focus:ring-green-200"
              pattern="\d*" maxlength="1" required />
            <input
              name="otp_2"
              type="text"
              class="w-14 h-14 text-center text-2xl font-extrabold text-gray-600 appearance-none rounded p-4 outline-none focus:bg-white focus:ring-2 focus:ring-green-200"
              maxlength="1" required />
            <input
              name="otp_3"
              type="text"
              class="w-14 h-14 text-center text-2xl font-extrabold text-gray-600 appearance-none rounded p-4 outline-none focus:bg-white focus:ring-2 focus:ring-green-200"
              maxlength="1" required />
            <input
              name="otp_4"
              type="text"
              class="w-14 h-14 text-center text-2xl font-extrabold text-gray-600 appearance-none rounded p-4 outline-none focus:bg-white focus:ring-2 focus:ring-green-200"
              maxlength="1" required />
          </div>

          <div class="max-w-[260px] mx-auto mt-4">
            <button type="submit" name="submit"
              class="w-full inline-flex justify-center whitespace-nowrap rounded-lg bg-green-600 px-3.5 py-2.5 text-sm font-medium text-white shadow-sm shadow-green-950/10 hover:bg-green-700 focus:outline-none focus:ring focus:ring-green-300 focus-visible:outline-none focus-visible:ring focus-visible:ring-green-300 transition-colors duration-150">
              Connect Device
            </button>
          </div>
        </form>

        <div class="text-sm text-slate-500 mt-4">Didn't receive the code? <a class="font-medium text-green-600 hover:text-green-700" href="#">Contact Us</a></div>
      </div>

      <p class="text-center text-gray-600 text-xs mt-4">
        &copy;2024 Botaniq. All rights reserved.
      </p>
    </div>
  </div>

  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <?php if (isset($message)) echo $message; ?>

  <!-- OTP form JavaScript handling -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const form = document.getElementById('otp-form');
      const inputs = [...form.querySelectorAll('input[type=text]')];
      const submit = form.querySelector('button[type=submit]');

      const handleKeyDown = (e) => {
        if (!/^[0-9]{1}$/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete') {
          e.preventDefault();
        }

        if (e.key === 'Delete' || e.key === 'Backspace') {
          const index = inputs.indexOf(e.target);
          if (index > 0) {
            inputs[index - 1].focus();
          }
        }
      };

      const handleInput = (e) => {
        const index = inputs.indexOf(e.target);
        if (e.target.value && index < inputs.length - 1) {
          inputs[index + 1].focus();
        }
      };

      inputs.forEach(input => {
        input.addEventListener('keydown', handleKeyDown);
        input.addEventListener('input', handleInput);
      });
    });
  </script>
</body>
</html>
