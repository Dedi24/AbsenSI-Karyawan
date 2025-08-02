const CACHE_NAME = 'absensi-karyawan-v1';
const urlsToCache = [
    '/',
    '/login',
    '/admin/dashboard',
    '/manifest.json',
    '/css/app.css',
    '/js/app.js',
    '/icons/icon-192x192.png',
    '/icons/icon-512x512.png',
    '/pwa/offline'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('Opened cache');
                return cache.addAll(urlsToCache);
            })
    );
});

self.addEventListener('fetch', (event) => {
    // Jika request adalah untuk halaman offline, gunakan cache
    if (event.request.url.includes('/pwa/offline')) {
        event.respondWith(
            caches.match(event.request)
                .then((response) => {
                    if (response) {
                        return response;
                    }
                    return fetch(event.request);
                })
        );
        return;
    }
    
    event.respondWith(
        caches.match(event.request)
            .then((response) => {
                // Return cached response if available
                if (response) {
                    return response;
                }
                // Otherwise fetch from network
                return fetch(event.request);
            })
            .catch(() => {
                // Fallback to offline page for document requests
                if (event.request.destination === 'document') {
                    return caches.match('/pwa/offline');
                }
            })
    );
});

self.addEventListener('activate', (event) => {
    const cacheWhitelist = [CACHE_NAME];
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheWhitelist.indexOf(cacheName) === -1) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});