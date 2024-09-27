<?php
require_once './layout/top.php';
// require_once '../helper/connection.php';
require_once './layout/header.php';
require_once './layout/sidebar.php';
?>

  <!-- BAGIAN TABEL -->
  <div class="m-5 mb-32 p-5 bg-white rounded-3xl shadow-lg">
    <div class="overflow-x-auto">
      <table id="data-table" class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
        <thead>
          <tr>
            <th class="p-2 border-b border-r">ID</th>
            <th class="p-2 border-b border-r">Temp</th>
            <th class="p-2 border-b border-r">Humidity</th>
            <th class="p-2 border-b">Soil</th>
          </tr>
        </thead>
        <tbody id="table-body">
          <!-- Data akan dimuat di sini -->
        </tbody>
      </table>
    </div>
  </div>
  <!--  -->

  <!-- BAGIAN DATA ASLI -->
  <script>
    function loadTableData() {
      fetch('backend/load_table.php') // Pastikan URL sesuai dengan lokasi file PHP Anda
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then(data => {
          const tableBody = document.getElementById('table-body');
          tableBody.innerHTML = ''; // Kosongkan tabel sebelum menambahkan data baru
          const limitedData = data.slice(0, 20); // Hanya ambil 20 baris pertama
          limitedData.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
            <td class="p-2 border-b border-r">${item.id || 'N/A'}</td>
            <td class="p-2 border-b border-r">${item.temp + ' °C' || 'N/A'}</td>
            <td class="p-2 border-b border-r">${item.humidity + ' %' || 'N/A'}</td>
            <td class="p-2 border-b">${item.soil + ' %' || 'N/A'}</td>
          `;
            tableBody.appendChild(row);
          });
        })
        .catch(error => console.error('Error fetching data:', error));
    }

    document.addEventListener('DOMContentLoaded', () => {
      loadTableData(); // Load data initially
      setInterval(loadTableData, 1000); // Update data every 1 second (1000 ms)
    });
  </script>
  <!--  -->

  <?php
  require_once './layout/bottom.php';
  ?>

</body>

</html>