<?php
require_once './layout/top.php';
require_once './helper/connection.php';
require_once './layout/header.php';
require_once './layout/sidebar.php';
?>

<div class="flex flex-col items-center min-h-screen pt-2">
    <!-- Profile Image Section -->
    <div id="avatar-container" class="p-2 bg-white rounded-xl shadow-sm flex justify-center items-center mb-4">
        <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" alt="Profile Image" class="w-24 h-24 rounded-full border-2 border-gray-300">
    </div>

    <!-- User Info Section -->
    <div class="text-center mb-4">
        <h2 class="text-lg font-bold text-gray-700">Username</h2>
        <p class="text-sm text-gray-500">User Role</p>
    </div>

    <!-- Action Buttons (Smaller and Closer) -->
    <div class="flex space-x-2 mb-6">
        <a href="edit-profile.php" class="bg-gray-500 text-white font-semibold py-1 px-3 rounded-full hover:bg-gray-600 transition duration-200 shadow-sm text-sm flex items-center">
            <i class="fas fa-user-edit mr-1"></i> Edit Profile
        </a>
        <a href="logout.php" class="bg-gray-500 text-white font-semibold py-1 px-3 rounded-full hover:bg-gray-600 transition duration-200 shadow-sm text-sm flex items-center">
            <i class="fas fa-sign-out-alt mr-1"></i> Log Out
        </a>
        <a href="change-password.php" class="bg-gray-500 text-white font-semibold py-1 px-3 rounded-full hover:bg-gray-600 transition duration-200 shadow-sm text-sm flex items-center">
            <i class="fas fa-key mr-1"></i> Change Pass
        </a>
    </div>
</div>

    <div class="invisible h-20"></div>



<?php
require_once './layout/bottom.php';
?>

</body>
</html>