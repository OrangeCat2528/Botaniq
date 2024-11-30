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
    AI is undergoing maintenance. Please wait
  </p>
</div>

<!-- Gambar Section menggantikan Camera View -->
<div class="shadow-lg rounded-3xl max-w-xs mx-auto overflow-hidden">
  <!-- Canvas Section to show the uploaded image -->
  <div id="image-container" class="w-full h-40 mx-auto rounded-t-3xl overflow-hidden bg-gray-300 relative flex justify-center items-center text-gray-600 font-bold cursor-pointer" onclick="openFileDialog()">
    <span id="no-image-text">Click to Upload</span> <!-- Default text when no image is selected -->
  </div>

  <!-- Upload Section (hidden by default) -->
  <div class="p-4" id="upload-section" style="display: none;">
    <div class="flex items-center justify-between sm:justify-start sm:space-x-4 space-x-2">
      <!-- Send to AI Button with Robot Icon -->
      <button class="flex items-center bg-blue-500 text-white font-bold py-3 px-6 rounded-3xl cursor-pointer">
        <i class="fas fa-robot mr-2"></i> Send to AI
      </button>

      <!-- Stylish Checkbox for AI Model -->
      <div class="flex items-center space-x-2">
        <label for="ai-checkbox" class="flex items-center text-sm text-gray-600 cursor-pointer">
          <!-- Checkbox Input -->
          <input id="ai-checkbox" type="checkbox" class="hidden" onchange="toggleCheckbox(this)" />
          <!-- Custom Styled Checkbox -->
          <div class="w-6 h-6 border-2 border-gray-500 rounded-lg flex justify-center items-center mr-2 relative">
            <!-- Check Icon -->
            <div class="absolute w-3 h-3 bg-green-500 rounded-full hidden" id="check-icon"></div>
          </div>
          <span class="text-sm">Sent Data</span> <!-- Changed text here -->
        </label>
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

<div class="lg:mx-auto lg:w-1/2 mb-44 m-5 p-5 rounded-3xl border-4 border-dotted border-gray-500">
  This plant in the image looks healthy and vibrant! The leaves are a bright, rich green, indicating that it is getting the right amount of sunlight and nutrients. The soil looks well-maintained, and the pot is spacious enough for the roots to grow. Overall, it seems to be thriving, with strong, upright growth. A happy plant, indeed!
  <div class="invisible h-5"></div>
</div>

<script>
  // Handle the image upload from modal
  function handleImageUploadFromModal(event) {
    const file = event.target.files[0];
    const imageContainer = document.getElementById('image-container');
    const noImageText = document.getElementById('no-image-text');

    if (file) {
      const reader = new FileReader();

      reader.onload = function(e) {
        const img = new Image();
        img.src = e.target.result;

        img.onload = function() {
          // Clear previous text and set the background to white when an image is loaded
          imageContainer.innerHTML = ''; // Remove the "No Image Selected" text
          imageContainer.style.backgroundColor = 'transparent'; // Remove gray background

          // Append the new image
          imageContainer.appendChild(img);

          // Set the image to fit within the smaller container size
          img.style.position = 'absolute';
          img.style.top = '50%';
          img.style.left = '50%';
          img.style.transform = 'translate(-50%, -50%)';

          // Apply objectFit 'cover' to make sure the image fills the container but may be cropped
          img.style.objectFit = 'cover';  // Image will cover the container and may be cropped

          // Ensure the image fits within the container (this is the preview size)
          img.style.width = '100%';
          img.style.height = '100%' ;

          // Show the "Send to AI" section after image is selected
          document.getElementById('upload-section').style.display = 'block';

          // Auto upload image and enable checkbox
          document.getElementById('ai-checkbox').checked = true;
        };
      };

      reader.readAsDataURL(file);
    } else {
      // Reset to "No Image Selected" when no file is chosen
      imageContainer.innerHTML = '<span id="no-image-text" class="text-gray-600 font-bold">Click to Upload</span>';
      imageContainer.style.backgroundColor = '#e0e0e0'; // Gray background when no image is selected
    }

    // Close the file dialog modal after image is selected
    closeFileDialog();
  }

  // Open the file dialog modal
  function openFileDialog() {
    document.getElementById('file-upload-modal').style.display = 'flex';
  }

  // Close the file dialog modal
  function closeFileDialog() {
    document.getElementById('file-upload-modal').style.display = 'none';
  }

  // Toggle the checkbox and show/hide check icon
  function toggleCheckbox(checkbox) {
    const checkIcon = document.getElementById('check-icon');
    if (checkbox.checked) {
      checkIcon.classList.remove('hidden');
    } else {
      checkIcon.classList.add('hidden');
    }
  }
</script>

<?php
require_once './layout/bottom.php';
?>
</body>
