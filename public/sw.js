// Stoka PWA — Service Worker
// Strategy: network-first for pages/data, cache-first for static assets

const CACHE = 'stoka-v1';
const STATIC = [
  'https://fonts.googleapis.com',
  'https://fonts.gstatic.com',
];

self.addEventListener('install', e => {
  self.skipWaiting();
});

self.addEventListener('activate', e => {
  e.waitUntil(
    caches.keys().then(keys =>
      Promise.all(keys.filter(k => k !== CACHE).map(k => caches.delete(k)))
    )
  );
  self.clients.claim();
});

self.addEventListener('fetch', e => {
  const url = new URL(e.request.url);

  // Never cache: API calls, auth, manifest
  if (['/manifest.json','/login','/logout','/demo'].some(p => url.pathname.startsWith(p))) return;

  // Cache-first for fonts and icons (long-lived static)
  if (STATIC.some(o => e.request.url.startsWith(o)) || url.pathname.startsWith('/icons/')) {
    e.respondWith(
      caches.match(e.request).then(cached => {
        if (cached) return cached;
        return fetch(e.request).then(res => {
          if (res.ok) {
            const clone = res.clone();
            caches.open(CACHE).then(c => c.put(e.request, clone));
          }
          return res;
        });
      })
    );
    return;
  }

  // Network-first for all shop pages (keeps demo data fresh)
  e.respondWith(
    fetch(e.request).catch(() => caches.match(e.request))
  );
});
