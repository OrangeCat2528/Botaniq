<?php
require_once './helper/connection.php';
require_once './layout/top.php';
require_once './layout/header.php';
require_once './layout/sidebar.php';

if (!isset($_GET['arc'])) {
    echo "No article selected.";
    exit;
}

$uuid = $_GET['arc'];
$query = "SELECT * FROM articles WHERE uuid = ?";
$stmt = mysqli_prepare($connection, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $uuid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $article = mysqli_fetch_assoc($result);

    if (!$article) {
        echo "Article not found.";
        exit;
    }

    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
} else {
    echo "Error fetching article.";
    exit;
}

$formattedContent = str_replace('\n', '<br><br>', $article['isian']);

// Calculate read time (rough estimate)
$word_count = str_word_count(strip_tags($formattedContent));
$read_time = ceil($word_count / 200); // Assuming average reading speed of 200 words per minute
?>

<!-- Progress Bar -->
<div class="fixed top-0 left-0 w-full h-1 z-50">
    <div id="progress-bar" class="h-full bg-green-500 transition-all duration-200"></div>
</div>

<!-- Back Navigation -->
<div class="sticky top-0 bg-white/80 backdrop-blur-sm z-40 border-b border-gray-100">
    <div class="max-w-3xl mx-auto px-5 py-4 flex justify-between items-center">
        <a href="articles" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left"></i>
            <span class="text-sm font-medium">Back to Articles</span>
        </a>
        <div class="flex items-center gap-3">
            <button class="p-2 text-gray-400 hover:text-green-500 transition-colors">
                <i class="far fa-bookmark"></i>
            </button>
            <button class="p-2 text-gray-400 hover:text-green-500 transition-colors">
                <i class="fas fa-share-alt"></i>
            </button>
        </div>
    </div>
</div>

<!-- Article Container -->
<div class="max-w-3xl mx-auto px-5 py-8">
    <article class="bg-white rounded-3xl border border-gray-100 overflow-hidden">
        <!-- Article Header -->
        <div class="p-8 pb-0">
            <!-- Category & Meta -->
            <div class="flex flex-wrap items-center gap-4 text-sm mb-6">
                <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full font-medium">
                    Botaniq Guide
                </span>
                <div class="flex items-center gap-2 text-gray-500">
                    <i class="fas fa-calendar-alt"></i>
                    <span><?php echo date('M d, Y', strtotime($article['created_at'] ?? 'now')); ?></span>
                </div>
                <div class="flex items-center gap-2 text-gray-500">
                    <i class="fas fa-clock"></i>
                    <span><?php echo $read_time; ?> min read</span>
                </div>
            </div>

            <!-- Title -->
            <h1 class="text-3xl font-bold text-gray-800 mb-6 leading-tight">
                <?php echo htmlspecialchars($article['judul']); ?>
            </h1>

            <!-- Author Info -->
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fas fa-user text-green-500"></i>
                </div>
                <div>
                    <div class="font-medium text-gray-800">Botaniq Team</div>
                    <div class="text-sm text-gray-500">Plant Care Expert</div>
                </div>
            </div>
        </div>

        <!-- Featured Image -->
        <?php if (!empty($article['image'])): ?>
        <div class="w-full aspect-video mb-8 overflow-hidden">
            <img class="w-full h-full object-cover" 
                 src="<?php echo htmlspecialchars($article['image']); ?>" 
                 alt="Article featured image">
        </div>
        <?php endif; ?>

        <!-- Article Content -->
        <div class="px-8 pb-8">
            <div class="prose max-w-none">
                <div class="text-gray-600 text-lg leading-relaxed space-y-6">
                    <?php echo $formattedContent; ?>
                </div>
            </div>
        </div>
    </article>

    <!-- Table of Contents Sidebar -->
    <div class="hidden lg:block fixed top-32 right-8 w-64 p-6 bg-white rounded-2xl border border-gray-100">
        <div class="text-sm font-medium text-gray-500 mb-4">TABLE OF CONTENTS</div>
        <nav class="space-y-3 text-sm" id="toc">
            <!-- Dynamically generated -->
        </nav>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-between mt-8 pb-4 border-b border-gray-100">
        <div class="flex items-center gap-4">
            <!-- Like Button -->
            <button class="flex items-center gap-2 text-gray-500 hover:text-green-500 transition-colors">
                <i class="far fa-heart"></i>
                <span class="text-sm font-medium">123</span>
            </button>
            <!-- Comment Button -->
            <button class="flex items-center gap-2 text-gray-500 hover:text-green-500 transition-colors">
                <i class="far fa-comment"></i>
                <span class="text-sm font-medium">45</span>
            </button>
        </div>
        <!-- Share Buttons -->
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-500">Share:</span>
            <button class="w-8 h-8 rounded-full bg-blue-100 text-blue-500 flex items-center justify-center hover:bg-blue-500 hover:text-white transition-colors">
                <i class="fab fa-facebook-f"></i>
            </button>
            <button class="w-8 h-8 rounded-full bg-blue-100 text-blue-400 flex items-center justify-center hover:bg-blue-400 hover:text-white transition-colors">
                <i class="fab fa-twitter"></i>
            </button>
            <button class="w-8 h-8 rounded-full bg-green-100 text-green-500 flex items-center justify-center hover:bg-green-500 hover:text-white transition-colors">
                <i class="fab fa-whatsapp"></i>
            </button>
        </div>
    </div>

    <!-- Related Articles -->
    <div class="mt-8">
        <h2 class="text-xl font-bold text-gray-800 mb-6">More Articles</h2>
        <div class="grid gap-6">
            <?php
            $related_query = "SELECT * FROM articles WHERE uuid != ? ORDER BY RAND() LIMIT 2";
            $stmt = mysqli_prepare($connection, $related_query);
            mysqli_stmt_bind_param($stmt, "s", $uuid);
            mysqli_stmt_execute($stmt);
            $related_result = mysqli_stmt_get_result($stmt);
            
            while ($related = mysqli_fetch_assoc($related_result)) {
                $readMoreLink = "viewarticles?arc=" . urlencode($related['uuid']);
                ?>
                <a href="<?php echo $readMoreLink; ?>" 
                   class="group bg-white rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition-all">
                    <div class="flex items-start gap-6">
                        <div class="w-20 h-20 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-green-100 transition-colors">
                            <i class="fas fa-leaf text-green-500 text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-800 mb-2 group-hover:text-green-500 transition-colors">
                                <?php echo htmlspecialchars($related['judul']); ?>
                            </h3>
                            <p class="text-gray-500 text-sm line-clamp-2">
                                <?php 
                                $excerpt = strip_tags(str_replace('\n', ' ', $related['isian']));
                                echo htmlspecialchars(substr($excerpt, 0, 150)) . '...'; 
                                ?>
                            </p>
                            <div class="flex items-center gap-4 mt-4 text-sm text-gray-400">
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-clock"></i>
                                    <?php echo ceil(str_word_count(strip_tags($related['isian'])) / 200); ?> min read
                                </span>
                                <span class="flex items-center gap-1">
                                    <i class="far fa-heart"></i>
                                    <?php echo rand(10, 100); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            <?php } ?>
        </div>
    </div>
