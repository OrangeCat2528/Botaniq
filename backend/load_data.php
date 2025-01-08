<?php
// linked_data.php
require "../helper/connection.php";
require "../helper/auth_helper.php";

$auth = AuthHelper::getInstance();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

function generateDummyData() {
    return [
        'id' => rand(100, 999),
        'temp' => rand(200, 350) / 10,
        'humidity' => rand(30, 90),
        'soil' => rand(20, 70),
        'watertank' => rand(50, 100),
        'waktu' => date('Y-m-d H:i:s'),
        'product_id' => 'DEMO'
    ];
}

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

    $linked_id = $currentUser['linked_id'];

    // Check if user is using DEMO account
    if ($linked_id === 'DEMO') {
        $auth->refreshTokenIfNeeded();
        echo json_encode(generateDummyData());
        exit;
    }

    // For non-DEMO users, proceed with database query
    if ($connection) {
        if ($linked_id !== null && $linked_id != 0) {
            try {
                $stmt = $connection->prepare("
                    SELECT id, temp, humidity, soil, watertank, waktu, product_id 
                    FROM datastream 
                    WHERE product_id = ? 
                    ORDER BY id DESC 
                    LIMIT 1
                ");
                $stmt->bind_param("s", $linked_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $data = $result->fetch_assoc();
                $stmt->close();

                if ($data) {
                    $auth->refreshTokenIfNeeded();
                    echo json_encode($data);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'No data found for this device.'
                    ]);
                }
            } catch (Exception $e) {
                error_log("Data fetch error: " . $e->getMessage());
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to fetch device data.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'No device linked to this user.'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Database connection failed.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}