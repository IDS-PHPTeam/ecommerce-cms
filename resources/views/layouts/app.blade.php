<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-theme="{{ $themeMode ?? 'light' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('cms.dashboard')) - {{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-colored.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo-colored.png') }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/admin.css') }}?v={{ time() }}" rel="stylesheet">
</head>
<body>
    @auth
    <div class="header">
        <div class="header-content">
            <div class="logo-container">
                <img src="{{ asset('images/logo.svg') }}" alt="تنور العصر" class="logo">
            </div>
            <div class="header-actions">
                @include('components.language-switcher')
                <button type="button" class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
                    <svg id="themeIconLight" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: {{ ($themeMode ?? 'light') === 'dark' ? 'none' : 'block' }};">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg id="themeIconDark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: {{ ($themeMode ?? 'light') === 'dark' ? 'block' : 'none' }};">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>
                <div class="user-dropdown">
                    <button class="user-menu-toggle" id="userMenuToggle" aria-label="User menu">
                        <div class="user-avatar">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <span class="user-name">{{ Auth::user()->first_name && Auth::user()->last_name ? Auth::user()->first_name . ' ' . Auth::user()->last_name : Auth::user()->name }}</span>
                        <svg class="dropdown-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="user-dropdown-menu" id="userDropdownMenu">
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="18" height="18" style="stroke: #099ecb;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="#099ecb" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>{{ __('cms.edit_profile') }}</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="dropdown-item-form">
                            @csrf
                            <button type="submit" class="dropdown-item logout-item">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="18" height="18" style="stroke: #099ecb;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="#099ecb" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>{{ __('cms.logout') }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="app-layout">
        <aside class="sidebar" id="sidebar">
            <nav class="sidebar-nav">
                <ul class="sidebar-menu">
                    <li class="sidebar-item">
                        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <span class="sidebar-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </span>
                            <span class="sidebar-text">{{ __('cms.dashboard') }}</span>
                        </a>
                    </li>
                    <li class="sidebar-item has-submenu {{ request()->routeIs('products.*') || request()->routeIs('categories.*') || request()->routeIs('attributes.*') ? 'active' : '' }}">
                        <a href="{{ route('products.index') }}" class="sidebar-link {{ request()->routeIs('products.*') || request()->routeIs('categories.*') || request()->routeIs('attributes.*') ? 'active' : '' }}" onclick="event.preventDefault(); toggleSubmenu(this); window.location.href='{{ route('products.index') }}';">
                            <span class="sidebar-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </span>
                            <span class="sidebar-text">{{ __('cms.products') }}</span>
                            <span class="sidebar-expand-icon">
                                <svg class="icon-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </a>
                        <ul class="sidebar-submenu {{ request()->routeIs('products.*') || request()->routeIs('categories.*') || request()->routeIs('attributes.*') ? 'active' : '' }}">
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('products.index') }}" class="sidebar-submenu-link {{ request()->routeIs('products.*') && !request()->routeIs('categories.*') && !request()->routeIs('attributes.*') ? 'active' : '' }}">
                                    {{ __('cms.all_products') }}
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('categories.index') }}" class="sidebar-submenu-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                                    {{ __('cms.categories') }}
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('attributes.index') }}" class="sidebar-submenu-link {{ request()->routeIs('attributes.*') ? 'active' : '' }}">
                                    {{ __('cms.attributes') }}
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('orders.index') }}" class="sidebar-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                            <span class="sidebar-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </span>
                            <span class="sidebar-text">{{ __('cms.orders') }}</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('media.index') }}" class="sidebar-link {{ request()->routeIs('media.*') ? 'active' : '' }}">
                            <span class="sidebar-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <span class="sidebar-text">{{ __('cms.media') }}</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('drivers.index') }}" class="sidebar-link {{ request()->routeIs('drivers.*') ? 'active' : '' }}">
                            <span class="sidebar-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <span class="sidebar-text">{{ __('cms.drivers') }}</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('customers.index') }}" class="sidebar-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                            <span class="sidebar-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </span>
                            <span class="sidebar-text">{{ __('cms.customers') }}</span>
                        </a>
                    </li>
                    <li class="sidebar-item has-submenu {{ request()->routeIs('settlements.*') ? 'active' : '' }}">
                        <a href="{{ route('settlements.history') }}" class="sidebar-link {{ request()->routeIs('settlements.*') ? 'active' : '' }}" onclick="event.preventDefault(); toggleSubmenu(this); window.location.href='{{ route('settlements.history') }}';">
                            <span class="sidebar-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <span class="sidebar-text">{{ __('cms.settlements_finance') }}</span>
                            <span class="sidebar-expand-icon">
                                <svg class="icon-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </a>
                        <ul class="sidebar-submenu {{ request()->routeIs('settlements.*') ? 'active' : '' }}">
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('settlements.history') }}" class="sidebar-submenu-link {{ request()->routeIs('settlements.history') ? 'active' : '' }}">
                                    {{ __('cms.settlement_history') }}
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('settlements.request') }}" class="sidebar-submenu-link {{ request()->routeIs('settlements.request') ? 'active' : '' }}">
                                    {{ __('cms.settlement_request') }}
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('settlements.discrepancy-reports') }}" class="sidebar-submenu-link {{ request()->routeIs('settlements.discrepancy-reports') || request()->routeIs('settlements.payout-summary') || request()->routeIs('settlements.commission-calculator') ? 'active' : '' }}">
                                    {{ __('cms.other') }}
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item has-submenu {{ request()->routeIs('admins.*') || request()->routeIs('roles-permissions.*') ? 'active' : '' }}">
                        <a href="{{ route('admins.index') }}" class="sidebar-link {{ request()->routeIs('admins.*') || request()->routeIs('roles-permissions.*') ? 'active' : '' }}" onclick="event.preventDefault(); toggleSubmenu(this); window.location.href='{{ route('admins.index') }}';">
                            <span class="sidebar-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </span>
                            <span class="sidebar-text">{{ __('cms.admins') }}</span>
                            <span class="sidebar-expand-icon">
                                <svg class="icon-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </a>
                        <ul class="sidebar-submenu {{ request()->routeIs('admins.*') || request()->routeIs('roles-permissions.*') ? 'active' : '' }}">
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admins.index') }}" class="sidebar-submenu-link {{ request()->routeIs('admins.*') ? 'active' : '' }}">
                                    {{ __('cms.all_admins') }}
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('roles-permissions.index') }}" class="sidebar-submenu-link {{ request()->routeIs('roles-permissions.*') ? 'active' : '' }}">
                                    {{ __('cms.roles_permissions') }}
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('audit-logs.index') }}" class="sidebar-link {{ request()->routeIs('audit-logs.*') ? 'active' : '' }}">
                            <span class="sidebar-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </span>
                            <span class="sidebar-text">{{ __('cms.audit_logs') }}</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('settings.index') }}" class="sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                            <span class="sidebar-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </span>
                            <span class="sidebar-text">{{ __('cms.settings') }}</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
        </aside>

        <main class="main-content">
            <div class="content-wrapper">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(isset($errors) && $errors->any())
                    <div class="alert alert-error">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
    @endauth

    @guest
    <div class="main-content guest">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>
    @endguest

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #1f2937;">{{ __('cms.confirm_delete') }}</h3>
                <button type="button" class="modal-close" id="closeModal">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <p id="deleteModalMessage" style="color: #374151; font-size: 1rem; line-height: 1.6;"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" id="cancelDelete" style="background-color: #6b7280; color: white;">{{ __('cms.cancel') }}</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">{{ __('cms.delete') }}</button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/confirm-delete.js') }}"></script>
    <script>
        // Theme Toggle Functionality - Initialize immediately to prevent flash
        (function() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme') || 'light';
            html.setAttribute('data-theme', currentTheme);
        })();
        
        // Theme Toggle Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            const html = document.documentElement;
            const themeIconLight = document.getElementById('themeIconLight');
            const themeIconDark = document.getElementById('themeIconDark');
            
            // Get current theme from data attribute
            let currentTheme = html.getAttribute('data-theme') || 'light';
            
            // Initialize icon display based on current theme
            function updateIcons(theme) {
                if (theme === 'dark') {
                    if (themeIconLight) themeIconLight.style.display = 'none';
                    if (themeIconDark) themeIconDark.style.display = 'block';
                } else {
                    if (themeIconLight) themeIconLight.style.display = 'block';
                    if (themeIconDark) themeIconDark.style.display = 'none';
                }
            }
            
            // Set initial icon state
            updateIcons(currentTheme);
            
            // Theme toggle handler
            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    // Toggle theme
                    currentTheme = currentTheme === 'light' ? 'dark' : 'light';
                    html.setAttribute('data-theme', currentTheme);
                    
                    // Update icons
                    updateIcons(currentTheme);
                    
                    // Save to server via AJAX
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (csrfToken) {
                        fetch('{{ route("settings.update-theme") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                theme_mode: currentTheme
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Theme updated to:', data.theme_mode);
                            }
                        })
                        .catch(error => {
                            console.error('Error updating theme:', error);
                        });
                    }
                });
            }
        });
    </script>
    <script>
        function toggleSubmenu(element) {
            const sidebarItem = element.closest('.sidebar-item');
            const submenu = sidebarItem.querySelector('.sidebar-submenu');
            
            if (submenu) {
                // Only open, don't close if already open
                if (!sidebarItem.classList.contains('active')) {
                    sidebarItem.classList.add('active');
                    submenu.classList.add('active');
                }
            }
        }

        // Ensure submenus are open when on related pages
        document.addEventListener('DOMContentLoaded', function() {
            const productsSubmenu = document.querySelector('.sidebar-item.has-submenu:has(.sidebar-submenu)');
            if (productsSubmenu) {
                const isProductsActive = window.location.pathname.includes('/products') || 
                                        window.location.pathname.includes('/categories') || 
                                        window.location.pathname.includes('/attributes');
                if (isProductsActive) {
                    productsSubmenu.classList.add('active');
                    const submenu = productsSubmenu.querySelector('.sidebar-submenu');
                    if (submenu) submenu.classList.add('active');
                }
            }

            const settlementsSubmenu = document.querySelectorAll('.sidebar-item.has-submenu')[1];
            if (settlementsSubmenu) {
                const isSettlementsActive = window.location.pathname.includes('/settlements');
                if (isSettlementsActive) {
                    settlementsSubmenu.classList.add('active');
                    const submenu = settlementsSubmenu.querySelector('.sidebar-submenu');
                    if (submenu) submenu.classList.add('active');
                }
            }

            const adminsSubmenu = document.querySelectorAll('.sidebar-item.has-submenu')[2];
            if (adminsSubmenu) {
                const isAdminsActive = window.location.pathname.includes('/admins') || 
                                      window.location.pathname.includes('/roles-permissions');
                if (isAdminsActive) {
                    adminsSubmenu.classList.add('active');
                    const submenu = adminsSubmenu.querySelector('.sidebar-submenu');
                    if (submenu) submenu.classList.add('active');
                }
            }
        });
    </script>
    @stack('scripts')
</body>
</html>

