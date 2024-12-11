<?php
require_once './layout/top.php';
// require_once '../helper/connection.php';
require_once './layout/header.php';
require_once './layout/sidebar.php';
?>

<div class="m-5 p-4 bg-yellow-400 rounded-3xl shadow-lg text-center flex flex-col justify-center items-center">
  <div class="flex items-center justify-center">
    <i class="fas fa-exclamation-triangle text-xl mr-2"></i>
    <span class="font-bold text-lg">Warning</span>
  </div>
  <p class="mt-2">
    AI is undergoing development. To Access Alpha AI, use Development Server!
  </p>
</div>

<!-- Upload Container -->
<div class="m-5">
  <div class="shadow-lg rounded-3xl max-w-xs mx-auto overflow-hidden">
    <!-- Canvas Section -->
    <div id="image-container" class="w-full h-40 rounded-t-3xl overflow-hidden bg-gray-300 relative flex justify-center items-center text-gray-600 font-bold cursor-pointer" onclick="openFileDialog()">
      <span id="no-image-text">Click to Upload</span>
    </div>

    <!-- Upload Section -->
    <div class="p-4" id="upload-section" style="display: none;">
      <div class="flex flex-wrap items-center justify-between gap-3">
        <!-- Send to AI Button -->
        <button id="send-ai-button" class="flex items-center bg-gray-400 text-white font-bold py-2 px-4 rounded-3xl cursor-not-allowed" disabled>
          <i class="fas fa-robot mr-2"></i> Send to AI
        </button>

        <!-- Checkbox -->
        <div class="flex items-center">
          <label for="ai-checkbox" class="flex items-center text-sm text-gray-600 cursor-pointer">
            <input id="ai-checkbox" type="checkbox" class="hidden" onchange="toggleCheckbox(this)" />
            <div class="w-6 h-6 border-2 border-gray-500 rounded-lg flex justify-center items-center mr-2 relative">
              <div class="absolute w-3 h-3 bg-green-500 rounded-full hidden" id="check-icon"></div>
            </div>
            <span class="text-sm">Send Data</span>
          </label>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal to select image -->
<div id="file-upload-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
  <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-md mx-4">
    <h3 class="text-xl font-semibold text-center mb-4">Select Image</h3>
    <input id="file-upload-modal-input" type="file" onchange="handleImageUploadFromModal(event)" class="w-full p-3 rounded-xl border border-gray-300" />
    <div class="flex justify-center mt-4">
      <button onclick="closeFileDialog()" class="bg-gray-500 text-white px-4 py-2 rounded-lg">Cancel</button>
    </div>
  </div>
</div>

<!-- Dotted Area -->
<div id="dotted-area" class="lg:mx-auto lg:w-1/2 mb-44 m-5 p-5 rounded-3xl border-4 border-dotted border-gray-500" style="display: none;">
  <!-- Konten akan diisi dari API -->
</div>

<script src="js/ai.js?v=1"></script>

<?php
require_once './layout/bottom.php';
?>
</body>
