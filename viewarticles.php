<?php
require_once './helper/connection.php'; // Correct relative path to connection.php
require_once './layout/top.php';
require_once './layout/header.php';
require_once './layout/sidebar.php';

// Check if UUID is provided in the URL
if (!isset($_GET['arc'])) {
    echo "No article selected.";
    exit;
}

// Retrieve the UUID from the URL
$uuid = $_GET['arc'];

// Prepare and execute SQL query to fetch the article by UUID
$query = "SELECT * FROM articles WHERE uuid = ?";
$stmt = mysqli_prepare($connection, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $uuid); // Bind UUID as a string
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Fetch the article data
    $article = mysqli_fetch_assoc($result);

    // If no article is found, handle it
    if (!$article) {
        echo "Article not found.";
        exit;
    }

    // Free the result and close the statement
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
} else {
    echo "Error fetching article.";
    exit;
}

// Replace \n with <br> to ensure line breaks are properly rendered in HTML
$formattedContent = str_replace('\n', '<br><br>', $article['isian']);
?>

<div class="m-5 p-5 bg-white rounded-3xl shadow-lg">
  <img class="rounded-3xl" src="<?php echo htmlspecialchars($article['image']); ?>" alt="Article Image">
</div>

<div class="m-5 bg-white rounded-3xl shadow-lg">
  <div class="bg-blue-500 text-white rounded-t-3xl px-3 py-2 w-full text-center flex justify-center items-center">
    <p class="text-sm md:text-base font-bold"><?php echo htmlspecialchars($article['judul']); ?></p>
  </div>
  
  <div class="p-5">
    <?php
    // Output the article content with line breaks properly handled
    echo '<p class="text-sm md:text-lg text-left text-gray-700 leading-relaxed mb-4">' . $formattedContent . '</p>';
    ?>
  </div>
</div>
<div class="invisible h-32"></div>

<?php
require_once './layout/bottom.php';
?>
