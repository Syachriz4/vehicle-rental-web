<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title') - VELOCE Fleet Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;400;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-secondary": "#ffffff",
                        "surface-container-low": "#f3f3f3",
                        "tertiary-fixed": "#5d5f5f",
                        "secondary-fixed-dim": "#acabab",
                        "surface-variant": "#e2e2e2",
                        "on-surface": "#1a1c1c",
                        "on-secondary-fixed": "#1b1c1c",
                        "surface-bright": "#f9f9f9",
                        "secondary-container": "#d5d4d4",
                        "surface": "#f9f9f9",
                        "on-secondary-fixed-variant": "#3b3b3c",
                        "on-error": "#ffffff",
                        "surface-tint": "#5e5e5e",
                        "secondary": "#5e5e5e",
                        "surface-dim": "#dadada",
                        "tertiary": "#3a3c3c",
                        "on-tertiary-fixed": "#ffffff",
                        "inverse-on-surface": "#f1f1f1",
                        "secondary-fixed": "#c7c6c6",
                        "tertiary-fixed-dim": "#454747",
                        "surface-container-highest": "#e2e2e2",
                        "on-background": "#1a1c1c",
                        "on-tertiary-fixed-variant": "#e2e2e2",
                        "on-error-container": "#410002",
                        "tertiary-container": "#737575",
                        "surface-container-high": "#e8e8e8",
                        "on-primary": "#e2e2e2",
                        "inverse-primary": "#c6c6c6",
                        "on-secondary-container": "#1b1c1c",
                        "error-container": "#ffdad6",
                        "outline": "#777777",
                        "primary-fixed": "#5e5e5e",
                        "surface-container-lowest": "#ffffff",
                        "on-primary-container": "#ffffff",
                        "primary": "#000000",
                        "on-tertiary": "#e2e2e2",
                        "background": "#f9f9f9",
                        "error": "#ba1a1a",
                        "on-primary-fixed": "#ffffff",
                        "primary-fixed-dim": "#474747",
                        "outline-variant": "#c6c6c6",
                        "on-tertiary-container": "#ffffff",
                        "on-surface-variant": "#474747",
                        "inverse-surface": "#2f3131",
                        "surface-container": "#eeeeee",
                        "primary-container": "#3b3b3b",
                        "on-primary-fixed-variant": "#e2e2e2"
                    },
                    borderRadius: {
                        DEFAULT: "0.125rem",
                        lg: "0.25rem",
                        xl: "0.5rem",
                        full: "0.75rem"
                    },
                    fontFamily: {
                        headline: ["Manrope"],
                        body: ["Inter"],
                        label: ["Inter"]
                    }
                }
            }
        }
    </script>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f9f9f9; color: #1a1c1c; }
        .font-headline { font-family: 'Manrope', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24; }
        input:focus, select:focus, textarea:focus {
            outline: none !important;
            box-shadow: none !important;
        }
    </style>
</head>
<body class="bg-surface text-on-surface antialiased">

