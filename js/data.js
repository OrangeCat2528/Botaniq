function load_data() {
  fetch('https://botaniq.cogarden.app/backend/load_data.php') // Adjust URL as needed
    .then(response => response.json())
    .then(data => {
      console.log(data);

      // Select elements
      const data1Element = document.getElementById('data1');
      const data2Element = document.getElementById('data2');
      const data3Element = document.getElementById('data3');
      const lastWaktuElement = document.getElementById('last-waktu');
      const warningSign = document.querySelector('.warning-sign');

      // Set data values
      if (data1Element) data1Element.textContent = (data.temp || '-');
      if (data2Element) data2Element.textContent = (data.humidity || '-');
      if (data3Element) data3Element.textContent = (data.soil || '-');

      // Display time
      const dataTime = data.waktu || '-';
      if (lastWaktuElement) lastWaktuElement.textContent = `${dataTime} WIB`;

      // Check if data is within the acceptable time range (2-3 minutes ago)
      if (dataTime !== '-') {
        const now = new Date();
        const dataDate = new Date(`1970-01-01T${dataTime}:00`); // Assume data.waktu is in "HH:MM" format

        // Calculate the acceptable range (2 to 3 minutes ago)
        const twoMinutesAgo = new Date(now.getTime() - 2 * 60 * 1000);
        const threeMinutesAgo = new Date(now.getTime() - 3 * 60 * 1000);

        if (dataDate < threeMinutesAgo || dataDate > twoMinutesAgo) {
          // Show warning if data time is outside the range
          if (warningSign) warningSign.classList.remove('hidden');
        } else {
          // Hide warning if data time is within the range
          if (warningSign) warningSign.classList.add('hidden');
        }
      }
    })
    .catch(error => console.error('Error loading data:', error));
}

document.addEventListener('DOMContentLoaded', () => {
  setInterval(load_data, 5000); // Adjust polling interval if necessary
});
