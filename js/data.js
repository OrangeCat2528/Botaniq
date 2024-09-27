function load_data() {
  fetch('http://localhost/botaniq/backend/load_data.php') //SESUAIKAN
    .then(response => response.json())
    .then(data => {
      console.log(data);
      const data1Element = document.getElementById('data1');
      const data2Element = document.getElementById('data2');
      const data3Element = document.getElementById('data3');
      const lastWaktuElement = document.getElementById('last-waktu');

      if (data1Element) data1Element.textContent = (data.temp || '-');
      if (data2Element) data2Element.textContent = (data.humidity || '-');
      if (data3Element) data3Element.textContent = (data.soil || '-');
      if (lastWaktuElement) lastWaktuElement.textContent = (data.waktu || '-') + ' WIB';
    })
    .catch(error => console.error('Error loading data:', error));
}

document.addEventListener('DOMContentLoaded', () => {
  setInterval(load_data, 500);
});