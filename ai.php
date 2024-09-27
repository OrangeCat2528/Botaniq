<?php
require_once './layout/top.php';
// require_once '../helper/connection.php';
require_once './layout/header.php';
require_once './layout/sidebar.php';
?>

<!--
<div class="m-5 p-4 bg-yellow-400 rounded-3xl shadow-lg text-center flex flex-col justify-center items-center">
  <div class="flex items-center justify-center">
    <i class="fas fa-exclamation-triangle text-xl mr-2"></i>
    <span class="font-bold text-lg">Information</span>
  </div>
  <p class="mt-2">
    AI is on Alpha Development. AI may not Give Accurate Information!
  </p>
</div>
-->
<!-- Gambar Section menggantikan Camera View -->
<div class="shadow-lg rounded-3xl max-w-xs mx-auto overflow-hidden">
  <!-- Menggunakan tag img dan ukuran tetap sama dengan class yang digunakan sebelumnya -->
  <div class="w-full h-80 mx-auto rounded-t-3xl overflow-hidden bg-black">
    <img src="https://i0.wp.com/media.dekoruma.com/article/2018/12/14080016/Tanaman-Dalam-Pot-Money-Bag-e1544750004398.jpg" class="w-full h-full object-cover" alt="Pot Tanaman" />
  </div>

  <!-- Tombol tetap sama -->
  <button class="w-full bg-blue-500 text-white font-bold py-3 flex justify-center items-center rounded-b-3xl">
    <i class="fas fa-camera text-white mr-2"></i> Capture
  </button>
</div>

<div class="lg:mx-auto lg:w-1/2 mb-44 m-5 p-5 rounded-3xl border-4 border-dotted border-gray-500">
This plant in the image looks healthy and vibrant! The leaves are a bright, rich green, indicating that it is getting the right amount of sunlight and nutrients. The soil looks well-maintained, and the pot is spacious enough for the roots to grow. Overall, it seems to be thriving, with strong, upright growth. A happy plant, indeed!
<div class="invisible h-5"></div>
<script src="js/kamera.js"></script>

<?php
require_once './layout/bottom.php';
?>

</body>