<!-- Sidebar Navigation -->
<aside class="fixed left-0 top-0 h-full w-64 bg-surface-container-lowest border-r border-surface-container-high flex flex-col py-8 z-50">
    <div class="px-8 mb-12">
        <h1 class="font-headline font-black text-2xl tracking-tighter text-primary">VELOCE</h1>
        <p class="text-[10px] uppercase tracking-[0.3em] text-on-surface-variant/60 font-bold mt-1">Fleet Manager v.2.0</p>
    </div>
    <nav class="flex-1 px-4 space-y-1">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('dashboard') ? 'bg-surface-container text-primary font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low' }} transition-all duration-300">
            <span class="material-symbols-outlined">dashboard</span>
            <span class="text-sm">Dashboard</span>
        </a>
        <a href="{{ route('bookings.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('bookings.*') ? 'bg-surface-container text-primary font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low' }} transition-all duration-300">
            <span class="material-symbols-outlined">event_note</span>
            <span class="text-sm">Bookings</span>
        </a>
        <a href="{{ route('vehicles.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('vehicles.*') ? 'bg-surface-container text-primary font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low' }} transition-all duration-300">
            <span class="material-symbols-outlined">directions_car</span>
            <span class="text-sm">Vehicles</span>
        </a>
        @if(auth()->user() && (auth()->user()->role === 'admin' || auth()->user()->role === 'approver'))
        <a href="{{ route('approvals.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('approvals.*') ? 'bg-surface-container text-primary font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low' }} transition-all duration-300">
            <span class="material-symbols-outlined">check_circle</span>
            <span class="text-sm">Approvals</span>
        </a>
        @endif
        @if(auth()->user() && auth()->user()->role === 'admin')
        <a href="{{ route('fuel-consumptions.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('fuel-consumptions.*') ? 'bg-surface-container text-primary font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low' }} transition-all duration-300">
            <span class="material-symbols-outlined">local_gas_station</span>
            <span class="text-sm">Fuel Management</span>
        </a>
        <a href="{{ route('service-schedules.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('service-schedules.*') ? 'bg-surface-container text-primary font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low' }} transition-all duration-300">
            <span class="material-symbols-outlined">build</span>
            <span class="text-sm">Service Logs</span>
        </a>
        <a href="{{ route('booking-reports.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('booking-reports.*') ? 'bg-surface-container text-primary font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low' }} transition-all duration-300">
            <span class="material-symbols-outlined">assessment</span>
            <span class="text-sm">Booking Reports</span>
        </a>
        @endif
        @if(auth()->user() && auth()->user()->role === 'admin')
        <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('users.*') ? 'bg-surface-container text-primary font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low' }} transition-all duration-300">
            <span class="material-symbols-outlined">people</span>
            <span class="text-sm">Users</span>
        </a>
        <a href="{{ route('departments.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('departments.*') ? 'bg-surface-container text-primary font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low' }} transition-all duration-300">
            <span class="material-symbols-outlined">corporate_fare</span>
            <span class="text-sm">Departments</span>
        </a>
        <a href="{{ route('activity-logs.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('activity-logs.*') ? 'bg-surface-container text-primary font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low' }} transition-all duration-300">
            <span class="material-symbols-outlined">description</span>
            <span class="text-sm">Activity Logs</span>
        </a>
        @endif
    </nav>
    <div class="px-4 mt-auto space-y-1">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:bg-surface-container-low transition-all">
                <span class="material-symbols-outlined">logout</span>
                <span class="text-sm">Sign Out</span>
            </button>
        </form>
    </div>
</aside>

