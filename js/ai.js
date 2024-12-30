document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("image-container").addEventListener("click", function() {
        const modal = document.createElement("div");
        modal.id = "popup-modal";
        modal.className = "fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50";
        modal.innerHTML = `
            <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-lg mx-4 transform transition-all duration-300 scale-90 opacity-0">
                <button id="close-modal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-lg"></i>
                </button>
                <h3 class="text-2xl font-semibold text-center mb-6">Select Image</h3>
                <input type="file" 
                       accept="image/*"
                       onchange="handleImageUploadFromModal(event)" 
                       class="w-full p-4 rounded-xl border-2 border-gray-300 hover:border-gray-400 transition-colors duration-300" />
            </div>
        `;
        document.body.appendChild(modal);

        // Tunggu sebentar untuk memastikan DOM sudah diupdate
        setTimeout(() => {
            const modalContent = modal.querySelector('.bg-white');
            modalContent.classList.remove('scale-90', 'opacity-0');
        }, 10);

        // Handle close modal
        const closeModal = () => {
            const modalContent = modal.querySelector('.bg-white');
            modalContent.classList.add('scale-90', 'opacity-0');
            setTimeout(() => {
                modal.remove();
            }, 300);
        };

        // Close on button click
        document.getElementById('close-modal').addEventListener('click', closeModal);

        // Close on outside click
        modal.addEventListener("click", function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    });
});

// Update handleImageUploadFromModal untuk menggunakan closeModal yang baru
function handleImageUploadFromModal(event) {
    const file = event.target.files[0];
    const imageContainer = document.getElementById('image-container');
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
                
                // Close with animation
                const modal = document.getElementById('popup-modal');
                if (modal) {
                    const modalContent = modal.querySelector('.bg-white');
                    modalContent.classList.add('scale-90', 'opacity-0');
                    setTimeout(() => {
                        modal.remove();
                    }, 300);
                }
            };
        };
        reader.readAsDataURL(file);
    } else {
        // Reset ke tampilan awal
        imageContainer.innerHTML = `
            <div class="flex flex-col items-center">
                <i class="fas fa-cloud-upload-alt text-4xl mb-3"></i>
                <span id="no-image-text" class="text-xl">Click to Upload</span>
            </div>
        `;
        imageContainer.style.backgroundColor = '#e0e0e0';
        document.getElementById('upload-section').style.display = 'none';
    }

    closeFileDialog();
}

function startUpload(file, imageContainer, sendButton) {
    sendButton.disabled = true;
    sendButton.classList.add('bg-gray-400', 'cursor-not-allowed');
    sendButton.classList.remove('bg-blue-500', 'hover:bg-blue-600');

    const percentageOverlay = document.createElement('div');
    percentageOverlay.className = 'absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center text-white text-2xl font-bold z-10';
    percentageOverlay.id = 'upload-percentage';
    percentageOverlay.textContent = '0%';
    imageContainer.appendChild(percentageOverlay);

    const formData = new FormData();
    formData.append('file', file);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'https://upload.botaniq.cogarden.app/upload', true);

    xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
            const percentage = Math.round((e.loaded * 100) / e.total);
            document.getElementById('upload-percentage').textContent = percentage + '%';
        }
    };

    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response && response.link) {
                document.getElementById('upload-percentage').remove();
                sendButton.disabled = false;
                sendButton.classList.remove('bg-gray-400', 'cursor-not-allowed');
                sendButton.classList.add('bg-blue-500', 'hover:bg-blue-600');

                const filename = response.link.split('/').pop();
                sendButton.onclick = () => sendToAI(filename);
            } else {
                alert('Upload Failed: ' + (response.message || 'Unknown error.'));
            }
        } else {
            alert('Upload Failed with Status ' + xhr.status);
        }
    };

    xhr.onerror = function() {
        alert('Upload Failed, please try Again.');
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
    sendButton.classList.remove('bg-blue-500', 'hover:bg-blue-600');

    try {
        const sensorResponse = await fetch('https://botaniq.cogarden.app/backend/load_data.php');
        const sensorData = await sensorResponse.json();
        const { temp, humidity, soil: moisture } = sensorData;

        const aiResponse = await fetch(`https://ai-img.botaniq.cogarden.app/ai/plant-data?imgUrl=https://upload.botaniq.cogarden.app/public/${filename}&humidity=${humidity}&temperature=${temp}&moisture=${moisture}`);
        const aiResult = await aiResponse.text();

        dottedArea.style.display = 'block';
        dottedArea.textContent = aiResult;
    } catch (error) {
        alert('Something went wrong while Processing data.');
        console.error(error);
    } finally {
        sendButton.innerHTML = '<i class="fas fa-robot mr-2"></i> Send to AI';
        sendButton.disabled = false;
        sendButton.classList.remove('bg-gray-400', 'cursor-not-allowed');
        sendButton.classList.add('bg-blue-500', 'hover:bg-blue-600');
    }
}