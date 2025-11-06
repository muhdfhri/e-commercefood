const CACHE_NAME = 'ecommerce-cache-v1';
const ASSETS_TO_CACHE = [
  '/',
  '/css/style.css',
  '/js/main.js',
  '/images/logo.png',
  '/images/logo-dark.png',
  '/images/not-found.jpg',
  '/images/loader.gif',
  '/images/empty-cart.png',
  '/images/empty-wishlist.png',
  '/images/empty-order.png',
  '/images/empty-search.png',
  '/images/placeholder.png',
  '/images/favicon.ico',
  '/offline.html' // Halaman offline custom
];

// Install Service Worker
self.addEventListener('install', function(event) {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(function(cache) {
        console.log('Opened cache');
        return cache.addAll(ASSETS_TO_CACHE);
      })
  );
});

// Fetch event for serving assets from cache
self.addEventListener('fetch', function(event) {
  event.respondWith(
    caches.match(event.request)
      .then(function(response) {
        // Cache hit - return response
        if (response) {
          return response;
        }

        // Clone the request
        var fetchRequest = event.request.clone();

        return fetch(fetchRequest).then(
          function(response) {
            // Check if we received a valid response
            if(!response || response.status !== 200 || response.type !== 'basic') {
              return response;
            }

            // Clone the response
            var responseToCache = response.clone();

            caches.open(CACHE_NAME)
              .then(function(cache) {
                cache.put(event.request, responseToCache);
              });

            return response;
          }
        ).catch(function() {
          // If both cache and network fail, show offline page
          if (event.request.mode === 'navigate') {
            return caches.match('/offline.html');
          }
        });
      })
    );
});

// Clean up old caches
self.addEventListener('activate', function(event) {
  var cacheWhitelist = [CACHE_NAME];
  event.waitUntil(
    caches.keys().then(function(cacheNames) {
      return Promise.all(
        cacheNames.map(function(cacheName) {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});
