<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Mayfair Clinic</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <!-- compiled assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4 { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-[#F4FAF8] text-[#374151] min-h-screen flex antialiased">

    <!-- Desktop Sidebar -->
    <aside class="hidden lg:flex flex-col w-64 bg-[#111827] text-gray-300 min-h-screen flex-shrink-0 border-r border-gray-800">
        <div class="h-20 flex items-center px-6 border-b border-gray-800 bg-[#0c1017]">
            <a href="{{ url('/admin/dashboard') }}" class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-full bg-[#006F5C] flex items-center justify-center text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <span class="font-black text-lg tracking-wider text-white">MAYFAIR <span class="text-xs text-[#006F5C]">ADMIN</span></span>
            </a>
        </div>
        
        <nav class="flex-grow py-6 px-4 space-y-1.5 overflow-y-auto">
            @php
                $currentRoute = request()->path();
            @endphp
            <a href="{{ url('/admin/dashboard') }}" class="flex items-center space-x-3 py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-200 {{ str_contains($currentRoute, 'dashboard') ? 'bg-[#006F5C] text-white shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ url('/admin/services') }}" class="flex items-center space-x-3 py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-200 {{ str_contains($currentRoute, 'services') ? 'bg-[#006F5C] text-white shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
                <span>Services CRUD</span>
            </a>

            <a href="{{ url('/admin/appointments') }}" class="flex items-center space-x-3 py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-200 {{ str_contains($currentRoute, 'appointments') ? 'bg-[#006F5C] text-white shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Appointments</span>
            </a>

            <a href="{{ url('/admin/faqs') }}" class="flex items-center space-x-3 py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-200 {{ str_contains($currentRoute, 'faqs') ? 'bg-[#006F5C] text-white shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>FAQs</span>
            </a>

            <a href="{{ url('/admin/carousel') }}" class="flex items-center space-x-3 py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-200 {{ str_contains($currentRoute, 'carousel') ? 'bg-[#006F5C] text-white shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Carousel</span>
            </a>

            <a href="{{ url('/admin/settings') }}" class="flex items-center space-x-3 py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-200 {{ str_contains($currentRoute, 'settings') ? 'bg-[#006F5C] text-white shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Settings</span>
            </a>
        </nav>

        <div class="p-4 border-t border-gray-800 bg-[#0c1017]">
            <form action="{{ url('/admin/logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center space-x-2 py-2.5 px-4 bg-red-900/40 text-red-300 hover:bg-red-900/60 hover:text-white rounded-xl text-sm font-semibold transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Sign Out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Admin Wrapper -->
    <div class="flex-grow flex flex-col min-w-0">
        <!-- Top Navigation Bar -->
        <header class="h-20 bg-white border-b border-[#EEF7F4] flex items-center justify-between px-6 sm:px-8 z-10 shadow-sm">
            <div class="flex items-center lg:hidden">
                <button id="admin-drawer-open" class="p-2 rounded-md text-gray-500 hover:text-gray-900 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
            
            <div>
                <span class="text-xs uppercase tracking-widest text-[#6B7280] font-semibold">Mayfair Wellness Clinic</span>
                <h2 class="text-lg font-bold text-[#111827] -mt-0.5">@yield('page_title', 'Admin Panel')</h2>
            </div>

            <div class="flex items-center space-x-4">
                <a href="{{ url('/') }}" target="_blank" class="hidden sm:inline-flex items-center px-4 py-2 border border-[#EEF7F4] text-xs font-bold uppercase tracking-wider rounded-full text-[#006F5C] hover:bg-[#EEF7F4] transition-colors">
                    View Website
                </a>
                <div class="flex items-center space-x-2">
                    <div class="w-9 h-9 rounded-full bg-[#006F5C] text-white flex items-center justify-center font-bold text-sm">
                        A
                    </div>
                    <span class="text-xs font-semibold text-[#111827] hidden md:block">Administrator</span>
                </div>
            </div>
        </header>

        <!-- Main Workspace -->
        <main class="flex-grow p-6 sm:p-8 overflow-y-auto">
            @if(session('success'))
                <div class="mb-6 p-4 bg-[#EEF7F4] text-[#006F5C] rounded-2xl text-sm border border-[#006F5C]/20 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-[#FDF2F5] text-[#CC205C] rounded-2xl text-sm border border-[#CC205C]/20 shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            @yield('admin_content')
        </main>
    </div>

    <!-- Mobile Drawer for Admin (for small screens) -->
    <div id="admin-drawer" class="hidden fixed inset-0 z-50 bg-black/40 backdrop-blur-sm">
        <div id="admin-drawer-content" class="fixed inset-y-0 left-0 w-64 bg-[#111827] text-gray-300 p-6 flex flex-col py-6 overflow-y-auto transform -translate-x-full transition-transform duration-300 ease-in-out">
            <div class="flex items-center justify-between pb-6 border-b border-gray-800">
                <span class="font-bold text-lg text-white">MAYFAIR ADMIN</span>
                <button id="admin-drawer-close" class="p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <nav class="flex-grow py-6 space-y-2">
                <a href="{{ url('/admin/dashboard') }}" class="block py-2.5 px-4 rounded-xl text-sm font-semibold hover:bg-gray-800 hover:text-white">Dashboard</a>
                <a href="{{ url('/admin/services') }}" class="block py-2.5 px-4 rounded-xl text-sm font-semibold hover:bg-gray-800 hover:text-white">Services CRUD</a>
                <a href="{{ url('/admin/appointments') }}" class="block py-2.5 px-4 rounded-xl text-sm font-semibold hover:bg-gray-800 hover:text-white">Appointments</a>
                <a href="{{ url('/admin/faqs') }}" class="block py-2.5 px-4 rounded-xl text-sm font-semibold hover:bg-gray-800 hover:text-white">FAQs</a>
                <a href="{{ url('/admin/carousel') }}" class="block py-2.5 px-4 rounded-xl text-sm font-semibold hover:bg-gray-800 hover:text-white">Carousel</a>
                <a href="{{ url('/admin/settings') }}" class="block py-2.5 px-4 rounded-xl text-sm font-semibold hover:bg-gray-800 hover:text-white">Settings</a>
            </nav>

            <div class="mt-auto">
                <form action="{{ url('/admin/logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-center py-2.5 px-4 bg-red-900/40 text-red-300 rounded-xl text-sm font-semibold hover:bg-red-900/60">
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Toggle scripts for Admin drawer -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const openBtn = document.getElementById('admin-drawer-open');
            const closeBtn = document.getElementById('admin-drawer-close');
            const drawer = document.getElementById('admin-drawer');
            const content = document.getElementById('admin-drawer-content');

            if (openBtn) {
                openBtn.addEventListener('click', () => {
                    drawer.classList.remove('hidden');
                    setTimeout(() => {
                        content.classList.remove('-translate-x-full');
                    }, 10);
                });
            }

            const hideDrawer = () => {
                content.classList.add('-translate-x-full');
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
