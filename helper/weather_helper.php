<?php
require '/../vendor/autoload.php';

$allowedOrigins = [
   'http://botaniq.test',
   'https://botaniq.cogarden.app',
   'http://botaniq.cogarden.app'
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowedOrigins)) {
   header('Access-Control-Allow-Origin: ' . $origin);
}
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Max-Age: 86400');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
   header('HTTP/1.1 204 No Content');
   exit();
}

header('Content-Type: application/json');

if (!isset($_GET['lat']) || !isset($_GET['lon'])) {
   http_response_code(400);
   echo json_encode(['error' => 'Missing parameters']);
   exit;
}

$lat = filter_var($_GET['lat'], FILTER_VALIDATE_FLOAT);
$lon = filter_var($_GET['lon'], FILTER_VALIDATE_FLOAT);

if ($lat === false || $lon === false) {
   http_response_code(400);
   echo json_encode(['error' => 'Invalid coordinates']);
   exit;
}

$apiKey = $_ENV['OPENWEATHER_API_KEY'];

if (!$apiKey) {
   http_response_code(500);
   echo json_encode(['error' => 'API key not configured']);
   exit;
}

$url = sprintf(
   'https://api.openweathermap.org/data/2.5/weather?lat=%f&lon=%f&units=metric&lang=id&appid=%s',
   $lat,
   $lon,
   $apiKey
);

$ch = curl_init();
curl_setopt_array($ch, [
   CURLOPT_URL => $url,
   CURLOPT_RETURNTRANSFER => true,
   CURLOPT_SSL_VERIFYPEER => true,
   CURLOPT_TIMEOUT => 5,
   CURLOPT_FOLLOWLOCATION => true,
   CURLOPT_MAXREDIRS => 3,
   CURLOPT_HTTPHEADER => [
       'Accept: application/json',
       'User-Agent: WeatherApp/1.0'
   ]
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
   http_response_code(500);
   echo json_encode([
       'error' => 'Failed to fetch weather data',
       'details' => curl_error($ch)
   ]);
   curl_close($ch);
   exit;
}

curl_close($ch);

if ($httpCode !== 200) {
   http_response_code($httpCode);
   echo json_encode([
       'error' => 'Weather service error',
       'status' => $httpCode
   ]);
   exit;
}

// Forward response
echo $response;