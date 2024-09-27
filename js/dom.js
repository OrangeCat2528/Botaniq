// Fungsi untuk memperbarui ikon
function updateIcons() {
  // Ambil nilai dari elemen HTML
  const data1Element = document.getElementById('data1');
  const data2Element = document.getElementById('data2');
  const data3Element = document.getElementById('data3');

  const iconStatus1 = document.getElementById('icon-status1');
  const iconStatus2 = document.getElementById('icon-status2');
  const iconStatus3 = document.getElementById('icon-status3');

  // Pastikan elemen ada sebelum mengubahnya
  if (data1Element && iconStatus1) {
    const data1 = parseFloat(data1Element.innerText);
    if (data1 >= 20 && data1 <= 40) {
      iconStatus1.classList.remove('fa-warning');
      iconStatus1.classList.add('fa-check-circle');
      iconStatus1.style.color = '#6cbe77'; // Warna hijau untuk check-circle
    } else {
      iconStatus1.classList.remove('fa-check-circle');
      iconStatus1.classList.add('fa-warning');
      iconStatus1.style.color = 'orange'; // Warna oranye untuk warning
    }
  }

  if (data2Element && iconStatus2) {
    const data2 = parseFloat(data2Element.innerText);
    if (data2 >= 50 && data2 <= 80) {
      iconStatus2.classList.remove('fa-warning');
      iconStatus2.classList.add('fa-check-circle');
      iconStatus2.style.color = '#6cbe77'; // Warna hijau untuk check-circle
    } else {
      iconStatus2.classList.remove('fa-check-circle');
      iconStatus2.classList.add('fa-warning');
      iconStatus2.style.color = 'orange'; // Warna oranye untuk warning
    }
  }

  if (data3Element && iconStatus3) {
    const data3 = parseFloat(data3Element.innerText);
    if (data3 >= 50 && data3 <= 80) {
      iconStatus3.classList.remove('fa-warning');
      iconStatus3.classList.add('fa-check-circle');
      iconStatus3.style.color = '#6cbe77'; // Warna hijau untuk check-circle
    } else {
      iconStatus3.classList.remove('fa-check-circle');
      iconStatus3.classList.add('fa-warning');
      iconStatus3.style.color = 'orange'; // Warna oranye untuk warning
    }
  }
}

// Panggil fungsi updateIcons setiap 500 ms
setInterval(updateIcons, 1);