</div>

<!-- Bottom Spacing -->
<div class="h-24"></div>

<style>
.prose {
    font-size: 1.125rem;
    line-height: 1.8;
    color: #374151;
}

.prose p {
    margin-bottom: 2em;
}

.prose h2 {
    font-size: 1.5em;
    color: #1f2937;
    font-weight: 700;
    margin: 2em 0 1em;
}

.prose h3 {
    font-size: 1.25em;
    color: #1f2937;
    font-weight: 600;
    margin: 1.5em 0 1em;
}

.prose ul, .prose ol {
    margin: 1.5em 0;
    padding-left: 1.5em;
}

.prose li {
    margin: 0.5em 0;
}

.prose img {
    border-radius: 0.75rem;
    margin: 2em 0;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Progress bar animation */
@keyframes progress {
    from { width: 0%; }
    to { width: 100%; }
}

#progress-bar {
    width: 0%;
    transition: width 0.1s;
}
</style>

<script>
// Reading progress bar
document.addEventListener('DOMContentLoaded', function() {
    const progressBar = document.getElementById('progress-bar');
    
    window.addEventListener('scroll', () => {
        const winScroll = document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;
        progressBar.style.width = scrolled + '%';
    });

    // Generate Table of Contents
    const content = document.querySelector('.prose');
    const toc = document.getElementById('toc');
    if (content && toc) {
        const headers = content.querySelectorAll('h2, h3');
        headers.forEach((header, index) => {
            const id = `section-${index}`;
            header.id = id;
            const link = document.createElement('a');
            link.href = `#${id}`;
            link.className = `block ${header.tagName === 'H2' ? 'text-gray-800' : 'text-gray-500 pl-4'} hover:text-green-500 transition-colors`;
            link.textContent = header.textContent;
            toc.appendChild(link);
        });
    }
});
</script>

<?php require_once './layout/bottom.php'; ?>