<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - {{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fadeIn': 'fadeIn 0.5s ease-in-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    },
                }
            }
        }
    </script>

    <style>
        :root {
            --primary: #06b6d4;
            --primary-darker: #0891b2;
            --secondary: #818cf8;
            --bg-dark: #0f172a;
            --text-light: #f8fafc;
            --text-muted: #94a3b8;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        /* Loading Screen */
        #loading-screen {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.3s ease-in-out;
        }

        #loading-screen.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .loader {
            width: 80px;
            height: 80px;
            border: 4px solid #ffffff20;
            border-radius: 50%;
            position: relative;
            animation: loader-scale 1s ease-out infinite;
        }

        .loader:after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            border: 4px solid transparent;
            border-top-color: var(--primary);
            animation: loader-spin 1s linear infinite;
        }

        @keyframes loader-spin {
            to { transform: rotate(360deg); }
        }

        @keyframes loader-scale {
            0% { transform: scale(0.8); opacity: 0.5; }
            50% { transform: scale(1); opacity: 1; }
            100% { transform: scale(0.8); opacity: 0.5; }
        }

        /* Enhanced UI Components */
        .admin-card {
            @apply bg-white rounded-xl shadow-lg border border-slate-100;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .admin-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(148,163,184,0.15);
        }
        .admin-btn {
            @apply px-4 py-2 rounded-lg font-medium transition-all duration-200;
        }
        .admin-btn-primary {
            @apply bg-gradient-to-r from-cyan-500 to-blue-500 text-white hover:from-cyan-600 hover:to-blue-600;
        }
        .admin-input {
            @apply rounded-lg border-slate-200 focus:border-cyan-500 focus:ring focus:ring-cyan-200 transition-shadow duration-200;
        }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50">
    <!-- Loading Screen -->
    <div id="loading-screen">
        <div class="text-center">
            <div class="loader mb-4"></div>
            <div class="text-slate-200 font-medium">Loading...</div>
        </div>
    </div>

    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-72 bg-gradient-to-b from-slate-800 to-slate-900 text-white border-r border-slate-800/50 shadow-xl">
            <div class="p-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center shadow-lg">
                        <i class="fas fa-spa text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold tracking-tight">{{ config('app.name', 'Laravel') }}</h1>
                        <p class="text-xs text-slate-400">Admin Dashboard</p>
                    </div>
                </div>
            </div>
            
            <nav class="mt-6 px-3">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-4 py-3 mb-2 rounded-xl text-sm font-medium transition-all duration-200
                          {{ request()->routeIs('admin.dashboard') 
                             ? 'bg-gradient-to-r from-cyan-500/10 to-blue-500/10 text-white' 
                             : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="fas fa-chart-line w-5"></i>
                    <span class="ml-3">Dashboard</span>
                </a>

                <a href="{{ route('admin.bookings') }}" 
                   class="flex items-center px-4 py-3 mb-2 rounded-xl text-sm font-medium transition-all duration-200
                          {{ request()->routeIs('admin.bookings*') 
                             ? 'bg-gradient-to-r from-cyan-500/10 to-blue-500/10 text-white' 
                             : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="fas fa-calendar-alt w-5"></i>
                    <span class="ml-3">Bookings</span>
                </a>

                <a href="{{ route('admin.services') }}" 
                   class="flex items-center px-4 py-3 mb-2 rounded-xl text-sm font-medium transition-all duration-200
                          {{ request()->routeIs('admin.services') 
                             ? 'bg-gradient-to-r from-cyan-500/10 to-blue-500/10 text-white' 
                             : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="fas fa-spa w-5"></i>
                    <span class="ml-3">Services</span>
                </a>

                <a href="{{ route('admin.gallery') }}" 
                   class="flex items-center px-4 py-3 mb-2 rounded-xl text-sm font-medium transition-all duration-200
                          {{ request()->routeIs('admin.gallery') 
                             ? 'bg-gradient-to-r from-cyan-500/10 to-blue-500/10 text-white' 
                             : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="fas fa-images w-5"></i>
                    <span class="ml-3">Gallery</span>
                </a>

                <div class="mt-6 px-3 py-4 border-t border-slate-700/50">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="flex w-full items-center px-4 py-2 rounded-lg text-sm font-medium text-slate-400 
                                       hover:bg-white/5 hover:text-white transition-all duration-200">
                            <i class="fas fa-sign-out-alt w-5"></i>
                            <span class="ml-3">Logout</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 bg-slate-900">
            <!-- Header -->
            <header class="bg-white/5 backdrop-blur-xl border-b border-slate-800/50">
                <div class="px-8 py-5 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-white tracking-tight">@yield('header', 'Dashboard')</h2>
                    
                    <!-- Header Actions -->
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button class="p-2 text-slate-400 hover:text-white transition-colors duration-200">
                                <i class="fas fa-bell text-lg"></i>
                                <span class="absolute top-0 right-0 w-2 h-2 bg-cyan-400 rounded-full"></span>
                            </button>
                        </div>
                        
                        <div class="flex items-center space-x-3 pl-4 border-l border-slate-800/50">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center">
                                <i class="fas fa-user-circle text-white"></i>
                            </div>
                            <div class="hidden sm:block">
                                <p class="text-sm font-medium text-white">Admin User</p>
                                <p class="text-xs text-slate-400">administrator</p>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-8">
                @if(session('success'))
                    <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-lg mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="animate-fadeIn">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    @livewireScripts

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Loading screen management
            const loadingScreen = document.getElementById('loading-screen');
            
            // Hide loading screen after page loads
            setTimeout(() => {
                loadingScreen.classList.add('hidden');
            }, 500);

            // Show loading screen on navigation
            document.addEventListener('livewire:loading', () => {
                loadingScreen.classList.remove('hidden');
            });

            document.addEventListener('livewire:load', () => {
                loadingScreen.classList.add('hidden');
            });

            // Show loading screen on form submissions
            document.addEventListener('submit', () => {
                loadingScreen.classList.remove('hidden');
            });

            // Add loading screen for link clicks
            document.querySelectorAll('a').forEach(link => {
                if (!link.hasAttribute('wire:click')) {
                    link.addEventListener('click', () => {
                        loadingScreen.classList.remove('hidden');
                    });
                }
            });

            // Livewire file upload events (handle start/finish/error so loader doesn't get stuck)
            document.addEventListener('livewire-upload-start', () => {
                // show loader when upload begins
                loadingScreen.classList.remove('hidden');
            });

            document.addEventListener('livewire-upload-finish', () => {
                // hide loader when Livewire finished upload & processing
                setTimeout(() => loadingScreen.classList.add('hidden'), 250);
            });

            document.addEventListener('livewire-upload-error', (e) => {
                // hide loader and show a friendly message
                loadingScreen.classList.add('hidden');
                console.error('Livewire upload error', e);
                try {
                    alert('Image upload failed. Check file size (max 2MB) and try again.');
                } catch (err) {}
            });

            // Also handle generic Livewire ajax error and done states
            document.addEventListener('livewire:error', () => {
                loadingScreen.classList.add('hidden');
            });
            document.addEventListener('livewire:idle', () => {
                loadingScreen.classList.add('hidden');
            });
        });
    </script>
</body>
</html>