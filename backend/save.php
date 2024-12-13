<?php
require "../helper/connection.php";

date_default_timezone_set('Asia/Jakarta');

$data0 = $_GET['data0'];
$data1 = $_GET['data1'];
$data2 = $_GET['data2'];
$data3 = $_GET['data3'];
$data4 = date('Y-m-d H:i:s');

if (isset($_GET['watertank'])) {
    $watertank = $_GET['watertank'];
} else {
    $watertank = 0; 
}

$stmt = $connection->prepare("INSERT INTO datastream (id, temp, humidity, soil, watertank, waktu, product_id) VALUES (NULL, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssiss", $data1, $data2, $data3, $watertank, $data4, $data0);

if ($stmt->execute()) {
    echo "Data tersimpan!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();

$connection->close();
?>
