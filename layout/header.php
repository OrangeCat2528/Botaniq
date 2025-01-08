<?php
require_once './helper/auth_helper.php';

$auth = AuthHelper::getInstance();

if (!$auth->isLogged()) {
    header('Location: ../auth/login');
    exit();
}

$currentUser = $auth->getCurrentUser();
if (!$currentUser) {
    $auth->logout();
    header('Location: ../auth/login');
    exit();
}

// Refresh token if needed
$auth->refreshTokenIfNeeded();

$page = basename($_SERVER['PHP_SELF'], ".php");
$headerTitles = [
    'ai' => 'AI Assistant',
    'dashboard' => 'My Garden',
    'articles' => 'Knowledge Base',
    'table' => 'Garden Data',
    'charts' => 'Analytics',
    'viewarticles' => 'Article View',
    'profile' => 'My Profile'
];

$headerTitle = $headerTitles[$page] ?? "Botaniq";
?>

<!-- Header -->
<header class="bg-white relative">
    <!-- Top Header -->
    <div class="flex items-center justify-between px-5 py-4">
        <!-- Left Menu Button -->
        <button id="sidebar-toggle" class="w-10 h-10 flex items-center justify-center rounded-xl text-gray-600 hover:bg-gray-50">
            <i class="fas fa-bars text-xl"></i>
        </button>

        <!-- Center Logo and Title -->
        <div class="flex items-center gap-2">
            <div class="relative">
                <div class="absolute inset-0 bg-green-100 rounded-lg rotate-45"></div>
                <img src="/assets/img/superapp-login-logo-only.png" alt="Botaniq SuperApp Logo" 
                     class="w-8 h-8 relative z-10" id="header-icon">
            </div>
            <h1 class="font-bold text-gray-700">
                <?= htmlspecialchars($headerTitle) ?>
            </h1>
        </div>

        <!-- Right Notification Button -->
        <button id="sidebar-notif" class="w-10 h-10 flex items-center justify-center rounded-xl text-gray-600 hover:bg-gray-50 relative">
            <i class="fas fa-bell text-xl"></i>
            <div class="absolute top-1 right-1.5 w-2 h-2 bg-red-500 rounded-full"></div>
        </button>
    </div>
</header>

<!-- Subtle Shadow Line -->
<div class="h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>