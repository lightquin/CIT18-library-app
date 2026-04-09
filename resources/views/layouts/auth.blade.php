<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — LibraFlow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
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
                            50:  '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
        
        /* Updated: Blue-focused Navy background */
        .auth-bg {
            background-color: #0f172a;
            background-image: 
                radial-gradient(circle at top, rgba(59, 130, 246, 0.15), transparent 45%),
                linear-gradient(160deg, #0f172a 0%, #1e293b 55%, #334155 100%);
            background-size: auto, cover;
            background-position: center, center;
        }

        .form-input {
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            transition: all 0.2s;
            font-family: 'DM Sans', sans-serif;
        }
        
        /* Updated: Focus states are now Blue */
        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            transition: all 0.2s;
        }
        .btn-primary:hover { 
            transform: translateY(-1px); 
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3); 
        }

        .card-auth { animation: slideUp 0.4s ease; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        .pattern {
            background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.04) 1px, transparent 0);
            background-size: 28px 28px;
        }
    </style>
</head>
<body class="min-h-screen flex">

    <div class="hidden lg:flex lg:w-1/2 auth-bg pattern flex-col justify-between p-12 text-white">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <span class="font-display text-2xl text-white font-semibold tracking-tight">LibraFlow</span>
        </div>

        <div>
            <h2 class="font-display text-4xl text-white font-semibold leading-tight mb-4">
                The intelligent way to<br>manage your library.
            </h2>
            <p class="text-blue-100/70 text-lg leading-relaxed max-w-md">
                Streamline your operations, empower your members, and digitize your collection with ease.
            </p>

            <ul class="mt-8 space-y-3">
                @foreach(['Comprehensive book catalog management', 'Member tracking & borrowing history', 'Smart search and filtering', 'Overdue alerts & fine management'] as $feature)
                <li class="flex items-center gap-3 text-blue-50/80 text-sm">
                    <span class="w-5 h-5 rounded-full bg-blue-500/20 flex items-center justify-center flex-shrink-0 border border-blue-500/30">
                        <svg class="w-3 h-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                    {{ $feature }}
                </li>
                @endforeach
            </ul>
        </div>

        <p class="text-slate-500 text-xs font-medium">© {{ date('Y') }} LibraFlow. All rights reserved.</p>
    </div>

    <div class="flex-1 flex items-center justify-center p-8 bg-slate-50">
        <div class="w-full max-w-md card-auth">
            <div class="lg:hidden flex items-center gap-3 mb-8 justify-center">
                <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="font-display text-2xl text-slate-900 font-semibold">LibraFlow</span>
            </div>

            @yield('content')
        </div>
    </div>
</body>
</html>