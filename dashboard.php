<?php
require_once './helper/connection.php';
require_once './layout/top.php';
require_once './layout/header.php';
require_once './layout/sidebar.php';

$query = "SELECT * FROM articles ORDER BY id DESC LIMIT 1";
$result = mysqli_query($connection, $query);

// Check if an article is found
if ($result && mysqli_num_rows($result) > 0) {
    $latestArticle = mysqli_fetch_assoc($result);
    $articleTitle = $latestArticle['judul'];
    $articleContent = $latestArticle['isian'];
    $articleUuid = $latestArticle['uuid'];
    $contentWords = explode(' ', $articleContent);
    $excerpt = implode(' ', array_slice($contentWords, 0, 40)) . '...';
    $readMoreLink = "https://botaniq.cogarden.app/viewarticles?arc=" . urlencode($articleUuid);
} else {
    $articleTitle = "No articles available";
    $excerpt = "";
    $readMoreLink = "#";
}
?>

<script src="js/dom.js?v=2"></script>
<script src="js/data.js?v=11"></script>
<script src="js/notifier.js?v=1"></script>
<script src="js/cuaca.js?v=5"></script>
<script src="js/tangki-air.js?v=17"></script>
<script src="js/watertank-controller.js?v=5"></script>
<link href="assets/watertank.css?v=11" rel="stylesheet"></link>

<div class="warning-sign m-5 p-4 bg-yellow-400 rounded-3xl shadow-lg text-center flex flex-col justify-center items-center hidden">
  <div class="flex items-center justify-center">
    <i class="fas fa-exclamation-triangle text-xl mr-2"></i>
    <span class="font-bold text-lg">Warning</span>
  </div>
  <p class="mt-2">
    Pot is Offline, Check Device Connection.
  </p>
</div>

<div id="avatar-container" class="m-5 p-2 bg-white rounded-xl shadow-lg flex justify-center items-center">
    <iframe src="/layout/avatars.php" frameborder="0" width="100%" style="height: 18vh; max-height: 230px; display: block;"></iframe>
</div>


<div class="m-5 mb-6 grid grid-cols-3 gap-5">
  <div class="bg-white shadow-md rounded-3xl p-5">
    <div class="flex justify-between items-center md:justify-center md:gap-2">
      <span class="text-3xl md:text-5xl font-bold text-blue-500" id="data1">0</span>
      <span class="text-2xl md:text-4xl font-bold text-blue-500">°C</span>
    </div>
    <i class="fas fa-check-circle text-2xl mt-1 mb-2 text-green-600" id="icon-status1"></i>
    <div class="grid items-center text-xs font-bold text-gray-700">Temp Status</div>
  </div>
  <div class="bg-white shadow-md rounded-3xl p-5">
    <div class="flex justify-between items-center md:justify-center md:gap-2">
      <span class="text-3xl md:text-5xl font-bold text-green-600" id="data2">0</span>
      <span class="text-2xl md:text-4xl font-bold text-green-600">%</span>
    </div>
    <i class="fas fa-check-circle text-2xl mt-1 mb-2 text-green-600" id="icon-status2"></i>
    <div class="grid items-center text-xs font-bold text-gray-700">Humidity Status</div>
  </div>
  <div class="bg-white shadow-md rounded-3xl p-5">
    <div class="flex justify-between items-center md:justify-center md:gap-2">
      <span class="text-3xl md:text-5xl font-bold text-yellow-500" id="data3">0</span>
      <span class="text-2xl md:text-4xl font-bold text-yellow-500">%</span>
    </div>
    <i class="fas fa-check-circle text-2xl mt-1 mb-2 text-green-600" id="icon-status3"></i>
    <div class="grid items-center text-xs font-bold text-gray-700">Soil Status</div>
  </div>
</div>

<div id="weather-container" class="m-5 rounded-3xl p-2 flex justify-center items-center text-white text-center" style="height: 50px; background: linear-gradient(to right, #ccc, #fff);">
  <i id="weather-icon" class="fas fa-cloud text-xl mr-2"></i>
  <span class="text-sm font-bold" id="weather-text">Loading...</span>
</div>

<div id="water-tank-container" class="relative mx-auto overflow-hidden bg-gray-100 rounded-3xl">
  <svg id="svg-container" xmlns="http://www.w3.org/2000/svg" class="absolute top-0 left-0 w-full h-full">
    <path id="wave-path" class="fill-blue-500"></path>
  </svg>
  <div id="water-text" class="absolute inset-0 flex items-center justify-center text-sm font-bold text-blue-500">
    <i class="fas fa-tint mr-2"></i>
    <span id="water-percentage"></span>
  </div>
</div>

<div class="m-5 bg-white rounded-3xl shadow-lg">
  <div class="bg-green-500 text-white rounded-t-3xl px-3 py-2 w-full text-center flex justify-center items-center">
    <i class="fas fa-star text-yellow-300 mr-2"></i> 
    <p class="text-sm md:text-base font-bold">Latest Articles</p>
  </div>
  
  <div class="p-5">
    <p class="text-sm md:text-lg text-gray-700 font-bold leading-relaxed"><?php echo htmlspecialchars($articleTitle); ?></p>  
    <p class="text-sm md:text-lg text-gray-700 leading-relaxed"><?php echo htmlspecialchars($excerpt); ?></p>
    <a class="text-sm md:text-lg text-gray-700 leading-relaxed underline" href="<?php echo htmlspecialchars($readMoreLink); ?>">Read More</a>
  </div>
</div>

<div class="invisible h-32"></div>
<div class="bg-white rounded-3xl p-2 mx-10 mt-5 shadow-md text-xs fixed bottom-28 left-0 right-0">
  <span>Last update : </span>
  <span id="last-waktu">Connecting to IoT..</span>
</div>

<?php
require_once './layout/bottom.php';
?>