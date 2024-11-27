<?php
require "../helper/connection.php";
session_start();

header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Accept");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['data'])) {
        header('Content-Type: application/json', true, 400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing required "data" parameter.'
        ]);
        exit;
    }

    $product_id = null;
    if ($_GET['data'] === 'cattleya') {
        $product_id = 'BBB'; 
    } elseif ($_GET['data'] === 'sativa') {
        $product_id = 'AAA';
    } else {
        header('Content-Type: application/json', true, 400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid "data" parameter. Valid values are "cattleya" and "sativa".'
        ]);
        exit;
    }

    $range = isset($_GET['range']) && is_numeric($_GET['range']) ? (int)$_GET['range'] : 1;

    if ($connection) {
        $stmt = $connection->prepare("SELECT id, temp, humidity, soil, waktu, product_id FROM datastream WHERE product_id = ? ORDER BY id DESC LIMIT ?");
        $stmt->bind_param("si", $product_id, $range);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();

        if (count($data) > 0) {
            header('Content-Type: application/json', true, 200);
            echo json_encode([
                'status' => 'success',
                'data' => $data
            ]);
        } else {
            header('Content-Type: application/json', true, 404);
            echo json_encode([
                'status' => 'error',
                'message' => 'No data found for the specified product_id.'
            ]);
        }
    } else {
        header('Content-Type: application/json', true, 500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Database connection failed.'
        ]);
    }
} else {
    header('Content-Type: application/json', true, 405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method. Only GET is supported.'
    ]);
}
?>
