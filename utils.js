/**
 * API Utilities - Handles AJAX requests to backend APIs
 */

class API {
    constructor(baseUrl = '/api') {
        this.baseUrl = baseUrl;
        this.timeout = 30000;
    }

    /**
     * Generic API call method
     */
    async call(endpoint, method = 'GET', data = null) {
        const options = {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        };

        if (data && (method === 'POST' || method === 'PUT')) {
            options.body = JSON.stringify(data);
        }

        try {
            const response = await fetch(this.baseUrl + endpoint, options);
            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || `HTTP ${response.status}`);
            }

            return result;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }

    /**
     * POST request
     */
    async post(endpoint, data) {
        return this.call(endpoint, 'POST', data);
    }

    /**
     * GET request
     */
    async get(endpoint) {
        return this.call(endpoint, 'GET');
    }

    /**
     * PUT request
     */
    async put(endpoint, data) {
        return this.call(endpoint, 'PUT', data);
    }

    /**
     * DELETE request
     */
    async delete(endpoint) {
        return this.call(endpoint, 'DELETE');
    }
}

// Global API instance
const api = new API('/api');

/**
 * Notification System
 */
class Notification {
    static show(message, type = 'info', duration = 5000) {
        const id = 'notification-' + Date.now();
        const bgClass = {
            'success': 'alert-success',
            'error': 'alert-danger',
            'warning': 'alert-warning',
            'info': 'alert-info'
        }[type] || 'alert-info';

        const iconClass = {
            'success': 'fa-check-circle',
            'error': 'fa-exclamation-circle',
            'warning': 'fa-exclamation-triangle',
            'info': 'fa-info-circle'
        }[type] || 'fa-info-circle';

        const html = `
            <div id="${id}" class="alert ${bgClass} alert-dismissible fade show" role="alert">
                <i class="fas ${iconClass}"></i>
                <span>${message}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        let container = document.getElementById('notification-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'notification-container';
            container.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
            document.body.appendChild(container);
        }

        container.insertAdjacentHTML('beforeend', html);

        if (duration > 0) {
            setTimeout(() => {
                const element = document.getElementById(id);
                if (element) {
                    element.remove();
                }
            }, duration);
        }
    }

    static success(message, duration = 5000) {
        this.show(message, 'success', duration);
    }

    static error(message, duration = 5000) {
        this.show(message, 'error', duration);
    }

    static warning(message, duration = 5000) {
        this.show(message, 'warning', duration);
    }

    static info(message, duration = 5000) {
        this.show(message, 'info', duration);
    }
}

/**
 * Loading Overlay
 */
class Loading {
    static show(message = 'جاري التحميل... / Loading...') {
        let overlay = document.getElementById('loading-overlay');
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.id = 'loading-overlay';
            overlay.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9998;
            `;
            overlay.innerHTML = `
                <div style="background: var(--bg-primary); padding: 30px; border-radius: 10px; text-align: center;">
                    <div class="spinner-border mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p>${message}</p>
                </div>
            `;
            document.body.appendChild(overlay);
        }
        overlay.style.display = 'flex';
    }

    static hide() {
        const overlay = document.getElementById('loading-overlay');
        if (overlay) {
            overlay.style.display = 'none';
        }
    }
}

/**
 * Form Utilities
 */
class FormHelper {
    static getFormData(formElement) {
        const formData = new FormData(formElement);
        const data = {};
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }
        return data;
    }

    static clearForm(formElement) {
        formElement.reset();
    }

    static disableForm(formElement, disabled = true) {
        const inputs = formElement.querySelectorAll('input, textarea, select, button');
        inputs.forEach(input => {
            input.disabled = disabled;
        });
    }

    static setFormValues(formElement, values) {
        for (const [key, value] of Object.entries(values)) {
            const field = formElement.elements[key];
            if (field) {
                field.value = value;
            }
        }
    }
}

/**
 * Table Utilities
 */
class TableHelper {
    static renderTable(data, columns, containerSelector) {
        const container = document.querySelector(containerSelector);
        if (!container) return;

        if (data.length === 0) {
            container.innerHTML = `<div class="alert alert-info">${t('common.noData')}</div>`;
            return;
        }

        let html = '<table class="table table-hover"><thead><tr>';
        
        for (const col of columns) {
            html += `<th>${col.label}</th>`;
        }
        html += '</tr></thead><tbody>';

        for (const row of data) {
            html += '<tr>';
            for (const col of columns) {
                const value = this.getNestedValue(row, col.field);
                const rendered = col.render ? col.render(value, row) : value;
                html += `<td>${rendered}</td>`;
            }
            html += '</tr>';
        }

        html += '</tbody></table>';
        container.innerHTML = html;
    }

    static getNestedValue(obj, path) {
        return path.split('.').reduce((curr, prop) => curr?.[prop], obj);
    }

    static addRow(tableSelector, row, columns) {
        const table = document.querySelector(tableSelector);
        if (!table) return;

        const tbody = table.querySelector('tbody');
        if (!tbody) return;

        let html = '<tr>';
        for (const col of columns) {
            const value = this.getNestedValue(row, col.field);
            const rendered = col.render ? col.render(value, row) : value;
            html += `<td>${rendered}</td>`;
        }
        html += '</tr>';

        tbody.insertAdjacentHTML('beforeend', html);
    }

    static removeRow(tableSelector, rowIndex) {
        const table = document.querySelector(tableSelector);
        if (!table) return;

        const rows = table.querySelectorAll('tbody tr');
        if (rows[rowIndex]) {
            rows[rowIndex].remove();
        }
    }
}

/**
 * Date Utilities
 */
class DateHelper {
    static format(date, format = 'YYYY-MM-DD') {
        const d = new Date(date);
        const year = d.getFullYear();
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        const hours = String(d.getHours()).padStart(2, '0');
        const minutes = String(d.getMinutes()).padStart(2, '0');

        return format
            .replace('YYYY', year)
            .replace('MM', month)
            .replace('DD', day)
            .replace('HH', hours)
            .replace('mm', minutes);
    }

    static isToday(date) {
        const today = new Date();
        const d = new Date(date);
        return d.toDateString() === today.toDateString();
    }

    static daysUntil(date) {
        const today = new Date();
        const d = new Date(date);
        const diff = d - today;
        return Math.ceil(diff / (1000 * 60 * 60 * 24));
    }
}

/**
 * Validation Utilities
 */
class Validator {
    static email(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    static btuEmail(email) {
        const re = /^[a-zA-Z0-9_.]+\.\d{6}@btu\.edu\.eg$/;
        return re.test(email);
    }

    static required(value) {
        return value && value.trim().length > 0;
    }

    static minLength(value, length) {
        return value && value.length >= length;
    }

    static maxLength(value, length) {
        return value && value.length <= length;
    }

    static number(value) {
        return !isNaN(value) && isFinite(value);
    }

    static url(url) {
        try {
            new URL(url);
            return true;
        } catch {
            return false;
        }
    }
}

/**
 * Storage Utilities
 */
class Storage {
    static set(key, value) {
        localStorage.setItem(key, JSON.stringify(value));
    }

    static get(key, defaultValue = null) {
        const value = localStorage.getItem(key);
        return value ? JSON.parse(value) : defaultValue;
    }

    static remove(key) {
        localStorage.removeItem(key);
    }

    static clear() {
        localStorage.clear();
    }
}

// Export for use in modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { API, Notification, Loading, FormHelper, TableHelper, DateHelper, Validator, Storage };
}
