<?php
require "../helper/connection.php";

// Atur zona waktu ke WIB
date_default_timezone_set('Asia/Jakarta');

// Ambil data dari URL parameter (GET request)
$data0 = $_GET['data0'];
$data1 = $_GET['data1'];
$data2 = $_GET['data2'];
$data3 = $_GET['data3'];
$data4 = date('Y-m-d H:i:s'); // Format waktu sesuai dengan format MySQL

// Siapkan statement SQL
$stmt = $connection->prepare("INSERT INTO datastream (product_id, temp, humidity, soil, waktu) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $data0, $data1, $data2, $data3, $data4);

// Eksekusi statement
if ($stmt->execute()) {
    echo "Data tersimpan!";
} else {
    echo "Error: " . $stmt->error;
}

// Tutup statement
$stmt->close();

// Tutup koneksi
$connection->close();
