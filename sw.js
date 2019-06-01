self.addEventListener('fetch', function(event) {
  event.respondWith(
    caches.match(event.request).then(function(response) {
      return response || fetch(event.request);
    })
  );
});

self.addEventListener('install', function(e) {
  e.waitUntil(
    caches.open('the-magic-cache').then(function(cache) {
    })
  );
});

self.addEventListener('install', function(e) {
  e.waitUntil(
    caches.open('the-magic-cache').then(function(cache) {
      return cache.addAll([
        '/tcc/',
        '/tcc/index.html',
        '/tcc/manifest.json',
        '/tcc/lerQRCode.html',
        '/tcc/site.php',
      ]);
    })
  );
});

