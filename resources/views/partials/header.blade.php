<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-uppercase" href="#">
            <i class="bi bi-speedometer2 me-2"></i> {{ __('dashboard.Admin Panel') }}
        </a>

        <div class="d-flex align-items-center text-white">
            {{-- Language Switcher --}}
            <div class="dropdown me-3">
                <a class="text-white dropdown-toggle" href="#" id="langMenu" data-bs-toggle="dropdown">
                    <i class="bi bi-translate"></i> {{ __('dashboard.Switch Language') }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item"
                            href="{{ route('lang_switch', 'en') }}">{{ __('dashboard.English') }}</a></li>
                    <li><a class="dropdown-item"
                            href="{{ route('lang_switch', 'ar') }}">{{ __('dashboard.Arabic') }}</a></li>
                </ul>
            </div>

            {{-- Notifications --}}
            <div class="dropdown me-3">
                <a href="#" id="notifMenuButton" class="text-white position-relative" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="bi bi-bell fs-5"></i>
                    <span id="notif-count"
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none">0</span>
                </a>
                <div id="notif-dropdown" class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="notifMenuButton"
                    data-bs-auto-close="outside" style="min-width: 320px; max-height: 420px; overflow-y: auto;">
                    <div class="d-flex align-items-center justify-content-between px-2 pb-2 border-bottom">
                        <h6 class="dropdown-header p-0 m-0">{{ __('dashboard.Notifications') }}</h6>
                        <button id="notif-mark-all" type="button"
                            class="btn btn-link btn-sm">{{ __('dashboard.Mark all as read') }}</button>
                    </div>
                    <div id="notif-list" class="pt-2"></div>
                </div>
            </div>

            {{-- User Menu --}}
            <div class="dropdown">
                <a class="text-white dropdown-toggle" href="#" id="userMenu" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name ?? 'Admin' }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">{{ __('dashboard.Profile') }}</a></li>
                    <li><a class="dropdown-item" href="#">{{ __('dashboard.Settings') }}</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-danger" href="#">{{ __('dashboard.Logout') }}</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const userId = @json(auth()->id());
            const listEl = document.getElementById('notif-list');
            const countEl = document.getElementById('notif-count');
            const dropdownEl = document.getElementById('notif-dropdown');
            const markAllBtn = document.getElementById('notif-mark-all');

            const renderNotifications = (notifications) => {
                if (!Array.isArray(notifications) || notifications.length === 0) {
                    listEl.innerHTML = '<div class="text-muted small p-2">{{ __('dashboard.No notifications') }}</div>';
                    return;
                }
                listEl.innerHTML = notifications.map(n => {
                    const data = n.data || {};
                    const title = data.title || 'Notification';
                    const body = data.body || '';
                    const time = n.time_ago || '';
                    return `<div class="border-bottom p-2">
                                                        <strong>${title}</strong><br>
                                                        <small>${body}</small><br>
                                                        <small class="text-muted">${time}</small>
                                                    </div>`;
                }).join('');
            };

            const updateBadge = (num) => {
                const safe = Number.isFinite(num) ? num : 0;
                countEl.textContent = safe > 99 ? '99+' : String(safe);
                countEl.classList.toggle('d-none', safe === 0);
            };

            const loadNotifications = async () => {
                try {
                    const res = await fetch(window.routes?.notificationsIndex || '/admin/notifications', {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (!res.ok) return;
                    const data = await res.json();
                    renderNotifications(data.notifications || []);
                    updateBadge(data.unread_count || 0);
                } catch (e) {
                    // swallow errors in UI
                }
            };

            const updateCount = async () => {
                try {
                    const res = await fetch(window.routes?.notificationsCount || '/admin/notifications/count', {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (!res.ok) return;
                    const data = await res.json();
                    updateBadge(data.count || 0);
                } catch (e) { }
            };

            // Init
            loadNotifications();
            setInterval(updateCount, 30000);

            // Realtime via Echo (if available)
            if (userId && window.Echo) {
                window.Echo.private(`App.Models.User.${userId}`)
                    .notification((notification) => {
                        // Prepend new item
                        const title = notification.title || 'Notification';
                        const body = notification.body || '';
                        const item = document.createElement('div');
                        item.classList.add('border-bottom', 'p-2');
                        item.innerHTML = `<strong>${title}</strong><br><small>${body}</small>`;
                        listEl.prepend(item);
                        // Increment badge
                        const current = parseInt(countEl.textContent || '0', 10) || 0;
                        updateBadge(current + 1);
                    });
            }

            // When dropdown opens, refresh list
            document.addEventListener('shown.bs.dropdown', (e) => {
                if (e.target && e.target.id === 'notifMenuButton') {
                    loadNotifications();
                }
            });

            // Close on ESC (Bootstrap handles it, but ensure any manual open state is cleared)
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    const dropdown = bootstrap.Dropdown.getInstance(document.getElementById('notifMenuButton'));
                    dropdown && dropdown.hide();
                }
            });

            // Mark all as read
            if (markAllBtn) {
                markAllBtn.addEventListener('click', async () => {
                    try {
                        const res = await fetch(window.routes?.notificationsReadAll || '/admin/notifications/read-all', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } });
                        if (res.ok) {
                            loadNotifications();
                            updateBadge(0);
                        }
                    } catch (_) { }
                });
            }
        });
    </script>
@endpush