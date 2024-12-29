<?php
require_once './layout/top.php';
require_once './helper/connection.php';
require_once './layout/header.php';
require_once './layout/sidebar.php';
?>

<div class="flex flex-col items-center min-h-screen pt-2">
    <!-- Profile Image Section (Rounded) -->
    <div id="avatar-container" class="p-2 bg-white rounded-full shadow-sm flex justify-center items-center mb-2">
        <img src="assets/profile.webp" alt="Profile Image" class="w-24 h-24 rounded-full border-2 border-gray-300">
    </div>

    <!-- User Info Section (Reduced Margin) -->
    <div class="text-center mb-4">
        <h2 class="text-lg font-bold text-gray-700"><?= $_SESSION['login']['username'] ?></h2>
        <p class="text-sm text-gray-500">User</p>
    </div>

    <!-- Action Buttons (Styled with Blue and Green) -->
    <div class="flex space-x-2 mb-6">
        <button data-feature-not-ready class="bg-blue-500 text-white font-semibold py-1 px-4 rounded-full hover:bg-blue-600 transition duration-200 shadow-sm text-sm flex items-center">
            <i class="fas fa-user-edit mr-1"></i> Edit Profile
        </button>
        <button id="openChangePassModal" class="bg-green-500 text-white font-semibold py-1 px-4 rounded-full hover:bg-green-600 transition duration-200 shadow-sm text-sm flex items-center">
            <i class="fas fa-key mr-1"></i> Change Pass
        </button>
    </div>

    <!-- Feature Not Ready Buttons -->
    <div class="flex justify-center mt-4 w-full px-4">
        <button data-feature-not-ready class="bg-white text-gray-700 font-semibold py-3 w-full max-w-md rounded-2xl flex justify-between items-center shadow-lg hover:shadow-xl transition duration-200 px-6">
            <span>Devices List</span>
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
    <div class="flex justify-center mt-4 w-full px-4">
        <button data-feature-not-ready class="bg-white text-gray-700 font-semibold py-3 w-full max-w-md rounded-2xl flex justify-between items-center shadow-lg hover:shadow-xl transition duration-200 px-6">
            <span>Manual Watering</span>
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
    <div class="flex justify-center mt-4 w-full px-4">
        <button data-feature-not-ready class="bg-white text-gray-700 font-semibold py-3 w-full max-w-md rounded-2xl flex justify-between items-center shadow-lg hover:shadow-xl transition duration-200 px-6">
            <span>AI Configuration</span>
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
    <div class="flex justify-center mt-4 w-full px-4">
        <button data-feature-not-ready class="bg-white text-gray-700 font-semibold py-3 w-full max-w-md rounded-2xl flex justify-between items-center shadow-lg hover:shadow-xl transition duration-200 px-6">
            <span>Customer Support</span>
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
    <div class="flex justify-center mt-4 w-full px-4">
        <button data-feature-not-ready class="bg-white text-gray-700 font-semibold py-3 w-full max-w-md rounded-2xl flex justify-between items-center shadow-lg hover:shadow-xl transition duration-200 px-6">
            <span>Request a Replacement</span>
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>

    <!-- LOG OUT Button -->
    <div class="flex justify-center mt-5 w-full px-4 space-x-3">
        <a href="auth/logout" class="bg-red-500 text-white font-semibold py-2 px-4 rounded-full hover:bg-red-600 transition duration-200 shadow-md text-sm flex items-center justify-center w-auto">
            LOG OUT
        </a>
        <a href="auth/logoutall" class="bg-gray-800 text-white font-semibold py-2 px-4 rounded-full hover:bg-gray-900 transition duration-200 shadow-md text-sm flex items-center justify-center w-auto">
            LOGOUT ALL DEVICE
        </a>
    </div>

    <!-- Version Text -->
    <div class="flex justify-center mt-2">
        <p class="text-gray-500 italic text-xs">Botaniq Alpha Version 2.0</p>
    </div>
</div>

<!-- Change Password Modal -->
<div id="changePassModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50 transition duration-300">
    <div class="bg-white w-80 p-6 rounded-lg shadow-lg scale-90 transform transition duration-300 opacity-0" id="changePassContent">
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

<!-- Feature Not Ready Modal -->
<div id="popup-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white rounded-3xl shadow-lg p-6 w-11/12 max-w-lg relative">
        <button id="close-feature-modal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
            <i class="fas fa-times text-lg"></i>
        </button>
        <div class="text-orange-500 text-3xl mb-3 flex justify-center">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <p class="text-gray-700 text-center font-bold text-sm md:text-base mb-4">This Feature is not yet Done, Stay Tuned!</p>
        <button class="bg-green-500 text-white font-bold px-4 py-2 rounded-lg w-full flex items-center justify-center gap-2 transition duration-300 hover:bg-green-600">
            <span>OK</span>
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Change Password Modal
        const openChangePassModal = document.getElementById('openChangePassModal');
        const changePassModal = document.getElementById('changePassModal');
        const changePassContent = document.getElementById('changePassContent');
        const cancelChangePass = document.getElementById('cancelChangePass');
        const changePassForm = document.getElementById('changePassForm');
        const oldPasswordInput = document.getElementById('oldPassword');
        const oldPasswordError = document.getElementById('oldPasswordError');

        openChangePassModal.addEventListener('click', () => {
            changePassModal.classList.remove('hidden');
            setTimeout(() => changePassContent.classList.remove('scale-90', 'opacity-0'), 10);
        });

        cancelChangePass.addEventListener('click', () => {
            changePassContent.classList.add('scale-90', 'opacity-0');
            setTimeout(() => changePassModal.classList.add('hidden'), 300);
            oldPasswordError.classList.add('hidden');
            oldPasswordInput.classList.remove('border-red-500');
            changePassForm.reset();
        });

        // Feature Not Ready Modal
        const featureModal = document.getElementById('popup-modal');
        const closeFeatureModal = document.getElementById('close-feature-modal');
        const buttons = document.querySelectorAll('[data-feature-not-ready]');

        buttons.forEach(button => {
            button.addEventListener('click', () => {
                featureModal.classList.remove('hidden');
            });
        });

        closeFeatureModal.addEventListener('click', () => {
            featureModal.classList.add('hidden');
        });
    });
</script>

<?php
require_once './layout/bottom.php';
?>