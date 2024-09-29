<?php
require_once './helper/connection.php'; // Make sure to include the correct connection file
require_once './layout/top.php';
require_once './layout/header.php';
require_once './layout/sidebar.php';

// Fetch the latest article from the database (article with the highest ID)
$query = "SELECT * FROM articles ORDER BY id DESC LIMIT 1"; // Get the article with the highest ID
$result = mysqli_query($connection, $query);

// Check if an article is found
if ($result && mysqli_num_rows($result) > 0) {
    $latestArticle = mysqli_fetch_assoc($result);
    
    // Extract the title and content
    $articleTitle = $latestArticle['judul'];
    $articleContent = $latestArticle['isian'];
    $articleUuid = $latestArticle['uuid'];
    
    // Split the content into words and display the first 40 words followed by "..."
    $contentWords = explode(' ', $articleContent);
    $excerpt = implode(' ', array_slice($contentWords, 0, 40)) . '...';
    
    // Generate the URL for the "Read More" link
    $readMoreLink = "https://botaniq.cogarden.app/viewarticles?arc=" . urlencode($articleUuid);
} else {
    // If no article is found, set default values
    $articleTitle = "No articles available";
    $excerpt = "";
    $readMoreLink = "#";
}

?>

<script src="js/dom.js?v=1"></script>
<script src="js/data.js?v=9"></script>
<script src="js/ai-dash.js?v=12"></script>

<div id="avatar-container" class="m-5 p-2 bg-white rounded-xl shadow-lg flex justify-center items-center">
    <iframe src="/layout/avatars.php" frameborder="0" width="100%" style="height: 23vh; max-height: 230px; display: block;"></iframe>
</div>

<!-- Status Section (No Changes) -->
<div class="m-5 mb-6 grid grid-cols-3 gap-5">
  <!-- Temp Status -->
  <div class="bg-white shadow-md rounded-3xl p-5">
    <div class="flex justify-between items-center md:justify-center md:gap-2">
      <span class="text-3xl md:text-5xl font-bold text-blue-500" id="data1">0</span>
      <span class="text-2xl md:text-4xl font-bold text-blue-500">°C</span>
    </div>
    <i class="fas fa-check-circle text-2xl mt-1 mb-2 text-green-600" id="icon-status1"></i>
    <div class="grid items-center text-xs font-bold text-gray-700">Temp Status</div>
  </div>

  <!-- Humidity Status -->
  <div class="bg-white shadow-md rounded-3xl p-5">
    <div class="flex justify-between items-center md:justify-center md:gap-2">
      <span class="text-3xl md:text-5xl font-bold text-green-600" id="data2">0</span>
      <span class="text-2xl md:text-4xl font-bold text-green-600">%</span>
    </div>
    <i class="fas fa-check-circle text-2xl mt-1 mb-2 text-green-600" id="icon-status2"></i>
    <div class="grid items-center text-xs font-bold text-gray-700">Humidity Status</div>
  </div>

  <!-- Soil Status -->
  <div class="bg-white shadow-md rounded-3xl p-5">
    <div class="flex justify-between items-center md:justify-center md:gap-2">
      <span class="text-3xl md:text-5xl font-bold text-yellow-500" id="data3">0</span>
      <span class="text-2xl md:text-4xl font-bold text-yellow-500">%</span>
    </div>
    <i class="fas fa-check-circle text-2xl mt-1 mb-2 text-green-600" id="icon-status3"></i>
    <div class="grid items-center text-xs font-bold text-gray-700">Soil Status</div>
  </div>
</div>

<div class="m-5 bg-white rounded-3xl shadow-lg h-12 flex items-center p-0">
  <!-- Temperature Section -->
  <div class="flex justify-between items-center w-full h-full">
    <!-- Icon and Label -->
    <div class="flex items-center pl-4">
      <i class="fas fa-robot text-yellow-500 mr-2"></i>
      <span class="text-gray-600 ">What AI Said?</span>
    </div>

    <!-- ai nya boongan, ubah px-8 jadi px-4 buat optimisasi web -->
    <div class="bg-blue-500 rounded-3xl px-4 flex items-center justify-center h-full">
      <span class="text-sm md:text-lg font-bold text-white"><i id="ai-stats">Loading...</i></span>
    </div>
  </div>
</div>

<!-- Articles Section (Dynamic Content) -->
<div class="m-5 bg-white rounded-3xl shadow-lg">
  <div class="bg-green-500 text-white rounded-t-3xl px-3 py-2 w-full text-center flex justify-center items-center">
    <i class="fas fa-star text-yellow-300 mr-2"></i> 
    <p class="text-sm md:text-base font-bold">Latest Articles</p>
  </div>
  
  <div class="p-5">
    <!-- Dynamic title and content -->
    <p class="text-sm md:text-lg text-gray-700 font-bold leading-relaxed"><?php echo htmlspecialchars($articleTitle); ?></p>  
    <p class="text-sm md:text-lg text-gray-700 leading-relaxed"><?php echo htmlspecialchars($excerpt); ?></p>
    <a class="text-sm md:text-lg text-gray-700 leading-relaxed underline" href="<?php echo htmlspecialchars($readMoreLink); ?>">Read More</a>
  </div>
</div>

<div class="invisible h-32"></div>

<!-- Last Update (No Changes) -->
<div class="bg-white rounded-3xl p-2 mx-10 mt-5 shadow-md text-xs fixed bottom-28 left-0 right-0">
  <span>Last update : </span>
  <span id="last-waktu">Connecting to IoT..</span>
</div>

<?php
require_once './layout/bottom.php';
?>

</body>

</html>
