<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-uppercase" href="#">
            <i class="bi bi-speedometer2 me-2"></i> {{ __('dashboard.Admin Panel') }}
        </a>

        <div class="d-flex align-items-center text-white">
            {{-- Language Switcher --}}
            <div class="dropdown me-3">
                <a class="text-white dropdown-toggle" href="#" id="langMenu" data-bs-toggle="dropdown">
                    <i class="bi bi-translate"></i> {{ __('dashboard.Language') }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item"
                            href="{{ route('lang.switch', 'en') }}">{{ __('dashboard.English') }}</a></li>
                    <li><a class="dropdown-item"
                            href="{{ route('lang.switch', 'ar') }}">{{ __('dashboard.Arabic') }}</a></li>
                </ul>
            </div>

            {{-- Notifications --}}
            <a href="#" class="text-white me-3 position-relative">
                <i class="bi bi-bell fs-5"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
            </a>

            {{-- User Menu --}}
            <div class="dropdown">
                <a class="text-white dropdown-toggle" href="#" id="userMenu" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle me-1"></i> Admin
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