/**
 * RetailTracker Pro - Simple Frontend Script
 * 
 * Handles: Theme toggle, Sidebar menu, User dropdown, Modals, Alerts
 */

document.addEventListener('DOMContentLoaded', function () {
    initTheme();
    initSidebar();
    initUserMenu();
    initAlerts();
});

// ==========================================
// DARK / LIGHT THEME TOGGLE
// ==========================================

function initTheme() {
    // Load saved theme or default to light
    var saved = localStorage.getItem('rt-theme') || 'light';
    applyTheme(saved);

    // Toggle theme when button is clicked
    var btn = document.getElementById('themeToggle');
    if (btn) {
        btn.addEventListener('click', function () {
            var current = localStorage.getItem('rt-theme');
            applyTheme(current === 'dark' ? 'light' : 'dark');
        });
    }
}

function applyTheme(theme) {
    if (theme === 'dark') {
        document.body.classList.add('dark-mode');
    } else {
        document.body.classList.remove('dark-mode');
    }
    document.documentElement.style.colorScheme = theme;
    localStorage.setItem('rt-theme', theme);
}

// ==========================================
// SIDEBAR (Mobile Menu)
// ==========================================

function initSidebar() {
    var toggle = document.getElementById('menuToggle');
    var sidebar = document.getElementById('appSidebar');
    var overlay = document.getElementById('sidebarOverlay');

    if (!toggle || !sidebar) return;

    // Open/close sidebar when hamburger is clicked
    toggle.addEventListener('click', function () {
        sidebar.classList.toggle('open');
        if (overlay) overlay.classList.toggle('show');
    });

    // Close sidebar when overlay is clicked
    if (overlay) {
        overlay.addEventListener('click', function () {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        });
    }
}

// ==========================================
// USER DROPDOWN MENU
// ==========================================

function initUserMenu() {
    var toggle = document.getElementById('userMenuToggle');
    var panel = document.getElementById('userPanel');

    if (!toggle || !panel) return;

    // Toggle dropdown when user name is clicked
    toggle.addEventListener('click', function (e) {
        e.stopPropagation();
        panel.classList.toggle('show');
    });

    // Close dropdown when clicking anywhere else
    document.addEventListener('click', function () {
        panel.classList.remove('show');
    });

    // Prevent closing when clicking inside the dropdown
    panel.addEventListener('click', function (e) {
        e.stopPropagation();
    });
}

// ==========================================
// AUTO-HIDE FLASH ALERTS
// ==========================================

function initAlerts() {
    // Auto-hide alerts after 6 seconds
    var alerts = document.querySelectorAll('.alert');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.style.opacity = '0';
            setTimeout(function () { alert.remove(); }, 300);
        }, 6000);
    });
}

// ==========================================
// MODAL (Open / Close)
// ==========================================

function showModal(id) {
    var modal = document.getElementById(id);
    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(id) {
    var modal = document.getElementById(id);
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }
}

// ==========================================
// TOAST NOTIFICATIONS (small popup messages)
// ==========================================

function showToast(message, type) {
    var container = document.getElementById('toastContainer');
    if (!container) return;

    var toast = document.createElement('div');
    toast.className = 'toast toast-' + (type || 'info');
    toast.innerHTML = '<span>' + message + '</span><button onclick="this.parentElement.remove()">&times;</button>';
    container.appendChild(toast);

    // Show animation
    setTimeout(function () { toast.classList.add('show'); }, 10);

    // Auto-remove after 4 seconds
    setTimeout(function () {
        toast.classList.remove('show');
        setTimeout(function () { toast.remove(); }, 300);
    }, 4000);
}
