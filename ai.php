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

<script>
function handleImageUploadFromModal(event) {
    const file = event.target.files[0];
    const imageContainer = document.getElementById('image-container');
    const noImageText = document.getElementById('no-image-text');
    const sendButton = document.getElementById('send-ai-button');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = new Image();
            img.src = e.target.result;

            img.onload = function() {
                imageContainer.innerHTML = '';
                imageContainer.style.backgroundColor = 'transparent';
                imageContainer.appendChild(img);

                img.style.position = 'absolute';
                img.style.top = '50%';
                img.style.left = '50%';
                img.style.transform = 'translate(-50%, -50%)';
                img.style.objectFit = 'cover';
                img.style.width = '100%';
                img.style.height = '100%';

                document.getElementById('upload-section').style.display = 'block';
                startUpload(file, imageContainer, sendButton);
            };
        };
        reader.readAsDataURL(file);
    } else {
        imageContainer.innerHTML = '<span id="no-image-text" class="text-gray-600 font-bold">Click to Upload</span>';
        imageContainer.style.backgroundColor = '#e0e0e0';
        document.getElementById('upload-section').style.display = 'none';
    }

    closeFileDialog();
}

function startUpload(file, imageContainer, sendButton) {
    sendButton.disabled = true;
    sendButton.classList.add('bg-gray-400', 'cursor-not-allowed');

    const percentageOverlay = document.createElement('div');
    percentageOverlay.className = 'absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center text-white text-2xl font-bold z-10';
    percentageOverlay.id = 'upload-percentage';
    percentageOverlay.textContent = '0%';
    imageContainer.appendChild(percentageOverlay);

    const formData = new FormData();
    formData.append('file', file);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'https://upload.botaniq.cogarden.app/upload', true);

    xhr.upload.onprogress = function (e) {
        if (e.lengthComputable) {
            const percentage = Math.round((e.loaded * 100) / e.total);
            document.getElementById('upload-percentage').textContent = percentage + '%';
        }
    };

    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response && response.link) {
                document.getElementById('upload-percentage').remove();
                sendButton.disabled = false;
                sendButton.classList.remove('bg-gray-400', 'cursor-not-allowed');
                sendButton.classList.add('bg-blue-500', 'hover:bg-blue-600');

                const filename = response.link.split('/').pop();
                sendButton.onclick = () => sendToAI(filename);
                alert('Upload berhasil: ' + response.link);
            } else {
                alert('Upload gagal: ' + (response.message || 'Unknown error.'));
            }
        } else {
            alert('Upload gagal dengan status ' + xhr.status);
        }
    };

    xhr.onerror = function () {
        alert('Upload gagal. Silakan coba lagi.');
    };

    xhr.send(formData);
}

function toggleCheckbox(checkbox) {
    const checkIcon = document.getElementById('check-icon');
    const sendButton = document.getElementById('send-ai-button');

    if (checkbox.checked) {
        checkIcon.classList.remove('hidden');
        sendButton.disabled = false;
        sendButton.classList.remove('bg-gray-400', 'cursor-not-allowed');
        sendButton.classList.add('bg-blue-500', 'hover:bg-blue-600');
    } else {
        checkIcon.classList.add('hidden');
        sendButton.disabled = true;
        sendButton.classList.remove('bg-blue-500', 'hover:bg-blue-600');
        sendButton.classList.add('bg-gray-400', 'cursor-not-allowed');
    }
}

async function sendToAI(filename) {
    const sendButton = document.getElementById('send-ai-button');
    const dottedArea = document.getElementById('dotted-area');

    sendButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Loading...';
    sendButton.disabled = true;
    sendButton.classList.add('bg-gray-400', 'cursor-not-allowed');

    try {
        const sensorResponse = await fetch('https://botaniq.cogarden.app/backend/load_data.php');
        const sensorData = await sensorResponse.json();
        const { temp, humidity, soil: moisture } = sensorData;

        const aiResponse = await fetch(`https://ai-img.botaniq.cogarden.app/ai/plant-data?imgUrl=https://upload.botaniq.cogarden.app/public/${filename}&humidity=${humidity}&temperature=${temp}&moisture=${moisture}`);
        const aiResult = await aiResponse.text();

        dottedArea.style.display = 'block';
        dottedArea.textContent = aiResult;
    } catch (error) {
        alert('Terjadi kesalahan saat memproses data.');
        console.error(error);
    } finally {
        sendButton.innerHTML = '<i class="fas fa-robot mr-2"></i> Send to AI';
        sendButton.disabled = false;
        sendButton.classList.remove('bg-gray-400', 'cursor-not-allowed');
        sendButton.classList.add('bg-blue-500', 'hover:bg-blue-600');
    }
}

function openFileDialog() {
    document.getElementById('file-upload-modal').style.display = 'flex';
}

function closeFileDialog() {
    document.getElementById('file-upload-modal').style.display = 'none';
}
</script>

<?php
require_once './layout/bottom.php';
?>
</body>
