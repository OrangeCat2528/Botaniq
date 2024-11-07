<?php
require_once './layout/top.php';
require_once './helper/connection.php';
require_once './layout/header.php';
require_once './layout/sidebar.php';
?>

<div class="flex flex-col items-center min-h-screen pt-2">
    <!-- Profile Image Section -->
    <div id="avatar-container" class="p-2 bg-white rounded-xl shadow-sm flex justify-center items-center">
        <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" alt="Profile Image" class="w-24 h-24 rounded-full border-2 border-gray-300">
    </div>

    <!-- User Info Section -->
    <div class="text-center mb-4 mt-2">
        <h2 class="text-lg font-bold text-gray-700">Username</h2>
        <p class="text-sm text-gray-500">User Role</p>
    </div>

    <!-- Action Buttons -->
    <div class="flex space-x-4 mb-6">
        <a href="edit-profile.php" class="bg-gray-500 text-white font-semibold py-1 px-4 rounded-full hover:bg-gray-600 transition duration-200 shadow-sm">
            <i class="fas fa-user-edit mr-1"></i> Edit Profile
        </a>
        <a href="logout.php" class="bg-gray-500 text-white font-semibold py-1 px-4 rounded-full hover:bg-gray-600 transition duration-200 shadow-sm">
            <i class="fas fa-sign-out-alt mr-1"></i> Log Out
        </a>
    </div>

    <!-- User Activity Section -->
    <div class="w-full max-w-md m-5 bg-white rounded-3xl shadow-lg">
        <div class="bg-green-500 text-white rounded-t-3xl px-3 py-2 text-center flex justify-center items-center">
            <i class="fas fa-bell text-yellow-300 mr-2"></i> 
            <p class="text-sm md:text-base font-bold">Recent Activity</p>
        </div>
        <div class="p-5">
            <p class="text-sm text-gray-700 font-bold leading-relaxed">No recent notifications.</p>  
        </div>
    </div>

    <!-- Profile Summary Section -->
    <div class="w-full max-w-md m-5 bg-white rounded-3xl shadow-lg">
        <div class="bg-blue-500 text-white rounded-t-3xl px-3 py-2 text-center flex justify-center items-center">
            <i class="fas fa-info-circle text-white mr-2"></i> 
            <p class="text-sm md:text-base font-bold">Profile Summary</p>
        </div>
        <div class="p-5">
            <p class="text-sm text-gray-700 leading-relaxed">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus elementum sapien vitae...</p>
        </div>
    </div>

    <div class="invisible h-20"></div>
</div>



<?php
require_once './layout/bottom.php';
?>

</body>
</html>