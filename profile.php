<?php
require_once './layout/top.php';
require_once './helper/connection.php';
require_once './layout/header.php';
require_once './layout/sidebar.php';
?>

<div class="flex flex-col items-center min-h-screen pt-6">
    <!-- Profile Header -->
    <div class="w-full max-w-md mb-6">
        <div class="flex flex-col items-center">
            <!-- Profile Image -->
            <div class="relative mb-4">
                <div class="p-1 rounded-full bg-gradient-to-r from-blue-500 to-purple-500">
                    <img src="assets/profile.webp" alt="Profile Image" class="w-24 h-24 rounded-full object-cover border-4 border-white">
                </div>
            </div>

            <!-- User Info -->
            <div class="text-center mb-4">
                <h2 class="text-xl font-bold text-gray-800"><?= $_SESSION['login']['username'] ?></h2>
                <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-xs font-medium inline-block mt-1">Active User</span>
            </div>

            <!-- Quick Actions -->
            <div class="flex space-x-3">
                <button data-feature-not-ready class="bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold py-2 px-6 rounded-xl hover:shadow-lg transition duration-200 text-sm flex items-center gap-2">
                    <i class="fas fa-user-edit"></i>
                    Edit Profile
                </button>
                <button id="openChangePassModal" class="bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold py-2 px-6 rounded-xl hover:shadow-lg transition duration-200 text-sm flex items-center gap-2">
                    <i class="fas fa-key"></i>
                    Change Pass
                </button>
            </div>
        </div>
    </div>

    <!-- Feature Cards -->
    <div class="w-full max-w-md space-y-3 px-4">
        <!-- Devices List -->
        <button data-feature-not-ready class="w-full bg-white/60 backdrop-blur-sm hover:bg-white text-gray-700 font-semibold p-4 rounded-2xl flex items-center shadow-sm hover:shadow transition duration-200 group">
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-mobile-alt text-blue-500"></i>
            </div>
            <span class="flex-1 text-left">Devices List</span>
            <i class="fas fa-chevron-right text-gray-400"></i>
        </button>

        <!-- Manual Watering -->
        <button data-feature-not-ready class="w-full bg-white/60 backdrop-blur-sm hover:bg-white text-gray-700 font-semibold p-4 rounded-2xl flex items-center shadow-sm hover:shadow transition duration-200 group">
            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-tint text-green-500"></i>
            </div>
            <span class="flex-1 text-left">Manual Watering</span>
            <i class="fas fa-chevron-right text-gray-400"></i>
        </button>

        <!-- AI Configuration -->
        <button data-feature-not-ready class="w-full bg-white/60 backdrop-blur-sm hover:bg-white text-gray-700 font-semibold p-4 rounded-2xl flex items-center shadow-sm hover:shadow transition duration-200 group">
            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-robot text-purple-500"></i>
            </div>
            <span class="flex-1 text-left">AI Configuration</span>
            <i class="fas fa-chevron-right text-gray-400"></i>
        </button>

        <!-- Customer Support -->
        <button data-feature-not-ready class="w-full bg-white/60 backdrop-blur-sm hover:bg-white text-gray-700 font-semibold p-4 rounded-2xl flex items-center shadow-sm hover:shadow transition duration-200 group">
            <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-headset text-yellow-500"></i>
            </div>
            <span class="flex-1 text-left">Customer Support</span>
            <i class="fas fa-chevron-right text-gray-400"></i>
        </button>

        <!-- Request Replacement -->
        <button data-feature-not-ready class="w-full bg-white/60 backdrop-blur-sm hover:bg-white text-gray-700 font-semibold p-4 rounded-2xl flex items-center shadow-sm hover:shadow transition duration-200 group">
            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-exchange-alt text-red-500"></i>
            </div>
            <span class="flex-1 text-left">Request a Replacement</span>
            <i class="fas fa-chevron-right text-gray-400"></i>
        </button>
    </div>

    <!-- Logout Buttons -->
    <div class="w-full max-w-md px-4 mt-6 mb-2">
        <div class="grid grid-cols-2 gap-3">
            <a href="auth/logout" class="bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold py-3 rounded-xl hover:shadow-lg transition duration-200 text-sm flex items-center justify-center gap-2">
                <i class="fas fa-sign-out-alt"></i>
                LOG OUT
            </a>
            <a href="auth/logoutall" class="bg-gradient-to-r from-gray-700 to-gray-800 text-white font-semibold py-3 rounded-xl hover:shadow-lg transition duration-200 text-sm flex items-center justify-center gap-2">
                <i class="fas fa-power-off"></i>
                LOGOUT ALL
            </a>
        </div>
    </div>

    <!-- Version Info -->
    <div class="mt-4 mb-6">
        <div class="px-4 py-1.5 rounded-full text-gray-500 text-xs flex items-center gap-2">
            <i class="fas fa-code-branch text-blue-500"></i>
            Botaniq Alpha Version 2.0
        </div>
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
<<div id="featureNotReadyModal" class="fixed inset-0 flex items-center justify-center z-50">
<div id="featureNotReadyContent" class="bg-white rounded-3xl shadow-lg p-6 w-11/12 max-w-lg relative transform transition duration-300">
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

<script src="js/animation.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Initialize Change Password Modal
    const changePassModal = new PopupAnimation();
    changePassModal.init('changePassModal', 'changePassContent', '#openChangePassModal');
    changePassModal.addCloseTrigger('#cancelChangePass');
    
    // Initialize Feature Not Ready Modal
    const featureModal = new PopupAnimation();
    featureModal.init('featureNotReadyModal', 'featureNotReadyContent', '[data-feature-not-ready]');
    featureModal.addCloseTrigger('#close-feature-modal, .bg-green-500'); // Gabungkan selector

    // Reset form ketika change password modal ditutup
    const changePassForm = document.getElementById('changePassForm');
    const oldPasswordError = document.getElementById('oldPasswordError');
    const oldPasswordInput = document.getElementById('oldPassword');
    
    changePassModal.modal.addEventListener('transitionend', () => {
        if (changePassModal.modal.classList.contains('hidden')) {
            oldPasswordError.classList.add('hidden');
            oldPasswordInput.classList.remove('border-red-500');
            changePassForm.reset();
        }
    });
});
</script>

<?php
require_once './layout/bottom.php';
?>