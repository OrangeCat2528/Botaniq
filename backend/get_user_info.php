<?php
require "../helper/connection.php";

// Add CORS headers
header("Access-Control-Allow-Origin: *"); // Allows all origins, adjust '*' to a specific domain if necessary
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Allow GET, POST, and OPTIONS methods
header("Access-Control-Allow-Headers: Content-Type"); // Allow Content-Type headers

// Ensure the request is a GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Assuming user ID is passed via GET query parameter, e.g., ?user_id=1
    $userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

    // If user ID is not provided, return an error response
    if ($userId === 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid user ID'
        ]);
        exit;
    }

    // Query the database to get the linked_id for the user
    if ($connection) {
        $stmt = $connection->prepare("SELECT linked_id FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($linked_id);
        $stmt->fetch();
        $stmt->close();

        // If linked_id is found, return it as JSON
        if ($linked_id !== null) {
            echo json_encode([
                'status' => 'success',
                'linked_id' => $linked_id
            ]);
        } else {
            // If no linked_id is found, return a message
            echo json_encode([
                'status' => 'error',
                'message' => 'No linked_id found for this user.'
            ]);
        }
    } else {
        // Return an error if the database connection fails
        echo json_encode([
            'status' => 'error',
            'message' => 'Database connection failed.'
        ]);
    }
} else {
    // Handle unsupported request methods
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}
?>
