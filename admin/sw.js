// Service Worker pour Portfolio Admin PWA
const CACHE_NAME = 'portfolio-admin-v1.4';
const CACHE_URLS = [
  '/admin/',
  '/admin/index.php',
  '/admin/login.php',
  '/admin/projects.php',
  '/admin/experiences.php',
  '/admin/formations.php',
  '/admin/skills.php',
  '/admin/messages.php',
  '/admin/settings.php',
  '/admin/logout.php',
  '/assets/css/admin.css',
  '/assets/css/style.css',
  '/assets/logo/logo-clair.png',
  '/assets/logo/logo-sombre.png',
  '/admin/favicon-theme.js',
  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'
];

// Installation du Service Worker
self.addEventListener('install', (event) => {
  console.log('[SW] Installation en cours...');
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        console.log('[SW] Cache ouvert, ajout des fichiers...');
        return cache.addAll(CACHE_URLS.map(url => {
          // Gérer les URLs relatives et absolues
          if (url.startsWith('http')) {
            return url;
          }
          return new Request(url, { mode: 'no-cors' });
        }));
      })
      .then(() => {
        console.log('[SW] Tous les fichiers ont été mis en cache');
        self.skipWaiting();
      })
      .catch((error) => {
        console.error('[SW] Erreur lors de la mise en cache:', error);
      })
  );
});

// Activation du Service Worker
self.addEventListener('activate', (event) => {
  console.log('[SW] Activation en cours...');
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== CACHE_NAME) {
            console.log('[SW] Suppression de l\'ancien cache:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    }).then(() => {
      console.log('[SW] Service Worker activé');
      return self.clients.claim();
    })
  );
});

// Stratégie de cache : Network First avec fallback sur cache
self.addEventListener('fetch', (event) => {
  // Ignorer les requêtes non-GET
  if (event.request.method !== 'GET') {
    return;
  }

  // Ignorer les requêtes vers des domaines externes (sauf Font Awesome)
  const url = new URL(event.request.url);
  if (url.origin !== location.origin && !url.hostname.includes('cdnjs.cloudflare.com')) {
    return;
  }

  // Stratégie spéciale pour les pages admin
  if (url.pathname.startsWith('/admin/')) {
    event.respondWith(
      fetch(event.request)
        .then((response) => {
          // Si la réponse est valide, la mettre en cache
          if (response.status === 200) {
            const responseClone = response.clone();
            caches.open(CACHE_NAME).then((cache) => {
              cache.put(event.request, responseClone);
            });
          }
          return response;
        })
        .catch(() => {
          // En cas d'échec réseau, utiliser le cache
          return caches.match(event.request).then((cachedResponse) => {
            if (cachedResponse) {
              return cachedResponse;
            }
            // Si pas de cache, retourner une page d'erreur offline
            if (event.request.mode === 'navigate') {
              return caches.match('/admin/index.php');
            }
            return new Response('Contenu non disponible hors ligne', {
              status: 503,
              statusText: 'Service Unavailable'
            });
          });
        })
    );
  } else {
    // Pour les autres ressources (CSS, JS, images)
    event.respondWith(
      caches.match(event.request).then((cachedResponse) => {
        if (cachedResponse) {
          return cachedResponse;
        }
        return fetch(event.request).then((response) => {
          if (response.status === 200) {
            const responseClone = response.clone();
            caches.open(CACHE_NAME).then((cache) => {
              cache.put(event.request, responseClone);
            });
          }
          return response;
        });
      })
    );
  }
});

// Gestion des notifications push (optionnel pour plus tard)
self.addEventListener('push', (event) => {
  if (event.data) {
    const data = event.data.json();
    const options = {
      body: data.body || 'Nouvelle notification',
      icon: '/assets/logo/logo-clair.png',
      badge: '/assets/logo/logo-sombre.png',
      vibrate: [200, 100, 200],
      data: {
        url: data.url || '/admin/'
      },
      actions: [
        {
          action: 'open',
          title: 'Ouvrir',
          icon: '/assets/logo/logo-clair.png'
        },
        {
          action: 'close',
          title: 'Fermer'
        }
      ]
    };

    event.waitUntil(
      self.registration.showNotification(data.title || 'Portfolio Admin', options)
    );
  }
});

// Gestion des clics sur les notifications
self.addEventListener('notificationclick', (event) => {
  event.notification.close();

  if (event.action === 'open' || !event.action) {
    const url = event.notification.data?.url || '/admin/';
    event.waitUntil(
      clients.openWindow(url)
    );
  }
});

// Message de bienvenue dans la console
self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});

console.log('[SW] Service Worker Portfolio Admin chargé et prêt !');
