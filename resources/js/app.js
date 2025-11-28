import './bootstrap';
import '@fortawesome/fontawesome-free/css/all.min.css';

document.addEventListener('DOMContentLoaded', () => {
    const sections = document.querySelectorAll('section');

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('appear');
            }
        });
    }, { threshold: 0.2 });

    sections.forEach(sec => observer.observe(sec));
});

// console.log('Echo test:', window.Echo);

// window.Echo.connector.socket.onopen = function () {
//     console.log('✅ WebSocket connected to Reverb');
// };

// window.Echo.connector.socket.onerror = function (err) {
//     console.error('❌ WebSocket error:', err);
// };