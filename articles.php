<?php
require_once './helper/connection.php';
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
    $query = "SELECT * FROM articles ORDER BY id DESC LIMIT 20";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die('Error executing SQL query: ' . mysqli_error($connection));
    }
}
?>

<!-- Search Bar Section -->
<div class="px-5 py-4">
    <form action="articles" method="GET">
        <div class="relative">
            <input 
                type="text" 
                name="search" 
                class="w-full px-5 py-3.5 pr-12 bg-white rounded-2xl shadow-sm border border-gray-100 focus:border-green-500 focus:ring focus:ring-green-200 transition-all text-gray-700" 
                placeholder="Search for articles..." 
                value="<?php echo htmlspecialchars($searchQuery); ?>"
            >
            <?php if (!empty($searchQuery)): ?>
                <a href="articles" class="absolute right-14 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 p-2">
                    <i class="fas fa-times"></i>
                </a>
            <?php endif; ?>
            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 bg-green-500 text-white p-2 rounded-xl hover:bg-green-600 transition-colors">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>
</div>

<?php if (!empty($searchQuery)): ?>
<div class="px-5 pb-2 flex items-center gap-2">
    <div class="px-3 py-1.5 bg-green-100 text-green-800 rounded-xl text-sm inline-flex items-center gap-2">
        <span><?php echo mysqli_num_rows($result); ?> results for</span>
        <span class="font-semibold">"<?php echo htmlspecialchars($searchQuery); ?>"</span>
    </div>
</div>
<?php endif; ?>

<?php
if ($result && mysqli_num_rows($result) > 0) {
    while ($article = mysqli_fetch_assoc($result)) {
        $articleTitle = $article['judul'];
        $articleContent = $article['isian'];
        $articleUuid = $article['uuid'];
        $contentWords = explode(' ', $articleContent);
        $excerpt = implode(' ', array_slice($contentWords, 0, 40)) . '...';
        $readMoreLink = "https://botaniq.cogarden.app/viewarticles?arc=" . urlencode($articleUuid);
?>
        <!-- Article Card -->
        <div class="mx-5 mt-4 first:mt-2 bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-md transition-all">
            <div class="flex items-stretch">
                <!-- Article Icon Column -->
                <div class="w-2 bg-gradient-to-b from-green-500 to-emerald-600"></div>
                
                <!-- Content Column -->
                <div class="flex-1 p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-leaf text-green-600"></i>
                        </div>
                        <span class="text-xs font-medium text-gray-500">Botaniq Guide</span>
                    </div>

                    <h3 class="font-bold text-gray-800 mb-2 text-lg leading-snug">
                        <?php echo htmlspecialchars($articleTitle); ?>
                    </h3>
                    
                    <p class="text-gray-600 text-sm leading-relaxed mb-4">
                        <?php echo htmlspecialchars($excerpt); ?>
                    </p>

                    <div class="flex items-center justify-between mt-auto pt-2 border-t border-gray-50">
                        <a href="<?php echo htmlspecialchars($readMoreLink); ?>" 
                           class="inline-flex items-center gap-2 text-green-600 hover:text-green-700 font-semibold text-sm group">
                            Continue Reading
                            <i class="fas fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                        </a>

                        <div class="flex items-center gap-1">
                            <button class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-50">
                                <i class="far fa-bookmark"></i>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-50">
                                <i class="far fa-share-square"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
} else {
?>
    <!-- No Results State -->
    <div class="px-5 py-8">
        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-search text-gray-400 text-xl"></i>
                </div>
                <div class="text-left">
                    <h3 class="text-gray-800 font-bold mb-1">No articles found</h3>
                    <p class="text-gray-500 text-sm">Try adjusting your search or check our latest articles</p>
                </div>
            </div>
            <div class="mt-4 flex justify-start">
                <a href="articles" class="inline-flex items-center gap-2 text-green-600 hover:text-green-700 font-semibold">
                    <i class="fas fa-arrow-left text-xs"></i>
                    View All Articles
                </a>
            </div>
        </div>
    </div>
<?php
}
?>

<div class="h-48"></div>

<?php require_once './layout/bottom.php'; ?>