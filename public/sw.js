const CACHE_NAME = 'athirven-v1';

// Runtime-only, cache-as-you-visit — not a hardcoded precache list, since
// Vite's build assets are content-hashed and a static list would go stale on
// every deploy. This lets a reader reopen an already-visited article/issue
// offline, which is what the PWA is for.
self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET' || new URL(event.request.url).origin !== self.location.origin) {
        return;
    }

    event.respondWith(
        caches.match(event.request).then((cached) => {
            const network = fetch(event.request)
                .then((response) => {
                    if (response.ok) {
                        const clone = response.clone();
                        caches.open(CACHE_NAME).then((cache) => cache.put(event.request, clone));
                    }

                    return response;
                })
                .catch(() => cached);

            return cached || network;
        })
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => Promise.all(
            keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key))
        ))
    );
});
