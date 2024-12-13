<?php
require "../helper/connection.php";
session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_SESSION['login']['id'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'User is not logged in.'
        ]);
        exit;
    }

    $userId = $_SESSION['login']['id'];

    if ($connection) {
        $stmt = $connection->prepare("SELECT linked_id FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($linked_id);
        $stmt->fetch();
        $stmt->close();

        if ($linked_id !== null) {
            $stmt = $connection->prepare("SELECT id, temp, humidity, soil, watertank, waktu, product_id FROM datastream WHERE product_id = ? ORDER BY id DESC LIMIT 1");
            $stmt->bind_param("s", $linked_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            $stmt->close();

            if ($data) {
                echo json_encode($data);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'no is the same'
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
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}
?>
