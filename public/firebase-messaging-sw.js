// firebase-messaging-sw.js
importScripts('https://www.gstatic.com/firebasejs/12.3.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/12.3.0/firebase-messaging-compat.js');

firebase.initializeApp({
    apiKey: "AIzaSyCfHDvHfXQFh86Q0xu-EisbFORMl2llGME",
    authDomain: "market-platform-b93fa.firebaseapp.com",
    projectId: "market-platform-b93fa",
    storageBucket: "market-platform-b93fa.firebasestorage.app",
    messagingSenderId: "859095628707",
    appId: "1:859095628707:web:f21f330779630d12cb3816",
    measurementId: "G-1S0ZSR2NVM"
});

const messaging = firebase.messaging();

// إظهار الإشعار لما يوصل Push (حتى لو التطبيق بالخلفية أو مقفول)
messaging.onBackgroundMessage(function (payload) {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);

    const notificationTitle = payload.data.title || payload.title || 'New Notification';
    const notificationOptions = {
        body: payload.data.body || payload.body,
        icon: payload.data.icon,
        data: {
            url: payload.data.url
        }
    };


    // 👇 هذا السطر هو اللي يضمن ظهور الإشعار على الموبايل
    return self.registration.showNotification(notificationTitle, notificationOptions);
});

// عند الضغط على الإشعار
self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    let targetUrl = event.notification.data?.url;
    if (!targetUrl) {
        targetUrl = self.location.origin + '/dashboard';
    }

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(windowClients => {
            for (let client of windowClients) {
                if (client.url === targetUrl && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});
