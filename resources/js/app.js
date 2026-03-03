import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';
import Sortable from 'sortablejs';
import Swal from 'sweetalert2';

// ═══════════════════════════════════════════════════════════════
// Alpine.js Setup
// ═══════════════════════════════════════════════════════════════
window.Alpine = Alpine;
Alpine.start();

// ═══════════════════════════════════════════════════════════════
// Global Libraries
// ═══════════════════════════════════════════════════════════════
window.Chart = Chart;
window.Sortable = Sortable;
window.Swal = Swal;

// ═══════════════════════════════════════════════════════════════
// DARK MODE — No-flash + system preference + localStorage
// ═══════════════════════════════════════════════════════════════
window.toggleTheme = function () {
    const html = document.documentElement;
    const isDark = html.classList.contains('dark');

    html.classList.toggle('dark', !isDark);
    localStorage.setItem('theme', isDark ? 'light' : 'dark');
    updateThemeIcons();

    // Smooth transition feedback
    html.style.transition = 'background-color 0.3s ease, color 0.3s ease';
};

function updateThemeIcons() {
    const darkIcon = document.getElementById('theme-toggle-dark-icon');
    const lightIcon = document.getElementById('theme-toggle-light-icon');
    if (!darkIcon || !lightIcon) return;

    const isDark = document.documentElement.classList.contains('dark');
    darkIcon.classList.toggle('hidden', !isDark);
    lightIcon.classList.toggle('hidden', isDark);
}

// ═══════════════════════════════════════════════════════════════
// TOAST NOTIFICATIONS — Glass-style
// ═══════════════════════════════════════════════════════════════
window.showNotification = function (message, type = 'success') {
    document.querySelectorAll('.toast-notification').forEach(el => el.remove());

    const toast = document.createElement('div');
    toast.className = 'toast-notification';

    const icons = { success: '✅', error: '❌', info: 'ℹ️', warning: '⚠️' };
    const colors = { success: 'border-emerald-500', error: 'border-red-500', info: 'border-primary-500', warning: 'border-amber-500' };

    toast.innerHTML = `
        <div class="toast-card ${colors[type] || colors.info}">
            <span class="text-2xl">${icons[type] || icons.info}</span>
            <div>
                <h4 class="font-bold text-xs uppercase tracking-widest opacity-60">${type}</h4>
                <p class="font-medium text-sm">${message}</p>
            </div>
            <button onclick="this.closest('.toast-notification').remove()" class="ml-auto opacity-40 hover:opacity-100 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    `;

    document.body.appendChild(toast);
    requestAnimationFrame(() => toast.classList.add('toast-visible'));
    setTimeout(() => {
        toast.classList.remove('toast-visible');
        setTimeout(() => toast.remove(), 500);
    }, 4000);
};

// ═══════════════════════════════════════════════════════════════
// SWEETALERT2 — Glass-styled confirmation
// ═══════════════════════════════════════════════════════════════
window.confirmAction = function (message = 'Are you sure?') {
    return Swal.fire({
        title: 'Confirm Action',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0ea5e9',
        cancelButtonColor: '#ef4444',
        confirmButtonText: 'Yes, proceed',
        cancelButtonText: 'Cancel',
        background: 'var(--glass-bg)',
        color: 'var(--color-text)',
        customClass: {
            popup: 'glass-card',
        }
    });
};

// ═══════════════════════════════════════════════════════════════
// 3D TILT EFFECT — For .tilt-card elements
// ═══════════════════════════════════════════════════════════════
function init3DTiltEffect() {
    // Disabled to improve rendering performance (layout thrashing on mousemove)
    return;
}

// ═══════════════════════════════════════════════════════════════
// RIPPLE BUTTON EFFECT — For .ripple-btn elements
// ═══════════════════════════════════════════════════════════════
function initRippleEffect() {
    document.querySelectorAll('.ripple-btn').forEach(btn => {
        btn.style.position = 'relative';
        btn.style.overflow = 'hidden';

        btn.addEventListener('click', function (e) {
            const rect = btn.getBoundingClientRect();
            const ripple = document.createElement('span');
            const size = Math.max(rect.width, rect.height);

            ripple.style.width = ripple.style.height = `${size}px`;
            ripple.style.left = `${e.clientX - rect.left - size / 2}px`;
            ripple.style.top = `${e.clientY - rect.top - size / 2}px`;
            ripple.classList.add('ripple');

            btn.appendChild(ripple);
            ripple.addEventListener('animationend', () => ripple.remove());
        });
    });
}

