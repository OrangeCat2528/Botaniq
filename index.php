<?php
require_once 'helper/connection.php';

$query = "SELECT is_maintenance FROM maintenance_mode ORDER BY id DESC LIMIT 1";
$result = mysqli_query($connection, $query);
$maintenance = mysqli_fetch_assoc($result);

if ($maintenance && $maintenance['is_maintenance']) {
    header('Location: maintenance');
    exit();
}
?>

<?php

require_once 'helper/auth_helper.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isUserLoggedIn()) {
    header('Location: auth/login.php');
    exit();
}

$currentUser = getCurrentUser();
if (!$currentUser) {
    require_once 'auth/logout.php';
    exit();
}

if ($currentUser['linked_id'] === null || $currentUser['linked_id'] === 0) {
    header('Location: device/link.php');
    exit();
}

header('Location: dashboard');
exit();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
    <script>
        // Service Worker registration
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/PWA/service-worker.js')
                .then(registration => {
                    console.log('Service Worker registered with scope:', registration.scope);
                })
                .catch(error => {
                    console.error('Service Worker registration failed:', error);
                });
        }
    </script>
    <link rel="manifest" href="/manifest.json">
</head>
<body>
    <noscript>
        <p>If you are seeing this, JavaScript is disabled or the redirection failed. Please enable JavaScript or click 
            <a href="auth/login">here</a> to log in.</p>
    </noscript>
</body>
</html>
