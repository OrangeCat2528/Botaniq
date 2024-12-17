document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("water-tank-container").addEventListener("click", function () {
      fetch("https://botaniq.cogarden.app/backend/load_data.php")
        .then(response => response.json())
        .then(data => {
          const { watertank, soil } = data;
  
          let modalMessage = "Are you sure to Activate Watering System?";
          if (watertank < 25) {
            modalMessage = "Your Water Tank is below the Recommended Percentage (25%). Do you want to keep Watering Enabled?";
          } else if (soil > 65) {
            modalMessage = "The pot soil moisture is in good condition, do you still want to activate watering?";
          }
  
          const modal = document.createElement("div");
          modal.id = "popup-modal";
          modal.className = "fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50";
          modal.innerHTML = `
            <div class="bg-white rounded-3xl shadow-lg p-6 w-11/12 max-w-lg relative">
              <button id="close-modal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-lg"></i>
              </button>
              <div class="warning-sign m-5 p-4 bg-yellow-400 rounded-3xl shadow-lg text-center flex flex-col justify-center items-center hidden" id="alert-container">
                <div class="flex items-center justify-center">
                  <i class="fas fa-exclamation-triangle text-xl mr-2"></i>
                  <span class="font-bold text-lg">Warning</span>
                </div>
                <p class="mt-2" id="alert-text"></p>
              </div>
              <div class="text-orange-500 text-3xl mb-3 flex justify-center">
                <i class="fas fa-exclamation-triangle"></i>
              </div>
              <p class="text-gray-700 text-center font-bold text-sm md:text-base mb-4">${modalMessage}</p>
              <button id="activate-watering" class="bg-green-500 text-white font-bold px-4 py-2 rounded-lg w-full flex items-center justify-center gap-2 transition duration-300 hover:bg-green-600">
                <span id="activate-text">Activate Watering</span>
                <i id="loading-icon" class="fas fa-spinner fa-spin hidden"></i>
              </button>
              <div id="progress-bar" class="mt-3 w-full h-2 bg-gray-200 rounded hidden">
                <div class="h-2 bg-blue-500 rounded" style="width: 0%"></div>
              </div>
            </div>
          `;
          document.body.appendChild(modal);
  
          document.getElementById("close-modal").addEventListener("click", function () {
            modal.remove();
          });
  
          document.getElementById("activate-watering").addEventListener("click", function () {
            const loadingIcon = document.getElementById("loading-icon");
            const activateText = document.getElementById("activate-text");
            const progressBar = document.getElementById("progress-bar");
            const progress = progressBar.querySelector("div");
  
            loadingIcon.classList.remove("hidden");
            activateText.textContent = "Loading...";
  
            fetch("http://100.100.1.5:2000/watertank&status=1")
              .then(response => {
                if (!response.ok) {
                  throw new Error("Failed to activate watering");
                }
                return response.json();
              })
              .then(() => {
                loadingIcon.classList.add("hidden");
                activateText.textContent = "Activate Watering";
                progressBar.classList.remove("hidden");
                let width = 0;
  
                const interval = setInterval(() => {
                  width += 10;
                  progress.style.width = `${width}%`;
  
                  if (width >= 100) {
                    clearInterval(interval);
                    progressBar.classList.add("hidden");
                    this.classList.add("bg-gray-400", "cursor-not-allowed");
                    this.disabled = true;
                  }
                }, 300);
              })
              .catch(() => {
                loadingIcon.classList.add("hidden");
                activateText.textContent = "Activate Watering";
  
                const alertContainer = document.getElementById("alert-container");
                const alertText = document.getElementById("alert-text");
                alertText.textContent = "Watering Failed! Make sure Pot has Stable Internet Connection";
                alertContainer.classList.remove("hidden");
              });
          });
  
          modal.addEventListener("click", function (e) {
            if (e.target === modal) {
              modal.remove();
            }
          });
        })
        .catch(error => {
          console.error("Error fetching water tank data:", error);
        });
    });
  });