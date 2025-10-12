// Notifications functionality
class NotificationManager {
    constructor() {
        this.initializeElements();
        this.setupEventListeners();
        this.loadNotifications();
        this.setupPusher();

        // Auto refresh notifications every 30 seconds
        setInterval(() => {
            this.updateNotificationCount();
        }, 30000);
    }

    initializeElements() {
        this.dropdown = document.getElementById('notificationDropdown');
        this.menu = document.getElementById('notificationMenu');
        this.badge = document.getElementById('notificationBadge');
        this.list = document.getElementById('notificationsList');
        this.markAllBtn = document.getElementById('markAllRead');
    }

    setupEventListeners() {
        if (this.dropdown) {
            this.dropdown.addEventListener('click', (e) => {
                e.stopPropagation();
                this.toggleMenu();
            });
        }

        if (this.markAllBtn) {
            this.markAllBtn.addEventListener('click', () => {
                this.markAllAsRead();
            });
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!this.menu?.contains(e.target) && !this.dropdown?.contains(e.target)) {
                this.hideMenu();
            }
        });
    }

    setupPusher() {
        if (typeof window.Echo !== 'undefined' && window.Echo) {
            const userId = document.querySelector('meta[name="user-id"]')?.getAttribute('content');
            if (userId) {
                window.Echo.private(`App.Models.User.${userId}`)
                    .notification((notification) => {
                        this.handleNewNotification(notification);
                    });
            }
        }
    }

    toggleMenu() {
        if (this.menu.classList.contains('hidden')) {
            this.loadNotifications();
            this.showMenu();
        } else {
            this.hideMenu();
        }
    }

    showMenu() {
        this.menu.classList.remove('hidden');
    }

    hideMenu() {
        this.menu.classList.add('hidden');
    }

    async loadNotifications() {
        try {
            const response = await fetch('/admin/notifications', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.renderNotifications(data.notifications);
                this.updateBadge(data.unread_count);
            }
        } catch (error) {
            console.error('Error loading notifications:', error);
        }
    }

    async updateNotificationCount() {
        try {
            const response = await fetch('/admin/notifications/count', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.updateBadge(data.count);
            }
        } catch (error) {
            console.error('Error updating notification count:', error);
        }
    }

    renderNotifications(notifications) {
        if (!this.list) return;

        if (notifications.length === 0) {
            this.list.innerHTML = `
                <div class="p-4 text-center text-gray-500">
                    ${window.translations?.no_notifications || 'No notifications'}
                </div>
            `;
            return;
        }

        this.list.innerHTML = notifications.map(notification => {
            const isUnread = !notification.read_at;
            const data = notification.data;

            return `
                <div class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer ${isUnread ? 'bg-blue-50' : ''}" 
                     data-notification-id="${notification.id}" 
                     onclick="notificationManager.markAsRead('${notification.id}')">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-900 ${isUnread ? 'font-semibold' : ''}">
                                ${data.title || 'Notification'}
                            </h4>
                            <p class="text-sm text-gray-600 mt-1">
                                ${data.body || data.message || 'New notification'}
                            </p>
                            <p class="text-xs text-gray-400 mt-2">
                                ${notification.time_ago}
                            </p>
                        </div>
                        ${isUnread ? '<div class="w-2 h-2 bg-blue-500 rounded-full ml-2 mt-1"></div>' : ''}
                    </div>
                </div>
            `;
        }).join('');
    }

    updateBadge(count) {
        if (!this.badge) return;

        if (count > 0) {
            this.badge.textContent = count > 99 ? '99+' : count;
            this.badge.classList.remove('hidden');
        } else {
            this.badge.classList.add('hidden');
        }
    }

    async markAsRead(notificationId) {
        try {
            const response = await fetch(`/admin/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                // Refresh notifications
                this.loadNotifications();
            }
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }

    async markAllAsRead() {
        try {
            const response = await fetch('/admin/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                this.loadNotifications();
            }
        } catch (error) {
            console.error('Error marking all notifications as read:', error);
        }
    }

    handleNewNotification(notification) {
        // Show browser notification if permission granted
        if (Notification.permission === 'granted') {
            new Notification(notification.title || 'New Notification', {
                body: notification.body || notification.message,
                icon: '/favicon.ico'
            });
        }

        // Update badge and reload notifications
        this.updateNotificationCount();

        // If menu is open, reload notifications
        if (!this.menu.classList.contains('hidden')) {
            this.loadNotifications();
        }
    }

    // Request notification permission
    static requestNotificationPermission() {
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    window.notificationManager = new NotificationManager();
    NotificationManager.requestNotificationPermission();
});

// Export for global access
window.NotificationManager = NotificationManager;
