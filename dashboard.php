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

<script src="js/dom.js?v=4"></script>
<script src="js/data.js?v=12"></script>
<script src="js/cuaca.js?v=7"></script>
<script src="js/tangki-air.js?v=18"></script>
<script src="js/watertank-controller.js?v=7"></script>
<script src="js/animation.js?v=2"></script>
<link href="assets/watertank.css?v=11" rel="stylesheet"></link>

<!-- Warning Sign -->
<div class="warning-sign mx-5 mt-4">
    <div class="bg-orange-100 border-l-4 border-orange-500 rounded-2xl p-4">
        <div class="flex items-center">
            <div class="bg-orange-500/10 p-2 rounded-xl">
                <i class="fas fa-exclamation-triangle text-orange-500"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-orange-800 font-semibold">Device Offline</h3>
                <p class="text-orange-700 text-sm">Please check your device connection.</p>
            </div>
        </div>
    </div>
</div>

<!-- Avatar Container -->
<div id="avatar-container" class="mx-5 mt-4 overflow-hidden bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl border border-gray-100">
    <iframe src="/layout/avatars.php" frameborder="0" width="100%" style="height: 18vh; max-height: 230px; display: block;"></iframe>
</div>

<!-- Metrics Grid -->
<div class="grid grid-cols-3 gap-3 mx-5 mt-4">
    <!-- Temperature Card -->
    <div class="bg-white rounded-2xl border border-gray-100 p-3 relative overflow-hidden group hover:shadow-lg transition-all">
        <div class="flex flex-col h-full">
            <!-- Header -->
            <div class="flex items-center gap-2 mb-2">
                <div class="bg-blue-100 w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-temperature-high text-blue-600 text-sm"></i>
                </div>
                <span class="text-xs font-medium text-gray-500">Temp</span>
            </div>
            
            <!-- Value -->
            <div class="flex items-baseline gap-1">
                <span class="text-2xl font-bold text-blue-600" id="data1">0</span>
                <span class="text-sm font-semibold text-blue-400">°C</span>
            </div>

            <!-- Status -->
            <div class="flex items-center gap-1.5 mt-2">
                <i class="fas fa-check-circle text-green-500 text-sm" id="icon-status1"></i>
                <span class="text-[10px] text-gray-500">Normal</span>
            </div>
        </div>
    </div>

    <!-- Humidity Card -->
    <div class="bg-white rounded-2xl border border-gray-100 p-3 relative overflow-hidden group hover:shadow-lg transition-all">
        <div class="flex flex-col h-full">
            <!-- Header -->
            <div class="flex items-center gap-2 mb-2">
                <div class="bg-green-100 w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-droplet text-green-600 text-sm"></i>
                </div>
                <span class="text-xs font-medium text-gray-500">Humid</span>
            </div>
            
            <!-- Value -->
            <div class="flex items-baseline gap-1">
                <span class="text-2xl font-bold text-green-600" id="data2">0</span>
                <span class="text-sm font-semibold text-green-400">%</span>
            </div>

            <!-- Status -->
            <div class="flex items-center gap-1.5 mt-2">
                <i class="fas fa-check-circle text-green-500 text-sm" id="icon-status2"></i>
                <span class="text-[10px] text-gray-500">Good</span>
            </div>
        </div>
    </div>

    <!-- Soil Status Card -->
    <div class="bg-white rounded-2xl border border-gray-100 p-3 relative overflow-hidden group hover:shadow-lg transition-all">
        <div class="flex flex-col h-full">
            <!-- Header -->
            <div class="flex items-center gap-2 mb-2">
                <div class="bg-yellow-100 w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-seedling text-yellow-600 text-sm"></i>
                </div>
                <span class="text-xs font-medium text-gray-500">Soil</span>
            </div>
            
            <!-- Value -->
            <div class="flex items-baseline gap-1">
                <span class="text-2xl font-bold text-yellow-600" id="data3">0</span>
                <span class="text-sm font-semibold text-yellow-400">%</span>
            </div>

            <!-- Status -->
            <div class="flex items-center gap-1.5 mt-2">
                <i class="fas fa-check-circle text-green-500 text-sm" id="icon-status3"></i>
                <span class="text-[10px] text-gray-500">Good</span>
            </div>
        </div>
    </div>
</div>

<!-- Weather Container -->
<div id="weather-container" class="mx-5 mt-4 rounded-2xl shadow-sm bg-gradient-to-r from-blue-500 to-blue-600">
    <div class="px-4 py-3 flex items-center justify-between text-white">
        <div class="flex items-center gap-3">
            <div class="bg-white/20 p-2 rounded-xl">
                <i id="weather-icon" class="fas fa-cloud text-xl"></i>
            </div>
            <div>
                <div class="text-xs opacity-80">Current Weather</div>
                <div class="font-semibold" id="weather-text">Loading...</div>
            </div>
        </div>
        <i class="fas fa-location-dot opacity-60"></i>
    </div>
</div>

<!-- Water Tank Container -->
<div id="water-tank-container" class="relative mx-5 mt-4 overflow-hidden bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl border border-blue-200">
    <svg id="svg-container" xmlns="http://www.w3.org/2000/svg" class="absolute top-0 left-0 w-full h-full">
        <defs>
            <linearGradient id="waveGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                <stop offset="0%" style="stop-color: #60A5FA; stop-opacity: 0.8"/>
                <stop offset="100%" style="stop-color: #3B82F6; stop-opacity: 0.9"/>
            </linearGradient>
        </defs>
        <path id="wave-path" fill="url(#waveGradient)"></path>
    </svg>
    <div id="water-text" class="absolute inset-0 flex items-center justify-center">
        <div class="bg-white/90 backdrop-blur-sm px-4 py-2 rounded-xl shadow-sm flex items-center gap-2">
            <i class="fas fa-tint text-blue-500"></i>
            <span class="font-medium text-blue-700" id="water-percentage">--</span>
        </div>
    </div>
</div>

<!-- Latest Article Card -->
<div class="mx-5 mt-4 bg-white rounded-2xl border border-gray-100 overflow-hidden">
    <div class="bg-gradient-to-r from-green-500 to-green-600 px-4 py-3 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="bg-white/20 p-2 rounded-xl">
                <i class="fas fa-newspaper text-white"></i>
            </div>
            <div class="text-white">
                <div class="text-xs opacity-80">Latest Update</div>
                <div class="font-semibold">Featured Article</div>
            </div>
        </div>
        <i class="fas fa-star text-yellow-300"></i>
    </div>
    
    <div class="p-4">
        <h3 class="font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($articleTitle); ?></h3>
        <p class="text-gray-600 text-sm leading-relaxed mb-3"><?php echo htmlspecialchars($excerpt); ?></p>
        <a href="<?php echo htmlspecialchars($readMoreLink); ?>" class="inline-flex items-center gap-2 text-green-600 hover:text-green-700 font-medium text-sm group">
            Read More 
            <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
        </a>
    </div>
</div>

<!-- Status Bar -->
<div class="h-32"></div>
<div class="fixed bottom-28 inset-x-0 px-5">
    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-3 border border-gray-100 shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-2 text-sm">
            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
            <span class="text-gray-500">Last update:</span>
            <span id="last-waktu" class="font-medium">Connecting to IoT..</span>
        </div>
        <i class="fas fa-signal text-green-500"></i>
    </div>
</div>

<?php
require_once './layout/bottom.php';
?>