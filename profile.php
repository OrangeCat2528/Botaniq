<?php
require_once './layout/top.php';
require_once './helper/connection.php';
require_once './layout/header.php';
require_once './layout/sidebar.php';
?>

<div class="flex flex-col items-center justify-center min-h-screen bg-gray-50">
    <!-- Profile Image Section -->
    <div class="mb-4">
        <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" alt="Profile Image" class="w-28 h-28 rounded-full border-2 border-gray-300 shadow-md">
    </div>

    <!-- User Info Section -->
    <div class="text-center mb-4">
        <h2 class="text-lg font-bold text-gray-700">Username</h2>
        <p class="text-sm text-gray-500">User Role</p>
    </div>

    <!-- Action Buttons -->
    <div class="flex space-x-4 mb-6">
        <a href="logout.php" class="bg-gray-500 text-white font-semibold py-1 px-5 rounded-full hover:bg-gray-600 transition duration-200 shadow-sm">LOG OUT</a>
        <a href="change-password.php" class="bg-gray-500 text-white font-semibold py-1 px-5 rounded-full hover:bg-gray-600 transition duration-200 shadow-sm">CHANGE PASS</a>
    </div>
</div>



<?php
require_once './layout/bottom.php';
?>

</body>
</html>