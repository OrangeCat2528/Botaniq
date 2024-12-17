// Tambahkan event listener pada water-tank-container
document.getElementById('water-tank-container').addEventListener('click', async function() {
    // Buat modal dengan Tailwind CSS classes
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    
    try {
      // Ambil data dari API
      const response = await fetch('https://botaniq.cogarden.app/backend/load_data.php');
      const data = await response.json();
      
      const waterLevel = parseInt(data.watertank);
      const soilLevel = parseInt(data.soil);
      
      // Tentukan pesan berdasarkan kondisi
      let message = '';
      if (waterLevel < 25) {
        message = `Your Water Tank is below the Recommended Percentage (${waterLevel}%), do you want to keep Watering Enabled?`;
      } else if (soilLevel > 65) {
        message = 'The pot soil moisture is in good condition, do you still want to activate watering?';
      } else {
        message = 'Are you sure to Activate Watering System?';
      }
      
      modal.innerHTML = `
        <div class="bg-white rounded-3xl p-6 w-11/12 max-w-md mx-auto">
          <div class="flex items-center justify-center mb-4">
            <i class="fas fa-exclamation-triangle text-3xl text-orange-500"></i>
          </div>
          <p class="text-center mb-6 text-gray-700">${message}</p>
          <div class="hidden mb-4 bg-gray-200 rounded-full h-1" id="progressBar">
            <div class="bg-green-500 h-1 rounded-full w-0 transition-all duration-3000" id="progress"></div>
          </div>
          <div class="flex flex-col gap-3">
            <button id="startWatering" class="bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-xl transition-colors duration-200">
              Start Watering
            </button>
            <button id="stopWatering" class="bg-red-500 hover:bg-red-600 text-white py-3 px-4 rounded-xl transition-colors duration-200">
              Stop Watering
            </button>
          </div>
        </div>
      `;
      
      document.body.appendChild(modal);
      
      // Handle Start Watering
      document.getElementById('startWatering').addEventListener('click', function() {
        const progressBar = document.getElementById('progressBar');
        const progress = document.getElementById('progress');
        const startButton = this;
        
        progressBar.classList.remove('hidden');
        
        // Mulai progress bar
        setTimeout(() => {
          progress.style.width = '100%';
        }, 100);
        
        // Disable button dan ubah warna
        startButton.classList.remove('bg-green-500', 'hover:bg-green-600');
        startButton.classList.add('bg-gray-500');
        startButton.disabled = true;
        
        // Tutup modal setelah 3 detik
        setTimeout(() => {
          modal.remove();
        }, 3000);
      });
      
      // Handle Stop Watering
      document.getElementById('stopWatering').addEventListener('click', function() {
        modal.remove();
      });
      
      // Handle click outside modal to close
      modal.addEventListener('click', function(e) {
        if (e.target === modal) {
          modal.remove();
        }
      });
      
    } catch (error) {
      console.error('Error fetching data:', error);
      modal.remove();
    }
  });