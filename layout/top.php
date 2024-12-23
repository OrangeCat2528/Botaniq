<!DOCTYPE html>
<html lang="en">

<head>
  <title>Botaniq SuperApp</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="style/style.css" rel="stylesheet">
  <link href="assets/fontawesome/css/all.css" rel="stylesheet">

  <style>
    @font-face {
      font-family: 'Montserrat';
      src: url('assets/montserrat.woff2') format('woff2');
      font-weight: 100 900;
      font-style: normal;
    }
  </style>
  <script>
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('../PWA/service-worker.js')
      .then(registration => {
        console.log('Service Worker registered with scope:', registration.scope);
      })
      .catch(error => {
        console.error('Service Worker registration failed:', error);
      });
  }
</script>
<link rel="manifest" href="../manifest.json">
</head>

<body class="mx-auto text-center scroll-smooth bg-gray-200">
</body>

</html>
