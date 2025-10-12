<div class="d-flex flex-column vh-100 p-3 text-white bg-dark" style="width: 250px;">
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-2">
            <a href="{{ route('admin_dashboard') }}"
                class="nav-link text-white {{ (request()->is('admin/dashboard*') || request()->routeIs('admin_dashboard')) ? 'active bg-primary' : '' }}">
                <i class="bi bi-bar-chart me-2"></i>{{ __('dashboard.Dashboard') }}
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="{{ route('admin_users_index') }}"
                class="nav-link text-white {{ request()->is('admin/users*') ? 'active bg-primary' : '' }}">
                <i class="bi bi-people me-2"></i>{{ __('dashboard.Users') }}
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="{{ route('admin_products_index') }}"
                class="nav-link text-white {{ request()->is('admin/products*') ? 'active bg-primary' : '' }}">
                <i class="bi bi-box-seam me-2"></i>{{ __('dashboard.Products') }}
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('admin_activity_logs_index') }}"
                class="nav-link text-white {{ request()->is('admin/activity-logs*') ? 'active bg-primary' : '' }}">
                <i class="bi bi-journal-text me-2"></i>{{ __('dashboard.Activity Logs') }}
            </a>
        </li>
    </ul>

    <hr class="border-secondary">

    <div class="text-center">
        <small class="text-muted">&copy; {{ date('Y') }} {{ __('dashboard.Admin Dashboard') }}</small>
    </div>
</div>