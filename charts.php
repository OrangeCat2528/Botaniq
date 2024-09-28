<?php
require_once './layout/top.php';
require_once './layout/header.php';
require_once './layout/sidebar.php';
?>

  <!-- BAGIAN GRAFIK -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 mt-5 mx-3 mb-32" id="konten-grafik">
    <div>
      <canvas class="mb-3 p-4 rounded-3xl bg-white shadow-md" id="chart-1" style="height: 45vh; width: 100%;"></canvas>
    </div>
    <div>
      <canvas class="mb-3 p-4 rounded-3xl bg-white shadow-md" id="chart-2" style="height: 45vh; width: 100%;"></canvas>
    </div>
    <div>
      <canvas class="mb-3 p-4 rounded-3xl bg-white shadow-md" id="chart-3" style="height: 45vh; width: 100%;"></canvas>
    </div>
  </div>

  <!-- Include the Chart.js library before your custom script -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="js/grafik.js?v=1"></script>
<?php
require_once './layout/bottom.php';
?>

</body>

</html>
