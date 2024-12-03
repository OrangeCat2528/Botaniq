<?php
require_once './layout/top.php';
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
        <button id="send-to-ai-btn" class="flex items-center bg-blue-500 text-white font-bold py-2 px-4 rounded-3xl cursor-pointer hover:bg-blue-600 transition-colors" onclick="uploadImage()" disabled>
          <i class="fas fa-robot mr-2"></i> Send to AI
        </button>

        <!-- Checkbox -->
        <div class="flex items-center">
          <label for="ai-checkbox" class="flex items-center text-sm text-gray-600 cursor-pointer">
            <input id="ai-checkbox" type="checkbox" class="hidden" onchange="toggleCheckbox(this)" />
            <div class="w-6 h-6 border-2 border-gray-500 rounded-lg flex justify-center items-center mr-2 relative">
              <div class="absolute w-3 h-3 bg-green-500 rounded-full hidden" id="check-icon"></div>
            </div>
            <span class="text-sm">Sent Data</span>
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

<div class="lg:mx-auto lg:w-1/2 mb-44 m-5 p-5 rounded-3xl border-4 border-dotted border-gray-500">
  This plant in the image looks healthy and vibrant! The leaves are a bright, rich green, indicating that it is getting the right amount of sunlight and nutrients. The soil looks well-maintained, and the pot is spacious enough for the roots to grow. Overall, it seems to be thriving, with strong, upright growth. A happy plant, indeed!
  <div class="invisible h-5"></div>
</div>

<script>
  let fileToUpload = null;

  function handleImageUploadFromModal(event) {
    const file = event.target.files[0];
    const imageContainer = document.getElementById('image-container');
    const noImageText = document.getElementById('no-image-text');
    const uploadSection = document.getElementById('upload-section');
    const sendToAIButton = document.getElementById('send-to-ai-btn');

    if (file) {
      fileToUpload = file;
      const reader = new FileReader();

      reader.onload = function(e) {
        const img = new Image();
        img.src = e.target.result;

        img.onload = function() {
          imageContainer.innerHTML = ''; // Remove the "No Image Selected" text
          imageContainer.style.backgroundColor = 'transparent'; // Remove gray background

          imageContainer.appendChild(img);
          img.style.position = 'absolute';
          img.style.top = '50%';
          img.style.left = '50%';
          img.style.transform = 'translate(-50%, -50%)';
          img.style.objectFit = 'cover';
          img.style.width = '100%';
          img.style.height = '100%';

          uploadSection.style.display = 'block';
        };
      };

      reader.readAsDataURL(file);
    } else {
      imageContainer.innerHTML = '<span id="no-image-text" class="text-gray-600 font-bold">Click to Upload</span>';
      imageContainer.style.backgroundColor = '#e0e0e0';
    }

    closeFileDialog();
  }

  function openFileDialog() {
    document.getElementById('file-upload-modal').style.display = 'flex';
  }

  function closeFileDialog() {
    document.getElementById('file-upload-modal').style.display = 'none';
  }

  function toggleCheckbox(checkbox) {
    const checkIcon = document.getElementById('check-icon');
    if (checkbox.checked) {
      checkIcon.classList.remove('hidden');
    } else {
      checkIcon.classList.add('hidden');
    }
  }

  function uploadImage() {
    const formData = new FormData();
    formData.append('file', fileToUpload);

    const uploadProgressText = document.createElement('span');
    uploadProgressText.classList.add('absolute', 'top-0', 'left-0', 'w-full', 'text-white', 'font-bold');
    uploadProgressText.id = 'upload-progress-text';
    uploadProgressText.innerText = 'Uploading: 0%';
    document.getElementById('image-container').appendChild(uploadProgressText);

    const sendToAIButton = document.getElementById('send-to-ai-btn');
    sendToAIButton.disabled = true;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/backend/upload.php', true);

    xhr.upload.onprogress = function(e) {
      if (e.lengthComputable) {
        const percentage = Math.round((e.loaded / e.total) * 100);
        uploadProgressText.innerText = 'Uploading: ' + percentage + '%';
      }
    };

    xhr.onload = function() {
      if (xhr.status === 200) {
        const response = JSON.parse(xhr.responseText);
        if (response.status === 'success') {
          uploadProgressText.innerText = 'Upload Complete';
          setTimeout(() => {
            uploadProgressText.remove();
            sendToAIButton.disabled = false;
            sendToAIButton.classList.remove('bg-gray-500');
            sendToAIButton.classList.add('bg-blue-500');
          }, 2000);
        } else {
          uploadProgressText.innerText = 'Upload Failed';
        }
      }
    };

    xhr.send(formData);
  }
</script>

<?php
require_once './layout/bottom.php';
?>
</body>
