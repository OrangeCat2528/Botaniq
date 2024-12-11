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
                alert('Upload Success, Debug Link: ' + response.link);
            } else {
                alert('Upload Failed: ' + (response.message || 'Unknown error.'));
            }
        } else {
            alert('Upload Failed with Status ' + xhr.status);
        }
    };

    xhr.onerror = function () {
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

function openFileDialog() {
    document.getElementById('file-upload-modal').style.display = 'flex';
}

function closeFileDialog() {
    document.getElementById('file-upload-modal').style.display = 'none';
}