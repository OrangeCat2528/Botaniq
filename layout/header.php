<?php
session_start();
function isLogin()
{
    if (!isset($_SESSION['login'])) {
        header('Location: ../auth/login.php');
        exit();
    }
}
isLogin();

$page = basename($_SERVER['PHP_SELF'], ".php");

$headerTitle = "Botaniq SuperApp";
switch ($page) {
    case "ai":
        $headerTitle = "Botaniq | AI";
        break;
    case "dashboard":
        $headerTitle = "Botaniq | Home";
        break;
    case "articles":
        $headerTitle = "Botaniq | Articles";
        break;
    case "table":
        $headerTitle = "Botaniq | Data";
        break;
    case "charts":
        $headerTitle = "Botaniq | Charts";
        break;
    case "viewarticles":
        $headerTitle = "Botaniq SuperApp";
        break;
    case "profile":
        $headerTitle = "My Profile";
        break;
    default:
        $headerTitle = "Botaniq SuperApp";
        break;
}

?>

<div class="text-center py-5 mb-5 shadow-md text-gray-600 bg-white">
  <button id="sidebar-toggle" class="absolute left-5 items-center fa fa-user-circle text-gray text-3xl"></button>
  <img src="/assets/img/superapp-login-logo-only.png" alt="Botaniq SuperApp Logo" class="inline-block w-8 h-8" id="header-icon">
  <span class="font-extrabold text-lg ml-1"><?= htmlspecialchars($headerTitle) ?></span>
</div>
