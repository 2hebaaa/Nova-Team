/**
 * Theme Switcher - Dark/Light Mode
 * Manages theme switching, persistence, and system preference detection
 */

class ThemeSwitcher {
    constructor() {
        this.STORAGE_KEY = 'lms_theme';
        this.DARK_MODE_CLASS = 'dark-mode';
        this.init();
    }

    /**
     * Initialize theme switcher
     */
    init() {
        this.loadTheme();
        this.attachEventListeners();
        this.setupMediaQuery();
    }

    /**
     * Load saved theme or detect system preference
     */
    loadTheme() {
        const savedTheme = localStorage.getItem(this.STORAGE_KEY);
        
        if (savedTheme) {
            // Use saved preference
            this.setTheme(savedTheme);
        } else {
            // Detect system preference
            this.detectSystemTheme();
        }
    }

    /**
     * Detect system theme preference
     */
    detectSystemTheme() {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            this.setTheme('dark');
        } else {
            this.setTheme('light');
        }
    }

    /**
     * Set theme (dark or light)
     */
    setTheme(theme) {
        const html = document.documentElement;
        
        if (theme === 'dark') {
            html.classList.add(this.DARK_MODE_CLASS);
            localStorage.setItem(this.STORAGE_KEY, 'dark');
            this.updateToggleButtons('dark');
        } else {
            html.classList.remove(this.DARK_MODE_CLASS);
            localStorage.setItem(this.STORAGE_KEY, 'light');
            this.updateToggleButtons('light');
        }
        
        // Fire custom event
        window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme } }));
    }

    /**
     * Toggle between dark and light mode
     */
    toggle() {
        const html = document.documentElement;
        const isDark = html.classList.contains(this.DARK_MODE_CLASS);
        this.setTheme(isDark ? 'light' : 'dark');
    }

    /**
     * Get current theme
     */
    getCurrentTheme() {
        return localStorage.getItem(this.STORAGE_KEY) || 'light';
    }

    /**
     * Update toggle button states
     */
    updateToggleButtons(theme) {
        document.querySelectorAll('[data-theme-toggle]').forEach(btn => {
            if (theme === 'dark') {
                btn.classList.remove('btn-outline-dark');
                btn.classList.add('btn-dark');
                btn.innerHTML = '<i class="fas fa-sun"></i> ' + t('common.lightMode');
            } else {
                btn.classList.remove('btn-dark');
                btn.classList.add('btn-outline-dark');
                btn.innerHTML = '<i class="fas fa-moon"></i> ' + t('common.darkMode');
            }
        });
    }

    /**
     * Attach event listeners to theme toggle buttons
     */
    attachEventListeners() {
        document.addEventListener('click', (e) => {
            if (e.target.closest('[data-theme-toggle]')) {
                this.toggle();
            }
        });
    }

    /**
     * Setup media query listener for system theme changes
     */
    setupMediaQuery() {
        if (!window.matchMedia) return;
        
        const darkModeQuery = window.matchMedia('(prefers-color-scheme: dark)');
        
        // Modern browsers
        if (darkModeQuery.addEventListener) {
            darkModeQuery.addEventListener('change', (e) => {
                // Only apply if user hasn't manually set a preference
                if (!localStorage.getItem(this.STORAGE_KEY)) {
                    this.setTheme(e.matches ? 'dark' : 'light');
                }
            });
        }
        // Legacy browsers
        else if (darkModeQuery.addListener) {
            darkModeQuery.addListener((e) => {
                if (!localStorage.getItem(this.STORAGE_KEY)) {
                    this.setTheme(e.matches ? 'dark' : 'light');
                }
            });
        }
    }
}

// Initialize theme switcher when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.themeSwitcher = new ThemeSwitcher();
});