<!-- Main Content Area -->
<main class="ml-64 min-h-screen">
    <!-- Top App Bar -->
    <header class="fixed top-0 right-0 left-64 bg-surface/80 backdrop-blur-xl h-20 flex justify-between items-center px-12 z-40 border-b border-surface-container-high">
        <div class="flex flex-col">
            <h2 class="text-xl font-headline font-bold text-primary">@yield('page-title', 'Dashboard')</h2>
            <p class="text-xs text-on-surface-variant font-medium">{{ auth()->user()->name ?? 'User' }}</p>
        </div>
        <div class="flex items-center gap-6">
            <!-- Search Bar -->
            <div class="relative">
                <form class="relative flex items-center bg-surface-container-low px-4 py-2 rounded-lg">
                    <span class="material-symbols-outlined text-on-surface-variant text-sm">search</span>
                    <input id="searchInput" class="bg-transparent border-none focus:ring-0 text-sm placeholder:text-on-surface-variant/50 w-48" placeholder="Search bookings, vehicles..." type="text" autocomplete="off"/>
                </form>
                <!-- Search Results Dropdown -->
                <div id="searchDropdown" class="absolute top-full left-0 mt-2 w-96 bg-surface-container-lowest border border-surface-container-high rounded-lg shadow-lg hidden z-50">
                    <div class="p-4 space-y-3 max-h-96 overflow-y-auto">
                        <!-- Results will be populated by JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Notifications & User Profile -->
            <div class="flex items-center gap-4">
                <!-- Notifications -->
                <div class="relative">
                    <button id="notificationsBtn" class="material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors relative">
                        notifications
                        <span id="notificationBadge" class="absolute -top-1 -right-1 w-4 h-4 bg-error rounded-full text-[8px] text-white font-bold flex items-center justify-center hidden">0</span>
                    </button>
                    <div id="notificationsDropdown" class="absolute top-full right-0 mt-2 w-80 bg-surface-container-lowest border border-surface-container-high rounded-lg shadow-lg hidden z-50">
                        <div class="p-4">
                            <h3 class="font-headline font-bold text-sm mb-3">Notifications</h3>
                            <div id="notificationsList" class="space-y-2 max-h-64 overflow-y-auto text-sm">
                                <!-- Notifications will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings (Just a placeholder for now) -->
                <button class="material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors">settings</button>

                <!-- Profile Dropdown -->
                <div class="relative">
                    <button id="profileBtn" class="w-8 h-8 bg-surface-container-highest rounded-lg overflow-hidden flex items-center justify-center hover:bg-surface-container-high transition-colors">
                        <span class="material-symbols-outlined text-sm">account_circle</span>
                    </button>
                    <div id="profileDropdown" class="absolute top-full right-0 mt-2 w-64 bg-surface-container-lowest border border-surface-container-high rounded-lg shadow-lg hidden z-50">
                        <div class="p-4 border-b border-surface-container-high">
                            <p class="font-headline font-bold text-sm">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-on-surface-variant">{{ auth()->user()->email }}</p>
                            <p class="text-xs text-on-surface-variant capitalize">Role: <span class="font-semibold">{{ auth()->user()->role }}</span></p>
                        </div>
                        <div class="p-2">
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-on-surface-variant hover:bg-surface-container-low transition-all text-sm">
                                    <span class="material-symbols-outlined text-base">logout</span>
                                    <span>Sign Out</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- JavaScript for Header Interactions -->
    <script>
        // Toggle Dropdowns
        document.getElementById('profileBtn').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('profileDropdown').classList.toggle('hidden');
            document.getElementById('notificationsDropdown').classList.add('hidden');
        });

        document.getElementById('notificationsBtn').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('notificationsDropdown').classList.toggle('hidden');
            document.getElementById('profileDropdown').classList.add('hidden');
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function() {
            document.getElementById('profileDropdown').classList.add('hidden');
            document.getElementById('notificationsDropdown').classList.add('hidden');
            document.getElementById('searchDropdown').classList.add('hidden');
        });

        // Search Functionality
        let searchTimeout;
        document.getElementById('searchInput').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            const query = e.target.value.trim();
            
            if (query.length < 2) {
                document.getElementById('searchDropdown').classList.add('hidden');
                return;
            }

            searchTimeout = setTimeout(function() {
                fetch(`/api/search?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        const dropdown = document.getElementById('searchDropdown');
                        const content = dropdown.querySelector('.p-4');
                        let html = '';

                        // Bookings
                        if (data.bookings.length > 0) {
                            html += '<div class="mb-3"><p class="text-xs font-bold text-on-surface-variant uppercase mb-2">Bookings</p>';
                            data.bookings.forEach(item => {
                                html += `<a href="${item.url}" class="block p-2 hover:bg-surface-container-low rounded text-sm cursor-pointer">
                                    <div class="font-medium">${item.title}</div>
                                    <span class="inline-block px-2 py-0.5 bg-primary text-on-primary text-[9px] rounded mt-1">${item.status}</span>
                                </a>`;
                            });
                            html += '</div>';
                        }

                        // Vehicles
                        if (data.vehicles.length > 0) {
                            html += '<div class="mb-3"><p class="text-xs font-bold text-on-surface-variant uppercase mb-2">Vehicles</p>';
                            data.vehicles.forEach(item => {
                                html += `<a href="${item.url}" class="block p-2 hover:bg-surface-container-low rounded text-sm cursor-pointer">
                                    <div class="font-medium">${item.title}</div>
                                </a>`;
                            });
                            html += '</div>';
                        }

                        // Users (admin only)
                        if (data.users.length > 0) {
                            html += '<div><p class="text-xs font-bold text-on-surface-variant uppercase mb-2">Users</p>';
                            data.users.forEach(item => {
                                html += `<a href="${item.url}" class="block p-2 hover:bg-surface-container-low rounded text-sm cursor-pointer">
                                    <div class="font-medium">${item.title}</div>
                                </a>`;
                            });
                            html += '</div>';
                        }

                        if (html === '') {
                            html = '<p class="text-sm text-on-surface-variant">No results found</p>';
                        }

                        content.innerHTML = html;
                        dropdown.classList.remove('hidden');
                    });
            }, 300);
        });

        // Load Notifications on page load
        function loadNotifications() {
            fetch('/api/notifications')
                .then(res => res.json())
                .then(data => {
                    const list = document.getElementById('notificationsList');
                    const badge = document.getElementById('notificationBadge');
                    
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.classList.remove('hidden');
                    }

                    if (data.notifications.length > 0) {
                        list.innerHTML = data.notifications.map(notif => `
                            <a href="${notif.url}" class="block p-2 hover:bg-surface-container-low rounded border-b border-surface-container-high last:border-0">
                                <p class="text-sm">${notif.message}</p>
                                <p class="text-xs text-on-surface-variant mt-1">${notif.time}</p>
                            </a>
                        `).join('');
                    } else {
                        list.innerHTML = '<p class="text-sm text-on-surface-variant">No notifications</p>';
                    }
                });
        }

        loadNotifications();
        // Refresh notifications every 30 seconds
        setInterval(loadNotifications, 30000);
    </script>

    <!-- Page Content -->
    <div class="pt-28 px-12 pb-24">
        @if (session('success'))
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg mb-6">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 rounded-lg mb-6">
                @foreach ($errors->all() as $error)
                    <p class="text-sm text-red-600">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @yield('content')
    </div>
</main>

</body>
</html>
