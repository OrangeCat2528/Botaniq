<?php
require_once './layout/top.php';
require_once './helper/connection.php';
require_once './layout/header.php';
require_once './layout/sidebar.php';
?>

<div class="flex flex-col items-center min-h-screen pt-2">
    <!-- Profile Image Section (Rounded) -->
    <div id="avatar-container" class="p-2 bg-white rounded-full shadow-sm flex justify-center items-center mb-2">
        <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" alt="Profile Image" class="w-24 h-24 rounded-full border-2 border-gray-300">
    </div>

    <!-- User Info Section (Reduced Margin) -->
    <div class="text-center mb-4">
        <h2 class="text-lg font-bold text-gray-700"><?= $_SESSION['login']['username'] ?></h2>
        <p class="text-sm text-gray-500">Prototyper</p>
    </div>

    <!-- Action Buttons (Styled with Blue and Green) -->
    <div class="flex space-x-2 mb-6">
        <a href="edit-profile.php" class="bg-blue-500 text-white font-semibold py-1 px-4 rounded-full hover:bg-blue-600 transition duration-200 shadow-sm text-sm flex items-center">
            <i class="fas fa-user-edit mr-1"></i> Edit Profile
        </a>
        <button id="openChangePassModal" class="bg-green-500 text-white font-semibold py-1 px-4 rounded-full hover:bg-green-600 transition duration-200 shadow-sm text-sm flex items-center">
            <i class="fas fa-key mr-1"></i> Change Pass
        </button>
    </div>

    <!-- Devices List Button -->
    <div class="flex justify-center mt-4 w-full px-4">
        <button class="bg-white text-gray-700 font-semibold py-3 w-full max-w-md rounded-2xl flex justify-between items-center shadow-lg hover:shadow-xl transition duration-200 px-6">
            <span>Devices List</span>
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>

    <!-- Devices List Button -->
    <div class="flex justify-center mt-4 w-full px-4">
        <button class="bg-white text-gray-700 font-semibold py-3 w-full max-w-md rounded-2xl flex justify-between items-center shadow-lg hover:shadow-xl transition duration-200 px-6">
            <span>Manual Watering</span>
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>

    <!-- Devices List Button -->
    <div class="flex justify-center mt-4 w-full px-4">
        <button class="bg-white text-gray-700 font-semibold py-3 w-full max-w-md rounded-2xl flex justify-between items-center shadow-lg hover:shadow-xl transition duration-200 px-6">
            <span>AI Configuration</span>
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
    <!-- Devices List Button -->
    <div class="flex justify-center mt-4 w-full px-4">
        <button class="bg-white text-gray-700 font-semibold py-3 w-full max-w-md rounded-2xl flex justify-between items-center shadow-lg hover:shadow-xl transition duration-200 px-6">
            <span>Customer Support</span>
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
    <!-- Devices List Button -->
    <div class="flex justify-center mt-4 w-full px-4">
        <button class="bg-white text-gray-700 font-semibold py-3 w-full max-w-md rounded-2xl flex justify-between items-center shadow-lg hover:shadow-xl transition duration-200 px-6">
            <span>Request an Replacement</span>
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>

    <!-- LOG OUT Button -->
    <div class="flex justify-center mt-5 w-full px-4">
        <a href="auth/logout" class="bg-red-500 text-white font-semibold py-2 px-4 rounded-full hover:bg-red-600 transition duration-200 shadow-md text-sm flex items-center justify-center w-auto">
            LOG OUT
        </a>
    </div>

    <!-- Version Text -->
    <div class="flex justify-center mt-2">
        <p class="text-gray-500 italic text-xs">Botaniq Alpha Version 2.0</p>
    </div>
</div>

<!-- Change Password Modal -->
<div id="changePassModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white w-80 p-6 rounded-lg shadow-lg">
        <h2 class="text-lg font-bold mb-4">Change Password</h2>
        <form id="changePassForm" action="auth/change-password.php" method="POST">
            <div class="mb-3">
                <label for="oldPassword" class="block text-gray-600 text-sm font-semibold">Old Password</label>
                <input type="password" id="oldPassword" name="oldPassword" class="w-full py-2 px-3 border border-gray-300 rounded-md focus:outline-none">
                <p id="oldPasswordError" class="text-red-500 text-xs hidden">Incorrect password</p>
            </div>
            <div class="mb-3">
                <label for="newPassword" class="block text-gray-600 text-sm font-semibold">New Password</label>
                <input type="password" id="newPassword" name="newPassword" class="w-full py-2 px-3 border border-gray-300 rounded-md focus:outline-none">
            </div>
            <div class="flex justify-end mt-4">
                <button type="button" id="cancelChangePass" class="mr-3 text-gray-500 hover:text-gray-700">Cancel</button>
                <button type="submit" id="submitChangePass" class="bg-green-500 text-white font-semibold py-1 px-4 rounded-full hover:bg-green-600">Submit</button>
            </div>
        </form>
    </div>
</div>

<div class="invisible h-5"></div>

<?php
require_once './layout/bottom.php';
?>

<style>
    /* Modal Style */
    #changePassModal .scale-90 {
        transform: scale(0.9);
        opacity: 0;
    }

    #changePassModal.show .scale-90 {
        transform: scale(1);
        opacity: 1;
    }
</style>

<script>
    // JavaScript for Change Password Modal
    document.addEventListener('DOMContentLoaded', () => {
        const openChangePassModal = document.getElementById('openChangePassModal');
        const changePassModal = document.getElementById('changePassModal');
        const cancelChangePass = document.getElementById('cancelChangePass');
        const changePassForm = document.getElementById('changePassForm');
        const oldPasswordInput = document.getElementById('oldPassword');
        const oldPasswordError = document.getElementById('oldPasswordError');

        // Show modal
        openChangePassModal.addEventListener('click', () => {
            changePassModal.classList.remove('hidden');
        });

        // Hide modal
        cancelChangePass.addEventListener('click', () => {
            changePassModal.classList.add('hidden');
            oldPasswordError.classList.add('hidden'); // Hide error on cancel
            oldPasswordInput.classList.remove('border-red-500'); // Reset border
            changePassForm.reset(); // Reset form
        });

        // Handle form submission
        changePassForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const oldPassword = oldPasswordInput.value;

            // Mock validation (replace this with server-side validation)
            if (oldPassword !== 'correct-password') { // replace 'correct-password' with real check
                oldPasswordError.classList.remove('hidden');
                oldPasswordInput.classList.add('border-red-500');
            } else {
                // Redirect to logout
                window.location.href = 'auth/logout';
            }
        });
    });
</script>

</body>

</html>