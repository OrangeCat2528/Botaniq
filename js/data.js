function load_data() {
  fetch('https://botaniq.cogarden.app/backend/load_data.php') // SESUAIKAN URL DENGAN BACKEND-MU
    .then(response => response.json())
    .then(data => {
      // Ambil elemen-elemen yang diperlukan
      const data1Element = document.getElementById('data1');
      const data2Element = document.getElementById('data2');
      const data3Element = document.getElementById('data3');
      const lastWaktuElement = document.getElementById('last-waktu');
      const aiStatsElement = document.getElementById('ai-stats');

      // Tampilkan nilai temp, humidity, soil, dan waktu jika elemen tersedia
      if (data1Element) data1Element.textContent = (data.temp || '-');
      if (data2Element) data2Element.textContent = (data.humidity || '-');
      if (data3Element) data3Element.textContent = (data.soil || '-');
      if (lastWaktuElement) lastWaktuElement.textContent = (data.waktu || '-') + ' WIB';

      // Cek apakah data valid
      const temp = data.temp;
      const humidity = data.humidity;
      const soil = data.soil;

      // Fungsi untuk menentukan status tanaman berdasarkan kondisi
      function determinePlantStatus(temp, humidity, soil) {
        // BLOOMING! 🌷✨
        if (temp >= 24 && temp <= 28 && humidity >= 55 && humidity <= 65 && soil >= 70 && soil <= 80) {
          return "BLOOMING! 🌷✨ Kondisi Optimal";
        }

        // SHINING BRIGHT! 🌟
        if (temp > 28 && temp <= 30 && humidity >= 50 && humidity <= 60 && soil >= 65 && soil <= 75) {
          return "SHINING BRIGHT! 🌟 Kondisi Sangat Baik";
        }

        // DOING FINE! 🌷
        if (temp >= 22 && temp <= 32 && humidity >= 45 && humidity <= 55 && soil >= 60 && soil <= 70) {
          return "DOING FINE! 🌷 Kondisi Cukup Baik";
        }

        // A LITTLE DOWN... 😕
        if (temp >= 18 && temp <= 34 && humidity >= 40 && humidity <= 50 && soil >= 50 && soil <= 60) {
          return "A LITTLE DOWN... 😕 Kondisi Kurang Baik";
        }

        // HELP ME, PLEASE! 🆘
        if (temp < 18 || temp > 35 || humidity < 40 || humidity > 70 || soil < 50) {
          return "HELP ME, PLEASE! 🆘 Kondisi Sangat Buruk";
        }

        // Jika tidak ada kondisi yang cocok, kembalikan status default
        return "Status tidak dapat ditentukan";
      }

      // Tentukan status tanaman
      const plantStatus = determinePlantStatus(temp, humidity, soil);

      // Tampilkan status tanaman di elemen <i>
      if (aiStatsElement) aiStatsElement.textContent = plantStatus;
    })
    .catch(error => console.error('Error loading data:', error));
}

document.addEventListener('DOMContentLoaded', () => {
  // Perbarui data setiap 500ms
  setInterval(load_data, 500);
});
