<?php
require_once './helper/auth_helper.php';

$auth = AuthHelper::getInstance();

if (!$auth->isLogged()) {
    header('Location: ../auth/login.php');
    exit();
}

$currentUser = $auth->getCurrentUser();
if (!$currentUser) {
    $auth->logout();
    header('Location: ../auth/login.php');
    exit();
}

// Refresh token if needed
$auth->refreshTokenIfNeeded();

$page = basename($_SERVER['PHP_SELF'], ".php");
$headerTitles = [
    'ai' => 'Botaniq | AI',
    'dashboard' => 'Botaniq | Home',
    'articles' => 'Botaniq | Articles',
    'table' => 'Botaniq | Data',
    'charts' => 'Botaniq | Charts',
    'viewarticles' => 'Botaniq SuperApp',
    'profile' => 'My Profile'
];

$headerTitle = $headerTitles[$page] ?? "Botaniq SuperApp";
?>

<div class="text-center py-5 mb-5 shadow-md text-gray-600 bg-white">
    <button id="sidebar-toggle" class="absolute left-5 items-center fa fa-user-circle text-gray text-3xl"></button>
    <img src="/assets/img/superapp-login-logo-only.png" alt="Botaniq SuperApp Logo" class="inline-block w-8 h-8" id="header-icon">
    <span class="font-extrabold text-lg ml-1"><?= htmlspecialchars($headerTitle) ?></span>
</div>