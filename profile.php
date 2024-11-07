<?php
require_once './layout/top.php';
require_once './helper/connection.php';
require_once './layout/header.php';
require_once './layout/sidebar.php';
?>

<div class="flex flex-col items-center justify-center min-h-screen bg-gradient-to-b from-blue-50 to-indigo-100">
    <!-- Profile Image Section -->
    <div class="mb-6">
        <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" alt="Profile Image" class="w-36 h-36 rounded-full border-4 border-indigo-400 shadow-lg">
    </div>

    <!-- User Info Section -->
    <div class="text-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Username</h2>
        <p class="text-gray-500">User Role</p>
    </div>

    <!-- Action Buttons -->
    <div class="flex space-x-4 mb-8">
        <a href="logout.php" class="bg-indigo-500 text-white font-semibold py-2 px-6 rounded-full hover:bg-indigo-600 transition duration-200 shadow-md">LOG OUT</a>
        <a href="change-password.php" class="bg-indigo-500 text-white font-semibold py-2 px-6 rounded-full hover:bg-indigo-600 transition duration-200 shadow-md">CHANGE PASS</a>
    </div>
</div>


<?php
require_once './layout/bottom.php';
?>

</body>
</html>