// Constants
const CONFIG = {
  API_URL: 'https://botaniq.cogarden.app/backend/load_data.php',
  UPDATE_INTERVAL: 1000,  // 1 second
  OFFLINE_THRESHOLD: 5 * 60 * 1000  // 5 minutes in milliseconds
};

class BotaniqDataManager {
  constructor() {
    this.lastUpdateTime = null;
    this.isInitialized = false;
  }

  init() {
    if (this.isInitialized) return;
    
    // Start the update loop
    this.updateLoop();
    this.isInitialized = true;
  }

  updateLoop() {
    this.fetchAndUpdateData();
    setInterval(() => this.fetchAndUpdateData(), CONFIG.UPDATE_INTERVAL);
  }

  updateMetricValue(elementId, value) {
    const element = document.getElementById(elementId);
    if (element) {
      element.textContent = value || '-';
    }
  }

  updateLastUpdate(timestamp) {
    const element = document.getElementById('last-waktu');
    if (element) {
      // Format with leading zero
      const formattedTime = timestamp || 'Connecting to IoT..';
      element.textContent = formattedTime ? `${formattedTime} WIB` : 'Connecting to IoT..';
    }
  }

  updateConnectionStatus(timestamp) {
    const warningSign = document.querySelector('.warning-sign');
    if (!warningSign) return;

    if (timestamp && timestamp !== '-') {
      const now = new Date();
      const dataDate = new Date(timestamp.replace(' ', 'T'));
      const timeDiff = now - dataDate;

      if (timeDiff > CONFIG.OFFLINE_THRESHOLD) {
        warningSign.classList.remove('hidden');
        
        // Update status bar indicator
        const statusIndicator = document.querySelector('.fixed.bottom-28 .bg-white\\/90');
        if (statusIndicator) {
          // Update pulse dot
          const pulseDot = statusIndicator.querySelector('div[class*="bg-"]');
          if (pulseDot) {
            pulseDot.className = pulseDot.className.replace(/bg-\w+-500/, 'bg-red-500');
          }
          
          // Update signal icon
          const signalIcon = statusIndicator.querySelector('.fas.fa-signal');
          if (signalIcon) {
            signalIcon.className = 'fas fa-signal text-red-500';
          }
        }
      } else {
        warningSign.classList.add('hidden');
        
        // Reset status bar to normal
        const statusIndicator = document.querySelector('.fixed.bottom-28 .bg-white\\/90');
        if (statusIndicator) {
          // Reset pulse dot
          const pulseDot = statusIndicator.querySelector('div[class*="bg-"]');
          if (pulseDot) {
            pulseDot.className = pulseDot.className.replace(/bg-\w+-500/, 'bg-green-500');
          }
          
          // Reset signal icon
          const signalIcon = statusIndicator.querySelector('.fas.fa-signal');
          if (signalIcon) {
            signalIcon.className = 'fas fa-signal text-green-500';
          }
        }
      }
    }
  }

  async fetchAndUpdateData() {
    try {
      const response = await fetch(CONFIG.API_URL);
      const data = await response.json();
      
      // Update metrics
      this.updateMetricValue('data1', data.temp);
      this.updateMetricValue('data2', data.humidity);
      this.updateMetricValue('data3', data.soil);
      
      // Update timestamp and check connection
      this.updateLastUpdate(data.waktu);
      this.updateConnectionStatus(data.waktu);
      
      this.lastUpdateTime = new Date();
    } catch (error) {
      console.error('Error fetching data:', error);
      this.updateLastUpdate(null); // Show connecting state
      this.updateConnectionStatus(null); // Show offline state
    }
  }
}

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', () => {
  const dataManager = new BotaniqDataManager();
  dataManager.init();
});