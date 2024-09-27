<?php
require_once './helper/connection.php'; // Make sure to include the correct connection file
require_once './layout/top.php';
require_once './layout/header.php';
require_once './layout/sidebar.php';

$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
if (!empty($searchQuery)) {
  $query = "SELECT * FROM articles WHERE judul LIKE ? OR isian LIKE ? ORDER BY id DESC";
  $stmt = mysqli_prepare($connection, $query);

  if ($stmt === false) {
    die('Error in SQL statement preparation: ' . mysqli_error($connection));
  }
  $likeSearch = '%' . $searchQuery . '%';
  mysqli_stmt_bind_param($stmt, 'ss', $likeSearch, $likeSearch);
  if (!mysqli_stmt_execute($stmt)) {
    die('Error executing SQL query: ' . mysqli_stmt_error($stmt));
  }

  $result = mysqli_stmt_get_result($stmt);
} else {
  $query = "SELECT * FROM articles ORDER BY id DESC LIMIT 20"; // 20 artikel
  $result = mysqli_query($connection, $query);
  if (!$result) {
    die('Error executing SQL query: ' . mysqli_error($connection));
  }
}
?>

<!-- Search Bar Section -->
<div class="m-5">
  <form action="articles" method="GET" class="flex justify-center">
    <div class="relative w-full max-w-lg">
      <input type="text" name="search" class="w-full p-4 pr-12 text-sm border-gray-200 rounded-3xl shadow-md focus:ring-0 focus:outline-none focus:border-gray-300" placeholder="Search..." value="<?php echo htmlspecialchars($searchQuery); ?>">
      <button type="submit" class="absolute inset-y-0 right-4 flex items-center">
        <i class="fas fa-search text-gray-400"></i>
      </button>
    </div>
  </form>
</div>

<?php
if ($result && mysqli_num_rows($result) > 0) {
  while ($article = mysqli_fetch_assoc($result)) {
    $articleTitle = $article['judul'];
    $articleContent = $article['isian'];
    $articleUuid = $article['uuid'];
    $contentWords = explode(' ', $articleContent);
    $excerpt = implode(' ', array_slice($contentWords, 0, 40)) . '...';
    $readMoreLink = "https://botaniq.app/viewarticles?arc=" . urlencode($articleUuid);
?>

    <!-- Dynamic Article Section -->
    <div class="m-5 bg-white rounded-3xl shadow-lg text-left">
      <div class="p-5">
        <p class="text-base md:text-base text-gray-700 font-bold"><?php echo htmlspecialchars($articleTitle); ?></p> <!-- Display the dynamic article title -->
        <p class="text-sm md:text-lg text-gray-700 leading-relaxed"><?php echo htmlspecialchars($excerpt); ?></p> <!-- Display the first 40 words of the article content -->
        <a class="text-sm md:text-lg text-gray-700 leading-relaxed underline" href="<?php echo htmlspecialchars($readMoreLink); ?>">Read More</a> <!-- Dynamic Read More link -->
      </div>
    </div>


<?php
  }
} else {
  echo "<p class='text-center p-5'>No articles found matching your search query.</p>";
}
?>

<div class="invisible h-32"></div>
<?php
require_once './layout/bottom.php';
?>

</body>

</html>