import Echo from 'laravel-echo';

import Pusher from 'pusher-js';

console.log('Origin browser saat ini:', window.location.origin);

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    host: 'ws://127.0.0.1:8080',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

const pusher = window.Echo.connector.pusher;

// Event koneksi
pusher.connection.bind('connected', () => {
    console.log('✅ WebSocket connected!');
    console.log('URL yang digunakan:', window.Echo.options.host);
    console.log('readyState socket:', pusher.connection.socket?.readyState);
});

// Event disconnect
pusher.connection.bind('disconnected', () => console.log('❌ WebSocket disconnected'));

// Event error
pusher.connection.bind('error', (err) => console.error('⚠️ WebSocket error:', err));

// Listen channel
// window.Echo.channel('quiz.1')
//     .listen('.question.sent', e => console.log('📩 Event diterima:', e));



// document.addEventListener('DOMContentLoaded', () => {
//     console.log('DOM siap, inisialisasi Echo');
//     window.Pusher = Pusher;

//     window.Echo = new Echo({
//         broadcaster: 'reverb',
//         host: 'ws://127.0.0.1:8080',
//         key: import.meta.env.VITE_REVERB_APP_KEY,
//         wsHost: import.meta.env.VITE_REVERB_HOST,
//         wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
//         wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
//         forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
//         enabledTransports: ['ws', 'wss'],
//     });
//     console.log("Echo loaded?", window.Echo);
//     if (window.Echo) {
//         console.log("registered")
//         window.Echo.channel('quiz.1')
//             .listen('.question.sent', e => {
//                 console.log('📩 Event diterima:', e).error(err => console.error('❌ Echo Error:', err));;
//             });
//     } else {
//         console.error("Echo belum siap!");
//     }
// });
// console.log("fired");