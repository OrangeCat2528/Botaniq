<?php
require_once './layout/top.php';
require_once './layout/header.php';
require_once './layout/sidebar.php';
?>

<div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
    <!-- Development Notice -->
    <div class="my-6">
        <div class="bg-gradient-to-r from-amber-400 to-orange-400 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 backdrop-blur-sm bg-white/10">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-flask text-white text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="font-bold text-white text-lg">Development Mode</h2>
                        <p class="text-white/90 text-sm">
                            AI features are currently in alpha. Use Development Server for testing.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Section -->
    <div class="my-6">
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <!-- Upload Area -->
            <div id="image-container" 
                class="group w-full aspect-video max-h-96 relative flex justify-center items-center cursor-pointer overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100"
                onclick="openFileDialog()">
                
                <!-- Upload State -->
                <div class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center transition-transform duration-300 group-hover:scale-95">
                    <div class="w-16 h-16 mb-4 bg-white rounded-2xl shadow-sm flex items-center justify-center">
                        <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl group-hover:text-blue-500 transition-colors"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Upload Image</h3>
                    <p class="text-sm text-gray-500 max-w-sm">
                        Click or drag and drop your image here for AI analysis
                    </p>
                </div>

                <!-- Drag Over State -->
                <div class="absolute inset-0 bg-blue-50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                    <div class="text-blue-500 font-medium">Drop your image here</div>
                </div>
            </div>

            <!-- Controls Section -->
            <div id="upload-section" style="display: none;" class="border-t border-gray-100">
                <div class="p-4 flex items-center justify-between gap-4">
                    <!-- Send to AI Button -->
                    <button id="send-ai-button" 
                        class="bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold py-3 px-6 rounded-xl disabled:opacity-50 disabled:cursor-not-allowed disabled:from-gray-400 disabled:to-gray-500 transition-all duration-300 hover:shadow-lg" 
                        disabled>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-robot"></i>
                            <span>Analyze with AI</span>
                        </div>
                    </button>

                    <!-- Toggle Switch -->
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="ai-checkbox" class="hidden" onchange="toggleCheckbox(this)">
                        <div class="relative">
                            <div class="w-12 h-6 bg-gray-200 rounded-full transition-colors duration-300 peer-checked:bg-green-500">
                                <div id="check-icon" class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-300"></div>
                            </div>
                        </div>
                        <span class="ml-3 text-sm font-medium text-gray-600">Enable Analysis</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Area -->
    <div id="dotted-area" class="hidden lg:mx-auto lg:w-2/3 mb-12 rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50/50 p-6">
        <!-- Content will be filled by API -->
    </div>
</div>

<script src="js/ai.js?v=5"></script>
<script src="js/animation.js?v=4"></script>

<style>
/* Custom Animations */
@keyframes pulse-border {
    0%, 100% { border-color: rgba(59, 130, 246, 0.5); }
    50% { border-color: rgba(59, 130, 246, 1); }
}

.drag-over {
    animation: pulse-border 2s infinite;
}

/* Toggle Switch Animation */
#ai-checkbox:checked ~ div #check-icon {
    transform: translateX(1.5rem);
}
</style>

<?php require_once './layout/bottom.php'; ?>
</body>