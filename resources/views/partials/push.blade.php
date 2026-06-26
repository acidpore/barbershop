<script>
    // Web Push: daftarkan service worker & langganan notifikasi reminder booking.
    const VAPID_PUBLIC_KEY = @json(config('webpush.vapid.public_key'));
    const PUSH_SUBSCRIBE_URL = @json(route('push.subscribe'));
    const PUSH_CSRF = @json(csrf_token());

    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
        const raw = atob(base64);
        return Uint8Array.from([...raw].map((c) => c.charCodeAt(0)));
    }

    window.enablePush = async function () {
        if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
            alert('Browser ini tidak mendukung notifikasi push.');
            return;
        }
        if (!VAPID_PUBLIC_KEY) {
            alert('VAPID key belum diset. Jalankan: php artisan webpush:vapid');
            return;
        }
        const permission = await Notification.requestPermission();
        if (permission !== 'granted') return;

        const registration = await navigator.serviceWorker.register('/sw.js');
        await navigator.serviceWorker.ready;
        const subscription = await registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: urlBase64ToUint8Array(VAPID_PUBLIC_KEY),
        });
        const json = subscription.toJSON();

        await fetch(PUSH_SUBSCRIBE_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': PUSH_CSRF },
            body: JSON.stringify({ endpoint: json.endpoint, keys: json.keys }),
        });

        window.dispatchEvent(new CustomEvent('push-enabled'));
        alert('Notifikasi booking diaktifkan untuk perangkat ini.');
    };
</script>
