// Membuat fungsi untuk mengambil data dari backend dan menampilkan status berdasarkan parameter
async function updateAIStats() {
    try {
      // Ambil data dari backend menggunakan Fetch API
      const response = await fetch('https://botaniq.cogarden.app/backend/load_data.php');
      const data = await response.json();
  
      // Dapatkan elemen <i> berdasarkan ID-nya
      const aiStatsElement = document.getElementById('ai-stats');
  
      // Cek kondisi berdasarkan suhu, kelembapan, dan kelembapan tanah
      const temp = data.temp;
      const humidity = data.humidity;
      const soil = data.soil;
  
      // Fungsi untuk menentukan status tanaman
      function determinePlantStatus(temp, humidity, soil) {
        // BLOOMING! 🌷✨
        if (temp >= 24 && temp <= 28 && humidity >= 55 && humidity <= 65 && soil >= 70 && soil <= 80) {
          return "BLOOMING! 🌷✨";
        }
  
        // SHINING BRIGHT! 🌟
        if (temp > 28 && temp <= 30 && humidity >= 50 && humidity <= 60 && soil >= 65 && soil <= 75) {
          return "SHINING BRIGHT! 🌟";
        }
  
        // DOING FINE! 🌷
        if (temp >= 22 && temp <= 32 && humidity >= 45 && humidity <= 55 && soil >= 60 && soil <= 70) {
          return "DOING FINE! 🌷";
        }
  
        // A LITTLE DOWN... 😕
        if (temp >= 18 && temp <= 34 && humidity >= 40 && humidity <= 50 && soil >= 50 && soil <= 60) {
          return "A LITTLE DOWN... 😕";
        }
  
        // HELP ME, PLEASE! 🆘
        if (temp < 18 || temp > 35 || humidity < 40 || humidity > 70 || soil < 50) {
          return "HELP ME, PLEASE! 🆘";
        }
  
        // Jika tidak ada kondisi yang cocok, kembalikan status default
        return "Data Not Found";
      }
  
      // Tentukan status tanaman berdasarkan nilai yang diambil dari backend
      const plantStatus = determinePlantStatus(temp, humidity, soil);
  
      // Tampilkan status tanaman di elemen <i>
      aiStatsElement.textContent = plantStatus;
  
    } catch (error) {
      console.error('Error fetching data:', error);
      // Jika terjadi error, tampilkan pesan error di elemen <i>
      const aiStatsElement = document.getElementById('ai-stats');
      aiStatsElement.textContent = 'Error loading data';
    }
  }
  
  // Panggil fungsi ketika halaman sudah selesai di-load
  window.addEventListener('DOMContentLoaded', updateAIStats);
  