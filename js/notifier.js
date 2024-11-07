function loadAndCheckData() {
    fetch('https://botaniq.cogarden.app/backend/load_data.php') // Adjust URL as needed
      .then(response => response.json())
      .then(data => {
        console.log(data);
  
        // Extract the timestamp from the data
        const dataTime = data.waktu || '-';
        
        // Check if the device is online based on the last updated time
        checkDataTimestamp(dataTime);
      })
      .catch(error => console.error('Error loading data:', error));
  }
  
  function checkDataTimestamp(dataTime) {
    const warningSign = document.querySelector('.warning-sign');
  
    if (dataTime !== '-') {
      const now = new Date();
      const dataDate = new Date(dataTime.replace(' ', 'T')); // Convert dataTime to Date object (ISO format)
  
      // Calculate the time 5 minutes ago
      const fiveMinutesAgo = new Date(now.getTime() - 5 * 60 * 1000);
  
      if (dataDate < fiveMinutesAgo) {
        // Show warning if data time is older than 5 minutes
        if (warningSign) warningSign.classList.remove('hidden');
      } else {
        // Hide warning if data time is within the last 5 minutes
        if (warningSign) warningSign.classList.add('hidden');
      }
    }
  }
  
  // Set up the interval to fetch data every 5 seconds and check device status
  document.addEventListener('DOMContentLoaded', () => {
    setInterval(loadAndCheckData, 5000); // Adjust polling interval if necessary
  });
  