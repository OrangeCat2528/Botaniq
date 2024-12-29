<?php
require_once '../layout/top.php';
?>

<div class="min-h-screen flex flex-col items-center justify-center bg-gray-100 px-4">
    <!-- Logo/Icon -->
    <div class="mb-8">
        <img src="/assets/img/superapp-login-logo-only.png" alt="Logo" class="w-32 h-32 mx-auto">
    </div>

    <!-- Error Message Card -->
    <div class="bg-white rounded-3xl shadow-lg p-8 max-w-lg w-full text-center">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">
            Internal Server Error
        </h1>
        
        <div class="mb-6">
            <p class="text-gray-600 mb-2">
                Oops! Something went wrong on our servers.
            </p>
            <p class="text-gray-600">
                We're working to fix this issue. Please try again later.
            </p>
        </div>

        <!-- Error Code Display -->
        <div class="bg-gray-50 rounded-xl p-4 mb-6">
            <p class="text-sm text-gray-500">Error Code</p>
            <p class="text-lg font-bold text-gray-700">500</p>
        </div>

        <!-- Action Buttons -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <a href="/" class="bg-blue-50 rounded-xl p-4 hover:bg-blue-100 transition-colors">
                <i class="fas fa-home text-blue-500 mb-2"></i>
                <p class="text-sm font-medium text-blue-600">Go Home</p>
            </a>
            <a href="javascript:history.back()" class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition-colors">
                <i class="fas fa-arrow-left text-gray-500 mb-2"></i>
                <p class="text-sm font-medium text-gray-600">Go Back</p>
            </a>
        </div>

        <!-- Technical Details (Optional) -->
        <div class="bg-red-50 rounded-xl p-4 mb-6">
            <p class="text-sm text-red-600">
                If this problem persists, please take note of this error and report it to our support team.
            </p>
        </div>

        <!-- Help Text -->
        <div class="text-sm text-gray-500">
            <p>Need technical assistance? Contact our support team:</p>
        </div>
    </div>
</div>