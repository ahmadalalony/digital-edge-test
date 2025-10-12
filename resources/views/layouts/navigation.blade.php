@if (View::exists('partials.header'))
    @include('partials.header')
@else
    <nav class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('dashboard') }}" class="text-gray-900 font-semibold">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <div class="flex items-center gap-4">
                    @auth
                        <!-- Notifications Dropdown -->
                        <div class="relative">
                            <button id="notificationDropdown"
                                class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none">
                                <i class="bi bi-bell text-lg"></i>
                                <span id="notificationBadge"
                                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5 min-w-[20px] text-center hidden">0</span>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="notificationMenu"
                                class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-lg z-50 hidden">
                                <div class="p-4 border-b border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ __('dashboard.Notifications') }}</h3>
                                </div>
                                <div id="notificationsList" class="max-h-96 overflow-y-auto">
                                    <div class="p-4 text-center text-gray-500">
                                        {{ __('dashboard.No notifications') }}
                                    </div>
                                </div>
                                <div class="p-4 border-t border-gray-200">
                                    <button id="markAllRead" class="text-sm text-blue-600 hover:text-blue-800">
                                        {{ __('dashboard.Mark all as read') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="text-sm text-gray-600 hover:text-gray-900">{{ __('dashboard.Logout') }}</button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
@endif