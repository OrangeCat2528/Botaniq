<?php
session_start();
if (isset($_SESSION['login'])) {
  header('Location: dashboard');
  exit;
} else {
  header('Location: auth/login');
  exit;
}
?>
<script>
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/PWA/service-worker.js')
      .then(registration => {
        console.log('Service Worker registered with scope:', registration.scope);
      })
      .catch(error => {
        console.error('Service Worker registration failed:', error);
      });
  }
</script>
<link rel="manifest" href="/manifest.json">
