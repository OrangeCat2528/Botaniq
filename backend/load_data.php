<?php
// linked_data.php
require "../helper/auth_helper.php";

$auth = AuthHelper::getInstance();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!$auth->isLogged()) {
        echo json_encode([
            'status' => 'error',
            'message' => 'User is not logged in.'
        ]);
        exit;
    }

    $currentUser = $auth->getCurrentUser();
    if (!$currentUser) {
        echo json_encode([
            'status' => 'error',
            'message' => 'User data not found.'
        ]);
        exit;
    }

    // Generate dynamic dummy data for testing
    $dummyData = [
        'id' => rand(100, 999), // Random ID between 100 and 999
        'temp' => rand(200, 350) / 10, // Random temperature between 20.0 and 35.0
        'humidity' => rand(30, 90), // Random humidity between 30 and 90
        'soil' => rand(20, 70), // Random soil moisture between 20 and 70
        'watertank' => rand(50, 100), // Random water tank level between 50 and 100
        'waktu' => date('Y-m-d H:i:s'), // Current timestamp
        'product_id' => 'BBB' // Set to "BBB"
    ];

    $auth->refreshTokenIfNeeded();

    // Returning dynamic dummy data
    echo json_encode($dummyData);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}
