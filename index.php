<?php
// index.php
require_once 'helper/connection.php';
require_once 'helper/auth_helper.php';

// Priority 1: Check Maintenance Mode
$query = "SELECT is_maintenance FROM maintenance_mode ORDER BY id DESC LIMIT 1";
$result = mysqli_query($connection, $query);
$maintenance = mysqli_fetch_assoc($result);

if ($maintenance && $maintenance['is_maintenance']) {
    header('Location: maintenance');
    exit();
}

// Priority 2: Check Authentication
$auth = AuthHelper::getInstance();

if (!$auth->isLogged()) {
    header('Location: auth/login.php');
    exit();
}

// Priority 3: Check User Data
$currentUser = $auth->getCurrentUser();
if (!$currentUser) {
    $auth->logout();
    header('Location: auth/login.php');
    exit();
}

// Priority 4: Check Device Linking
if ($currentUser['linked_id'] === null || $currentUser['linked_id'] == 0) {
    header('Location: device/link.php');
    exit();
}

// All checks passed, redirect to dashboard
header('Location: dashboard');
exit();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
    <link rel="manifest" href="/manifest.json">
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/PWA/service-worker.js')
                .catch(error => console.error('SW registration failed:', error));
        }
    </script>
</head>
<body>
    <noscript>
        <p>JavaScript is required. Please enable JavaScript or <a href="auth/login">click here</a> to log in.</p>
    </noscript>
</body>
</html>