<div class="d-flex flex-column vh-100 p-3 text-white" style="width: 250px;">
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-2">
            <a href="/admin/dashboard"
                class="nav-link text-white {{ request()->is('dashboard*') ? 'active bg-primary' : '' }}">
                <i class="bi bi-bar-chart me-2"></i>{{ __('dashboard.Dashboard') }}
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="/admin/users" class="nav-link text-white {{ request()->is('users*') ? 'active bg-primary' : '' }}">
                <i class="bi bi-people me-2"></i>{{ __('dashboard.Users') }}
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="/admin/products"
                class="nav-link text-white {{ request()->is('products*') ? 'active bg-primary' : '' }}">
                <i class="bi bi-box-seam me-2"></i>{{ __('dashboard.Products') }}
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="/admin/gallery"
                class="nav-link text-white {{ request()->is('gallery*') ? 'active bg-primary' : '' }}">
                <i class="bi bi-images me-2"></i>{{ __('dashboard.Gallery') }}
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="/admin/activity-logs"
                class="nav-link text-white {{ request()->is('activity-logs*') ? 'active bg-primary' : '' }}">
                <i class="bi bi-journal-text me-2"></i>{{ __('dashboard.Activity Logs') }}
            </a>
        </li>
    </ul>

    <hr class="border-secondary">

    <div class="text-center">
        <small class="text-muted">&copy; {{ date('Y') }} {{ __('dashboard.Admin Dashboard') }}</small>
    </div>
</div>