<?php
require_once './layout/top.php';
require_once './layout/header.php';
require_once './layout/sidebar.php';
?>

<div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
  <div class="my-6 p-6 bg-yellow-400 rounded-3xl shadow-lg text-center">
    <div class="flex items-center justify-center">
      <i class="fas fa-exclamation-triangle text-2xl mr-3"></i>
      <span class="font-bold text-xl">Warning</span>
    </div>
    <p class="mt-3 text-lg">
      AI is undergoing development. To Access Alpha AI, use Development Server!
    </p>
  </div>

  <div class="my-6">
    <div class="shadow-lg rounded-3xl overflow-hidden bg-white">
      <div id="image-container" 
           class="w-full aspect-video max-h-96 rounded-t-3xl overflow-hidden bg-gray-300 relative flex justify-center items-center text-gray-600 font-bold cursor-pointer hover:bg-gray-400 transition-colors duration-300" 
           onclick="openFileDialog()">
        <div class="flex flex-col items-center">
          <i class="fas fa-cloud-upload-alt text-4xl mb-3"></i>
          <span id="no-image-text" class="text-xl">Click to Upload</span>
        </div>
      </div>

      <div class="p-6" id="upload-section" style="display: none;">
        <div class="flex flex-wrap items-center justify-between gap-4">
          <button id="send-ai-button" 
                  class="flex items-center bg-gray-400 text-white font-bold py-3 px-6 rounded-2xl cursor-not-allowed hover:bg-gray-500 transition-colors duration-300 disabled:opacity-50" 
                  disabled>
            <i class="fas fa-robot mr-3 text-lg"></i> 
            Send to AI
          </button>

          <div class="flex items-center">
            <label for="ai-checkbox" class="flex items-center text-base text-gray-600 cursor-pointer hover:text-gray-800 transition-colors duration-300">
              <input id="ai-checkbox" type="checkbox" class="hidden" onchange="toggleCheckbox(this)" />
              <div class="w-7 h-7 border-2 border-gray-500 rounded-xl flex justify-center items-center mr-3 relative hover:border-gray-700 transition-colors duration-300">
                <div class="absolute w-4 h-4 bg-green-500 rounded-full hidden" id="check-icon"></div>
              </div>
              <span class="text-base font-medium">Send Data</span>
            </label>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="dotted-area" class="lg:mx-auto lg:w-1/2 mb-44 m-5 p-5 rounded-3xl border-4 border-dotted border-gray-500" style="display: none;">
  <!-- Konten akan diisi dari API -->
</div>

<script src="js/ai.js?v=5"></script>
<script src="js/animation.js?v=4"></script>

<?php
require_once './layout/bottom.php';
?>
</body>