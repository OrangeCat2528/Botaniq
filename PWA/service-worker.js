self.addEventListener('install', (event) => {
    event.waitUntil(
      caches.open('app-cache-v1').then((cache) => {
        return cache.addAll([
          '/',
          '/index.php',
          '/style/style.css',
          '/js/data.js',
          '/PWA/icon/logo-botaniq-512.png',
          '/PWA/icon/logo-botaniq-192.png'
        ]);
      })
    );
  });
  
  self.addEventListener('fetch', (event) => {
    event.respondWith(
      caches.match(event.request).then((response) => {
        return response || fetch(event.request);
      })
    );
  });
  