<?php
require_once './layout/top.php';
require_once './helper/connection.php';
require_once './layout/header.php';
require_once './layout/sidebar.php';
?>

<div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <!-- Profile Image -->
    <div class="mb-4">
        <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" alt="Profile Image" class="w-32 h-32 rounded-full border-4 border-gray-300">
    </div>

    <!-- Buttons -->
    <div class="flex space-x-4">
        <a href="logout.php" class="bg-gray-500 text-white font-semibold py-2 px-4 rounded-full hover:bg-gray-600 transition duration-200">LOG OUT</a>
        <a href="change-password.php" class="bg-gray-500 text-white font-semibold py-2 px-4 rounded-full hover:bg-gray-600 transition duration-200">CHANGE PASS</a>
    </div>
</div>

<?php
require_once './layout/bottom.php';
?>

</body>
</html>