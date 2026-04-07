<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LibraFlow') — Library Management</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['"Playfair Display"', 'Georgia', 'serif'],
                        sans: ['"DM Sans"', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        ink: {
                            50:  '#f5f3ef',
                            100: '#e8e3d8',
                            200: '#d1c9b5',
                            300: '#b5a98e',
                            400: '#9a8c6e',
                            500: '#7d7057',
                            600: '#635847',
                            700: '#4a4236',
                            800: '#312c24',
                            900: '#1a1712',
                        },
                        amber: {
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'DM Sans', sans-serif; background-color: #f5f3ef; }
        .font-display { font-family: 'Playfair Display', serif; }

        /* Sidebar */
        .sidebar { background: linear-gradient(160deg, #1a1712 0%, #312c24 60%, #4a4236 100%); }
        .nav-link { transition: all 0.2s; }
        .nav-link:hover, .nav-link.active {
            background: rgba(251,191,36,0.15);
            color: #fbbf24;
            border-left: 3px solid #fbbf24;
        }
        .nav-link { border-left: 3px solid transparent; }

        /* Cards */
        .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04); }
        .card-hover { transition: transform 0.2s, box-shadow 0.2s; }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 4px 20px rgba(0,0,0,0.1); }

        /* Badges */
        .badge-available   { background: #dcfce7; color: #166534; }
        .badge-limited     { background: #fef9c3; color: #854d0e; }
        .badge-unavailable { background: #fee2e2; color: #991b1b; }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white; font-weight: 600;
            transition: all 0.2s;
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(217,119,6,0.4); }
        .btn-danger { background: #fee2e2; color: #991b1b; }
        .btn-danger:hover { background: #fecaca; }

        /* Flash messages */
        .flash-success { background: #f0fdf4; border-left: 4px solid #22c55e; color: #166534; }
        .flash-error   { background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; }

        /* Form inputs */
        .form-input {
            border: 1.5px solid #d1c9b5;
            border-radius: 8px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-input:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245,158,11,0.15);
            outline: none;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #b5a98e; border-radius: 3px; }

        /* Page transitions */
        .page-content { animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

        /* Mobile sidebar */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s; }
            .sidebar.open { transform: translateX(0); }
        }
    </style>
</head>

<body class="min-h-screen">
<div class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="sidebar w-64 flex-shrink-0 flex flex-col overflow-y-auto z-30 fixed md:static h-full" id="sidebar">
        <!-- Logo -->
        <div class="p-6 border-b border-white/10">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-amber-500 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="font-display text-xl text-white font-semibold">LibraFlow</span>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-4 space-y-1 mt-2">
            <p class="text-xs text-ink-400 uppercase tracking-wider px-3 mb-3 font-semibold">Main Menu</p>

            <a href="{{ route('dashboard') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-ink-200 text-sm {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="w-4.5 h-4.5 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('books.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-ink-200 text-sm {{ request()->routeIs('books.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Books
            </a>

            <a href="#"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-ink-200 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Members
            </a>

            <a href="#"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-ink-200 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Borrowings
            </a>

            <a href="#"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-ink-200 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                Categories
            </a>
        </nav>

        <!-- User info at bottom -->
        <div class="p-4 border-t border-white/10">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-full bg-amber-500/20 flex items-center justify-center text-amber-400 font-semibold text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                    <p class="text-ink-400 text-xs truncate capitalize">{{ auth()->user()->role }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-ink-300 hover:text-red-400 text-sm rounded-lg hover:bg-red-500/10 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top bar -->
        <header class="bg-white border-b border-ink-100 px-6 py-4 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-4">
                <!-- Mobile menu toggle -->
                <button onclick="document.getElementById('sidebar').classList.toggle('open')"
                        class="md:hidden text-ink-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="font-display text-xl text-ink-800 font-semibold">@yield('page-title', 'Dashboard')</h1>
            </div>
            <div class="text-sm text-ink-400">
                {{ now()->format('l, F j, Y') }}
            </div>
        </header>

        <!-- Flash messages -->
        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="flash-success flex items-center gap-3 px-4 py-3 rounded-lg mb-0">
                    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="flash-error flex items-center gap-3 px-4 py-3 rounded-lg mb-0">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            @endif
        </div>

        <!-- Page content -->
        <main class="flex-1 overflow-y-auto p-6 page-content">
            @yield('content')
        </main>
    </div>
</div>

<!-- Overlay for mobile -->
<div onclick="document.getElementById('sidebar').classList.remove('open')"
     class="fixed inset-0 bg-black/50 z-20 hidden md:hidden" id="overlay"></div>

<script>
    document.getElementById('sidebar').addEventListener('transitionend', function() {
        const overlay = document.getElementById('overlay');
        overlay.style.display = this.classList.contains('open') ? 'block' : 'none';
    });
</script>
</body>
</html>
