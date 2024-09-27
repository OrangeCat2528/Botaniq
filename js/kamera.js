const video = document.getElementById('camera-stream');
let currentStream = null;
let currentFacingMode = 'user'; // Start with the front camera

// Function to start the camera
function startCamera(facingMode = 'user') {
  // Stop the previous stream if it exists
  if (currentStream) {
    currentStream.getTracks().forEach(track => track.stop());
  }

  if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    navigator.mediaDevices.getUserMedia({ video: { facingMode: facingMode } })
      .then(function (stream) {
        currentStream = stream; // Save the current stream
        video.srcObject = stream;
        video.play();
      })
      .catch(function (error) {
        console.error("Error accessing the camera: ", error);
        alert('Unable to access the camera. Please ensure you have granted camera access.');
      });
  } else {
    console.error("getUserMedia not supported on your browser!");
  }
}

// Function to switch the camera
function switchCamera() {
  // Toggle between 'user' (front) and 'environment' (rear) cameras
  currentFacingMode = (currentFacingMode === 'user') ? 'environment' : 'user';
  startCamera(currentFacingMode);
}

// Event listener to switch camera when clicking the video element twice
video.addEventListener('dblclick', function() {
  switchCamera();
});

// Start the camera when the page loads
window.onload = function() {
  startCamera(); // Start with the front camera by default
};
