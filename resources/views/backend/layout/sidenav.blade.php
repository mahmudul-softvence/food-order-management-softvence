<nav class="navbar navbar-dark navbar-theme-primary px-4 col-12 d-lg-none">
    <a class="navbar-brand me-lg-5" href="{{ route('dashboard') }}">
        <img class="navbar-brand-dark" src="{{ asset('backend/uploads/logo.png') }}" alt="Volt logo" />
    </a>
    <div class="d-flex align-items-center">
        <button class="navbar-toggler d-lg-none collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<nav id="sidebarMenu" class="sidebar d-lg-block bg-gray-800 text-white collapse" data-simplebar>
    <div class="sidebar-inner px-4 pt-3">

        <div
            class="user-card d-flex d-md-none align-items-center justify-content-between justify-content-md-center pb-4">
            <div class="d-flex align-items-center">
                <div class="avatar-lg me-4">
                    <img src="{{ asset('backend/assets/img/team/profile-picture-3.jpg') }}"
                        class="card-img-top rounded-circle border-white" alt="Bonnie Green">
                </div>
                <div class="d-block">
                    <h2 class="h5 mb-3">{{ __('messages.hi') }}, {{ Auth::user()->name }}</h2>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="btn btn-secondary btn-sm d-inline-flex align-items-center">
                        <i class="bi bi-box-arrow-right icon-xxs me-1"></i>
                        {{ __('messages.logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
            <div class="collapse-close d-md-none">
                <a href="#sidebarMenu" data-bs-toggle="collapse" data-bs-target="#sidebarMenu"
                    aria-controls="sidebarMenu" aria-expanded="true" aria-label="Toggle navigation">
                    <i class="bi bi-x-lg icon-xs"></i>
                </a>
            </div>
        </div>

        <a class="logo d-none d-md-block">
            <img src="{{ asset('backend/uploads/logo.png') }}" alt="Logo" class="w-100 logo-white">
        </a>

        <ul class="nav flex-column pt-3 pt-md-0">

            @php
                $hasTodayMeal = \App\Models\TodayMeal::whereHas('food', function ($q) {
                    $q->where('user_id', \Illuminate\Support\Facades\Auth::id());
                })
                    ->whereDate('created_at', now()->toDateString())
                    ->exists();
            @endphp

            @if (
                !$hasTodayMeal &&
                    auth()->user()->hasAnyRole(['admin', 'vendor']))
                <li class="nav-item mb-3">
                    <a href="{{ route('vendor.food') }}" class="nav-link bg-warning text-primary">
                        <span class="sidebar-icon text-primary">
                            <i class="bi bi-egg-fried me-2"></i>
                        </span>
                        <span class="sidebar-text">{{ __('messages.set_meal') }}</span>
                    </a>
                </li>
            @endif

            @can('dashboard')
                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <span class="sidebar-icon"><i class="bi bi-speedometer2 me-2"></i></span>
                        <span class="sidebar-text">{{ __('messages.dashboard') }}</span>
                    </a>
                </li>
            @endcan



            @can('vendor.order.status')
                <li role="separator" class="dropdown-divider mt-4 mb-3 border-gray-700"></li>

                <li class="nav-item">
                    <span
                        class="nav-link collapsed d-flex justify-content-between align-items-center @if (Route::is('vendor.orders.status')) active @endif"
                        data-bs-toggle="collapse" data-bs-target="#submenu-orders">

                        <span>
                            <span class="sidebar-icon"><i class="bi bi-bag-check me-2"></i></span>
                            <span class="sidebar-text">{{ __('messages.orders') }}</span>
                        </span>

                        <span class="link-arrow"><i class="bi bi-chevron-right icon-sm"></i></span>
                    </span>

                    <div class="multi-level collapse @if (Route::is('vendor.orders.status')) show @endif" id="submenu-orders">
                        <ul class="flex-column nav">

                            <li class="nav-item {{ Request::is('*/vendor/orders/pending') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('vendor.orders.status', 'pending') }}">
                                    {{ __('messages.pending') }}
                                </a>
                            </li>

                            <li class="nav-item {{ Request::is('*/vendor/orders/delivered') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('vendor.orders.status', 'delivered') }}">
                                    {{ __('messages.delivered') }}
                                </a>
                            </li>

                            <li class="nav-item {{ Request::is('*/vendor/orders/received') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('vendor.orders.status', 'received') }}">
                                    {{ __('messages.received') }}
                                </a>
                            </li>



                            <li class="nav-item {{ Request::is('*/vendor/orders/cancelled') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('vendor.orders.status', 'cancelled') }}">
                                    {{ __('messages.cancelled') }}
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>
            @endcan

            @can('food_category.view')
                <li class="nav-item {{ request()->routeIs('food_category*') ? 'active' : '' }}">
                    <a href="{{ route('food_category') }}" class="nav-link">
                        <span class="sidebar-icon"><i class="bi bi-egg-fried me-2"></i></span>
                        <span class="sidebar-text">{{ __('messages.food_categories') }}</span>
                    </a>
                </li>
            @endcan

            @can('vendor.food.view')
                <li class="nav-item {{ request()->routeIs('vendor.food*') ? 'active' : '' }}">
                    <a href="{{ route('vendor.food') }}" class="nav-link">
                        <span class="sidebar-icon"><i class="bi bi-basket3 me-2"></i></span>
                        <span class="sidebar-text">{{ __('messages.food') }}</span>
                    </a>
                </li>
            @endcan


            @can('vendor.meal_history')
                <li class="nav-item {{ request()->routeIs('vendor.meal.history') ? 'active' : '' }}">
                    <a href="{{ route('vendor.meal.history') }}" class="nav-link">
                        <span class="sidebar-icon"><i class="bi bi-calendar-check me-2"></i></span>
                        <span class="sidebar-text">{{ __('messages.meal_history') }}</span>
                    </a>
                </li>
            @endcan


            @can('vendor.payment_history')
                <li class="nav-item {{ request()->routeIs('vendor.meal.payment_history') ? 'active' : '' }}">
                    <a href="{{ route('vendor.meal.payment_history') }}" class="nav-link">
                        <span class="sidebar-icon"><i class="bi bi-credit-card me-2"></i></span>
                        <span class="sidebar-text">{{ __('messages.payment_history') }}</span>
                    </a>
                </li>
            @endcan

            <li role="separator" class="dropdown-divider mt-4 mb-3 border-gray-700"></li>


            @can('employee.make_order')
                <li class="nav-item {{ request()->routeIs('order.make_order') ? 'active' : '' }}">
                    <a href="{{ route('order.make_order') }}" class="nav-link">
                        <span class="sidebar-icon"><i class="bi bi-egg-fried me-2"></i></span>
                        <span class="sidebar-text">{{ __('messages.make_order') }}</span>
                    </a>
                </li>
            @endcan

            @can('employee.order.status')
                <li class="nav-item">
                    <span
                        class="nav-link collapsed d-flex justify-content-between align-items-center
                        @if (Route::is('employee.orders.status')) active @endif"
                        data-bs-toggle="collapse" data-bs-target="#submenu-employee-orders">

                        <span>
                            <span class="sidebar-icon"><i class="bi bi-bag-check me-2"></i></span>
                            <span class="sidebar-text">{{ __('messages.orders') }}</span>
                        </span>

                        <span class="link-arrow"><i class="bi bi-chevron-right icon-sm"></i></span>
                    </span>

                    <div class="multi-level collapse @if (Route::is('employee.orders.status')) show @endif"
                        id="submenu-employee-orders">
                        <ul class="flex-column nav">

                            <li class="nav-item {{ Request::is('*/employee/orders/pending') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('employee.orders.status', 'pending') }}">
                                    {{ __('messages.pending') }}
                                </a>
                            </li>

                            <li class="nav-item {{ Request::is('*/employee/orders/delivered') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('employee.orders.status', 'delivered') }}">
                                    {{ __('messages.delivered') }}
                                </a>
                            </li>

                            <li class="nav-item {{ Request::is('*/employee/orders/received') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('employee.orders.status', 'received') }}">
                                    {{ __('messages.received') }}
                                </a>
                            </li>

                            <li class="nav-item {{ Request::is('*/employee/orders/cancelled') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('employee.orders.status', 'cancelled') }}">
                                    {{ __('messages.cancelled') }}
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>
            @endcan


            @can('employee.payment_history')
                <li class="nav-item {{ request()->routeIs('employee.payment_history') ? 'active' : '' }}">
                    <a href="{{ route('employee.payment_history') }}" class="nav-link">
                        <span class="sidebar-icon"><i class="bi bi-clock-history me-2"></i></span>
                        <span class="sidebar-text">{{ __('messages.payment_history') }}</span>
                    </a>
                </li>
            @endcan

            @can('user.view')
                <li role="separator" class="dropdown-divider mt-4 mb-3 border-gray-700"></li>

                <li class="nav-item {{ request()->routeIs('users*') ? 'active' : '' }}">
                    <a href="{{ route('users') }}" class="nav-link">
                        <span class="sidebar-icon"><i class="bi bi-person-plus me-2"></i></span>
                        <span class="sidebar-text">{{ __('messages.create_user') }}</span>
                    </a>
                </li>
            @endcan

            @can('team.view')
                <li class="nav-item {{ request()->routeIs('teams*') ? 'active' : '' }}">
                    <a href="{{ route('teams') }}" class="nav-link">
                        <span class="sidebar-icon"><i class="bi bi-people me-2"></i></span>
                        <span class="sidebar-text">{{ __('messages.create_team') }}</span>
                    </a>
                </li>
            @endcan

            @can('role.view')
                <li class="nav-item {{ request()->routeIs('roles') ? 'active' : '' }}">
                    <a href="{{ route('roles') }}" class="nav-link">
                        <span class="sidebar-icon"><i class="bi bi-shield-check me-2"></i></span>
                        <span class="sidebar-text">{{ __('messages.roles') }}</span>
                    </a>
                </li>
            @endcan


            <li role="separator" class="dropdown-divider mt-4 mb-3 border-gray-700"></li>


            @can('settings.view')
                <li class="nav-item {{ request()->routeIs('settings*') ? 'active' : '' }}">
                    <a href="{{ route('settings') }}" class="nav-link">
                        <span class="sidebar-icon"><i class="bi bi-gear me-2"></i></span>
                        <span class="sidebar-text">{{ __('messages.settings') }}</span>
                    </a>
                </li>
            @endcan

            @can('profile.update')
                <li class="nav-item {{ request()->routeIs('profile') ? 'active' : '' }}">
                    <a href="{{ route('profile') }}" class="nav-link">
                        <span class="sidebar-icon"><i class="bi bi-person-circle me-2"></i></span>
                        <span class="sidebar-text">{{ __('messages.profile') }}</span>
                    </a>
                </li>
            @endcan





        </ul>
    </div>
</nav>
