<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Adonis Admin</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <!-- compiled assets -->
    <link rel="stylesheet" href="/build/assets/app.css">
    <script type="module" src="/build/assets/app.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4 { font-family: 'Outfit', sans-serif; }
        .scrollbar-thin::-webkit-scrollbar { width: 4px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: #374151; border-radius: 4px; }
        .scrollbar-thin::-webkit-scrollbar-thumb:hover { background: #4b5563; }
    </style>
</head>
<body class="bg-[#0c0f15] text-gray-300 antialiased">

    <!-- Desktop Sidebar - fixed, never scrolls -->
    <aside class="hidden lg:flex flex-col fixed inset-y-0 left-0 w-64 bg-[#111827] text-gray-300 z-30 border-r border-gray-800">
        <div class="h-20 flex items-center px-6 border-b border-gray-800 bg-[#0c1017] shrink-0">
            <a href="{{ url('/admin/dashboard') }}" class="flex items-center space-x-3">
                <div class="w-8 h-8 flex items-center justify-center text-[#C9A84C]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
                <span class="font-black text-lg tracking-wider text-white">ADONIS <span class="text-xs text-[#C9A84C]">ADMIN</span></span>
            </a>
        </div>

        <div class="flex flex-col flex-1 min-h-0">
            <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1.5 scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-transparent">
                @php
                    $currentRoute = request()->path();
                @endphp
                <a href="{{ url('/admin/dashboard') }}" class="flex items-center space-x-3 py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-200 {{ str_contains($currentRoute, 'dashboard') ? 'bg-[#C9A84C] text-black shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ url('/admin/price-list') }}" class="flex items-center space-x-3 py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-200 {{ str_contains($currentRoute, 'price-list') ? 'bg-[#C9A84C] text-black shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span>Services (Catalog)</span>
                </a>

                <a href="{{ url('/admin/appointments') }}" class="flex items-center space-x-3 py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-200 {{ str_contains($currentRoute, 'appointments') ? 'bg-[#C9A84C] text-black shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Appointments</span>
                </a>

                <a href="{{ url('/admin/barbers') }}" class="flex items-center space-x-3 py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-200 {{ str_contains($currentRoute, 'barbers') ? 'bg-[#C9A84C] text-black shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Barbers</span>
                </a>

                <a href="{{ url('/admin/blogs') }}" class="flex items-center space-x-3 py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-200 {{ str_contains($currentRoute, 'blogs') ? 'bg-[#C9A84C] text-black shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <span>Blogs</span>
                </a>

                <a href="{{ url('/admin/faqs') }}" class="flex items-center space-x-3 py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-200 {{ str_contains($currentRoute, 'faqs') ? 'bg-[#C9A84C] text-black shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>FAQs</span>
                </a>

                <a href="{{ url('/admin/carousel') }}" class="flex items-center space-x-3 py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-200 {{ str_contains($currentRoute, 'carousel') ? 'bg-[#C9A84C] text-black shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Carousel</span>
                </a>

                <a href="{{ url('/admin/settings') }}" class="flex items-center space-x-3 py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-200 {{ str_contains($currentRoute, 'settings') ? 'bg-[#C9A84C] text-black shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Settings</span>
                </a>
            </nav>

            <div class="p-4 border-t border-gray-800 bg-[#0c1017] shrink-0">
                <form action="{{ url('/admin/logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-2 py-2.5 px-4 bg-red-900/40 text-red-300 hover:bg-red-900/60 hover:text-white rounded-xl text-sm font-semibold transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Admin Wrapper - full height scrollable -->
    <div class="lg:pl-64 min-h-screen flex flex-col">
        <!-- Top Navigation Bar - sticky -->
        <header class="h-16 sm:h-20 bg-[#111827] border-b border-gray-800 flex items-center justify-between px-4 sm:px-8 z-10 shadow-sm sticky top-0">
            <div class="flex items-center lg:hidden">
                <button id="admin-drawer-open" class="p-2 rounded-md text-gray-500 hover:text-gray-900 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
            
            <div>
                <span class="text-xs uppercase tracking-widest text-gray-500 font-semibold">Adonis Men's Grooming</span>
                <h2 class="text-lg font-bold text-white -mt-0.5">@yield('page_title', 'Admin Panel')</h2>
            </div>

            <div class="flex items-center space-x-4">
                <a href="{{ url('/') }}" target="_blank" class="hidden sm:inline-flex items-center px-4 py-2 border border-gray-700 text-xs font-bold uppercase tracking-wider text-[#C9A84C] hover:bg-gray-800 transition-colors">
                    View Website
                </a>
                <div class="flex items-center space-x-3">
                    @php
                        $adminName = Auth::check() ? Auth::user()->name : 'Admin';
                        $adminInitial = strtoupper(substr($adminName, 0, 1));
                    @endphp
                    <div class="w-9 h-9 flex items-center justify-center font-bold text-sm text-[#C9A84C] border border-[#C9A84C]/30">
                        {{ $adminInitial }}
                    </div>
                    <span class="text-xs font-semibold text-gray-300 hidden md:block">{{ $adminName }}</span>
                </div>
            </div>
        </header>

        <!-- Main Workspace -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-900/30 text-green-400 text-sm border border-green-800/50">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-900/30 text-red-400 text-sm border border-red-800/50">
                    {{ session('error') }}
                </div>
            @endif

            @yield('admin_content')
        </main>
    </div>

    <!-- Mobile Drawer for Admin -->
    <div id="admin-drawer" class="hidden fixed inset-0 z-50 bg-black/40 backdrop-blur-sm">
        <div id="admin-drawer-content" class="fixed inset-y-0 left-0 w-72 max-w-[85vw] bg-[#111827] text-gray-300 flex flex-col transform -translate-x-full transition-transform duration-300 ease-in-out">
            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-800 shrink-0">
                <span class="font-bold text-lg text-white">ADONIS</span>
                <button id="admin-drawer-close" class="p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1.5">
                <a href="{{ url('/admin/dashboard') }}" class="flex items-center space-x-3 py-2.5 px-4 rounded-xl text-sm font-semibold hover:bg-gray-800 hover:text-white">Dashboard</a>
                <a href="{{ url('/admin/price-list') }}" class="flex items-center space-x-3 py-2.5 px-4 rounded-xl text-sm font-semibold hover:bg-gray-800 hover:text-white">Services (Catalog)</a>
                <a href="{{ url('/admin/appointments') }}" class="flex items-center space-x-3 py-2.5 px-4 rounded-xl text-sm font-semibold hover:bg-gray-800 hover:text-white">Appointments</a>
                <a href="{{ url('/admin/barbers') }}" class="flex items-center space-x-3 py-2.5 px-4 rounded-xl text-sm font-semibold hover:bg-gray-800 hover:text-white">Barbers</a>
                <a href="{{ url('/admin/blogs') }}" class="flex items-center space-x-3 py-2.5 px-4 rounded-xl text-sm font-semibold hover:bg-gray-800 hover:text-white">Blogs</a>
                <a href="{{ url('/admin/faqs') }}" class="flex items-center space-x-3 py-2.5 px-4 rounded-xl text-sm font-semibold hover:bg-gray-800 hover:text-white">FAQs</a>
                <a href="{{ url('/admin/carousel') }}" class="flex items-center space-x-3 py-2.5 px-4 rounded-xl text-sm font-semibold hover:bg-gray-800 hover:text-white">Carousel</a>
                <a href="{{ url('/admin/settings') }}" class="flex items-center space-x-3 py-2.5 px-4 rounded-xl text-sm font-semibold hover:bg-gray-800 hover:text-white">Settings</a>
            </nav>

            <div class="shrink-0 border-t border-gray-800 p-4">
                <form action="{{ url('/admin/logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-2 py-2.5 px-4 bg-red-900/40 text-red-300 rounded-xl text-sm font-semibold hover:bg-red-900/60">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Drawer toggle scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const openBtn = document.getElementById('admin-drawer-open');
            const closeBtn = document.getElementById('admin-drawer-close');
            const drawer = document.getElementById('admin-drawer');
            const content = document.getElementById('admin-drawer-content');

            if (openBtn) {
                openBtn.addEventListener('click', () => {
                    drawer.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                    setTimeout(() => {
                        content.classList.remove('-translate-x-full');
                    }, 10);
                });
            }

            const hideDrawer = () => {
                content.classList.add('-translate-x-full');
                document.body.style.overflow = '';
                setTimeout(() => {
                    drawer.classList.add('hidden');
                }, 300);
            };

            if (closeBtn) closeBtn.addEventListener('click', hideDrawer);
            if (drawer) {
                drawer.addEventListener('click', (e) => {
                    if (e.target === drawer) hideDrawer();
                });
            }
        });
    </script>
</body>
</html>
