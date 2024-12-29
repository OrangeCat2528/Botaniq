<?php
// helper/connection.php
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$dbhost = $_ENV['DB_HOST'];
$dbport = (int)$_ENV['DB_PORT'];
$dbusername = $_ENV['DB_USERNAME'];
$dbpassword = $_ENV['DB_PASSWORD'];
$dbname = $_ENV['DB_NAME'];

try {
    // mysqli connection
    $connection = mysqli_connect($dbhost, $dbusername, $dbpassword, $dbname);
    if (!$connection) {
        throw new Exception("Connection database failed: " . mysqli_connect_error());
    }

    // PDO connection for PHPAuth
    $dsn = "mysql:host={$dbhost};port={$dbport};dbname={$dbname};charset=utf8mb4";
    $pdo = new PDO($dsn, $dbusername, $dbpassword, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
    
} catch (Exception $e) {
    error_log($e->getMessage(), 3, __DIR__ . '/../logs/error.log');
    
    ob_start();
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Database Connection Error</title>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <style>
            body {
                font-family: 'Montserrat', sans-serif;
            }
        </style>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Connection to Core Server Error',
                text: 'Try Again Later.',
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false
            });
        </script>
    </body>
    </html>
<?php
    echo ob_get_clean();
    exit;
}

// Optional: Set timezone if needed
date_default_timezone_set('Asia/Jakarta');