<?php
require "../helper/connection.php";
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

    $linked_id = $currentUser['linked_id'];

    if ($connection) {
        if ($linked_id !== null) {
            try {
                $stmt = $connection->prepare("SELECT * FROM datastream WHERE product_id = ? ORDER BY id DESC LIMIT 20");
                $stmt->bind_param("s", $linked_id);
                $stmt->execute();
                $result = $stmt->get_result();

                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                $stmt->close();

                // Refresh token if needed
                $auth->refreshTokenIfNeeded();
                
                echo json_encode($data);
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to fetch data.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'No linked_id found for this user.'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Database connection failed.'
        ]);
    }
}

$connection->close();