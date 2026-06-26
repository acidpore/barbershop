// Service worker untuk Web Push reminder booking Lumina.
self.addEventListener('push', (event) => {
    if (!event.data) return;
    const payload = event.data.json();
    const data = payload.data || {};

    event.waitUntil(
        self.registration.showNotification(payload.title || 'Lumina', {
            body: payload.body || '',
            icon: payload.icon || '/favicon.ico',
            badge: '/favicon.ico',
            data,
        })
    );
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    const url = (event.notification.data && event.notification.data.url) || '/';
    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((list) => {
            for (const client of list) {
                if (client.url.includes(url) && 'focus' in client) return client.focus();
            }
            return clients.openWindow(url);
        })
    );
});
