// Membuat fungsi untuk mengambil data dari backend dan menampilkan status tanaman
function updateAIStats() {
  fetch('https://botaniq.cogarden.app/backend/load_data.php') // URL Backend yang sesuai
    .then(response => {
      // Cek apakah respons berhasil
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(data => {
      console.log('Data received from backend:', data); // Log data dari backend

      // Ambil elemen <i> untuk status tanaman
      const aiStatsElement = document.getElementById('ai-stats');
      
      // Ambil nilai temp, humidity, dan soil dari data dan cek validitasnya
      const temp = parseFloat(data.temp);
      const humidity = parseFloat(data.humidity);
      const soil = parseFloat(data.soil);
      
      // Log the parsed values for debugging
      console.log(`Parsed values - Temp: ${temp}, Humidity: ${humidity}, Soil: ${soil}`);

      // Cek apakah data yang diterima valid
      if (isNaN(temp) || isNaN(humidity) || isNaN(soil)) {
        if (aiStatsElement) aiStatsElement.textContent = "Data tidak valid: nilai tidak dapat dihitung";
        return;
      }

      // Tentukan status tanaman berdasarkan nilai yang diambil dari backend
      const plantStatus = determinePlantStatus(temp, humidity, soil);
      
      // Tampilkan status tanaman di elemen <i>
      if (aiStatsElement) aiStatsElement.textContent = plantStatus;
    })
    .catch(error => console.error('Error fetching data:', error));
}

// Fungsi untuk menentukan status tanaman
function determinePlantStatus(temp, humidity, soil) {
  console.log(`Evaluating plant status for Temp: ${temp}, Humidity: ${humidity}, Soil: ${soil}`);
  
  // BLOOMING! 🌷✨
  if (temp >= 22 && temp <= 30 && humidity >= 55 && humidity <= 70 && soil >= 65 && soil <= 80) {
      console.log("Plant status: BLOOMING! 🌷✨");
      return "BLOOMING!";
  }

  // SHINING BRIGHT! 🌟
  if (temp > 30 && temp <= 34 && humidity >= 50 && humidity <= 70 && soil >= 60 && soil <= 75) {
      console.log("Plant status: SHINING BRIGHT! 🌟");
      return "SHINING BRIGHT!";
  }

  // DOING FINE! 🌷
  if (temp >= 20 && temp <= 35 && humidity >= 45 && humidity <= 70 && soil >= 60 && soil <= 75) {
      console.log("Plant status: DOING FINE! 🌷");
      return "DOING FINE!";
  }

  // A LITTLE DOWN... 😕
  if (temp >= 18 && temp <= 38 && humidity >= 40 && humidity <= 75 && soil >= 50 && soil <= 65) {
      console.log("Plant status: A LITTLE DOWN... 😕");
      return "A LITTLE DOWN...";
  }

  // HELP ME, PLEASE! 🆘
  if (temp < 18 || temp > 38 || humidity < 40 || humidity > 75 || soil < 50) {
      console.log("Plant status: HELP ME, PLEASE! 🆘");
      return "HELP ME, PLEASE!";
  }

  // Jika tidak ada kondisi yang cocok, kembalikan status default
  console.log("Plant status: Data tidak valid");
  return `THINKING..`;
}  

// Panggil fungsi ketika halaman sudah selesai di-load
document.addEventListener('DOMContentLoaded', () => {
  updateAIStats(); // Memanggil fungsi untuk pertama kali
  setInterval(updateAIStats, 5000); // Memperbarui status setiap 5 detik
});