// ═══════════════════════════════════════════════════════════════
// ANIMATED COUNTER — Count up numbers on scroll
// ═══════════════════════════════════════════════════════════════
window.animateCounter = function (element, target, duration = 2000) {
    let start = 0;
    const step = (timestamp) => {
        if (!start) start = timestamp;
        const progress = Math.min((timestamp - start) / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3); // easeOutCubic
        const current = Math.floor(eased * target);
        element.textContent = current.toLocaleString();
        if (progress < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
};

function initCounters() {
    const counters = document.querySelectorAll('[data-counter]');
    if (!counters.length) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.dataset.counted) {
                entry.target.dataset.counted = 'true';
                const target = parseInt(entry.target.dataset.counter, 10);
                const suffix = entry.target.dataset.counterSuffix || '';
                const origAnimateCounter = (el, tgt, dur) => {
                    let startTime = 0;
                    const step = (timestamp) => {
                        if (!startTime) startTime = timestamp;
                        const progress = Math.min((timestamp - startTime) / dur, 1);
                        const eased = 1 - Math.pow(1 - progress, 3);
                        el.textContent = Math.floor(eased * tgt).toLocaleString() + suffix;
                        if (progress < 1) requestAnimationFrame(step);
                    };
                    requestAnimationFrame(step);
                };
                origAnimateCounter(entry.target, target, 2000);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(c => observer.observe(c));
}

// ═══════════════════════════════════════════════════════════════
// PARTICLES.JS — Initialize if container exists
// ═══════════════════════════════════════════════════════════════
function initParticles() {
    if (typeof particlesJS === 'undefined' || !document.getElementById('particles-js')) return;

    const isDark = document.documentElement.classList.contains('dark');

    particlesJS('particles-js', {
        particles: {
            number: { value: 50, density: { enable: true, value_area: 800 } },
            color: { value: isDark ? '#38bdf8' : '#0284c7' },
            shape: { type: 'circle' },
            opacity: { value: 0.3, random: true, anim: { enable: true, speed: 1, opacity_min: 0.1 } },
            size: { value: 3, random: true, anim: { enable: true, speed: 2, size_min: 0.5 } },
            line_linked: {
                enable: true,
                distance: 150,
                color: isDark ? '#38bdf8' : '#0284c7',
                opacity: 0.15,
                width: 1
            },
            move: { enable: true, speed: 1.5, direction: 'none', random: true, out_mode: 'out' }
        },
        interactivity: {
            detect_on: 'canvas',
            events: {
                onhover: { enable: true, mode: 'grab' },
                onclick: { enable: true, mode: 'push' },
                resize: true
            },
            modes: {
                grab: { distance: 140, line_linked: { opacity: 0.4 } },
                push: { particles_nb: 3 }
            }
        },
        retina_detect: true
    });
}

// ═══════════════════════════════════════════════════════════════
// AUTO-SAVE — For notes
// ═══════════════════════════════════════════════════════════════
window.autoSave = function (url, data, callback) {
    let timeout = null;
    return function () {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            axios.post(url, data())
                .then(response => { if (callback) callback(response); })
                .catch(error => console.error('Auto-save failed:', error));
        }, 1000);
    };
};

// ═══════════════════════════════════════════════════════════════
// AUDIO PLAYER
// ═══════════════════════════════════════════════════════════════
window.createAudioPlayer = function (audioUrl) {
    return {
        audio: new Audio(audioUrl),
        playing: false,
        currentTime: 0,
        duration: 0,
        speed: 1.0,
        init() {
            this.audio.addEventListener('loadedmetadata', () => { this.duration = this.audio.duration; });
            this.audio.addEventListener('timeupdate', () => { this.currentTime = this.audio.currentTime; });
            this.audio.addEventListener('ended', () => { this.playing = false; });
        },
        toggle() {
            this.playing ? this.audio.pause() : this.audio.play();
            this.playing = !this.playing;
        },
        setSpeed(speed) { this.speed = speed; this.audio.playbackRate = speed; },
        seek(time) { this.audio.currentTime = time; }
    };
};

// ═══════════════════════════════════════════════════════════════
// QUIZ TIMER
// ═══════════════════════════════════════════════════════════════
window.quizTimer = function (durationMinutes) {
    return {
        timeLeft: durationMinutes * 60,
        interval: null,
        init() { this.start(); },
        start() {
            this.interval = setInterval(() => {
                this.timeLeft--;
                if (this.timeLeft <= 0) { this.stop(); this.onTimeUp(); }
            }, 1000);
        },
        stop() { if (this.interval) clearInterval(this.interval); },
        get formattedTime() {
            const m = Math.floor(this.timeLeft / 60);
            const s = this.timeLeft % 60;
            return `${m}:${s.toString().padStart(2, '0')}`;
        },
        onTimeUp() { console.log('Time is up!'); }
    };
};

// ═══════════════════════════════════════════════════════════════
// PROGRESS TRACKER
// ═══════════════════════════════════════════════════════════════
window.progressTracker = function (lessonId, enrollmentId) {
    let startTime = Date.now();
    return {
        updateProgress(position) {
            axios.post(`/student/courses/${enrollmentId}/lessons/${lessonId}/progress`, {
                position, time_spent: Math.floor((Date.now() - startTime) / 1000)
            }).catch(error => console.error('Failed to update progress:', error));
        },
        markComplete() {
            return axios.post(`/student/courses/${enrollmentId}/lessons/${lessonId}/complete`)
                .then(response => { showNotification(response.data.message, 'success'); return response.data; })
                .catch(error => { showNotification('Failed to mark lesson as complete', 'error'); throw error; });
        }
    };
};

// ═══════════════════════════════════════════════════════════════
// CHART.JS HELPERS — Gradient fills
// ═══════════════════════════════════════════════════════════════
window.createLineChart = function (elementId, data, options = {}) {
    const ctx = document.getElementById(elementId);
    if (!ctx) return null;
    return new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { labels: { color: 'var(--color-text-muted)', font: { family: 'Inter' } } } },
            scales: {
                x: { ticks: { color: 'var(--color-text-muted)' }, grid: { color: 'var(--glass-border)' } },
                y: { ticks: { color: 'var(--color-text-muted)' }, grid: { color: 'var(--glass-border)' } },
            },
            ...options
        }
    });
};

window.createBarChart = function (elementId, data, options = {}) {
    const ctx = document.getElementById(elementId);
    if (!ctx) return null;
    return new Chart(ctx, {
        type: 'bar',
        data: data,
        options: { responsive: true, maintainAspectRatio: false, ...options }
    });
};

window.createDoughnutChart = function (elementId, data, options = {}) {
    const ctx = document.getElementById(elementId);
    if (!ctx) return null;
    return new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: { responsive: true, maintainAspectRatio: false, ...options }
    });
};

// ═══════════════════════════════════════════════════════════════
// MAIN INITIALIZER
// ═══════════════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {
    updateThemeIcons();
    init3DTiltEffect();
    initRippleEffect();
    initCounters();
    initParticles();
});