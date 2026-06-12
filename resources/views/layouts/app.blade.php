<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', ($websiteSettings->site_name ?? 'Mayfair Wellness Clinic') . " - Men's Health")</title>
    <meta name="description" content="@yield('meta_description', 'Confidential men\'s health physiotherapy and urological condition support at Mayfair Wellness Clinic, Gulshan, Dhaka.')">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Compiled Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="bg-[#F4FAF8] text-[#374151] min-h-screen flex flex-col antialiased">

    <!-- Header Navigation -->
    <header class="bg-white border-b border-[#EEF7F4] sticky top-0 z-50 shadow-sm transition-all duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center">
                        @if(!empty($websiteSettings->logo))
                        <img src="{{ asset($websiteSettings->logo) }}" alt="{{ $websiteSettings->site_name ?? 'Mayfair' }}" class="h-10 sm:h-12 w-auto object-contain">
                        @else
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-[#006F5C] flex items-center justify-center text-white shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div>
                                <span class="font-bold text-xl tracking-tight text-[#111827] block">MAYFAIR</span>
                                <span class="text-xs uppercase tracking-widest text-[#006F5C] font-semibold block -mt-1">Wellness Clinic</span>
                            </div>
                        </div>
                        @endif
                    </a>
                </div>

                <!-- Desktop Navigation Menu -->
                <nav class="hidden md:flex items-center space-x-8">
                    @if(isset($headerMenus) && count($headerMenus) > 0)
                        @foreach($headerMenus as $menu)
                            @php
                                $isExternal = str_starts_with($menu->url, 'http');
                                $isActive = !$isExternal && (request()->is(ltrim($menu->url, '/')) || (request()->is('/') && $menu->url === '/'));
                            @endphp
                            <a href="{{ $menu->url }}"{{ $isExternal ? ' target=_blank rel=noopener' : '' }} class="text-sm font-semibold tracking-wide transition-colors duration-200 {{ $isActive ? 'text-[#006F5C] border-b-2 border-[#006F5C] pb-1' : 'text-[#6B7280] hover:text-[#006F5C]' }}">
                                {{ $menu->title }}
                            </a>
                        @endforeach
                    @else
                        <a href="{{ url('/') }}" class="text-sm font-semibold tracking-wide text-[#006F5C]">Home</a>
                        <a href="https://mayfair.com.bd/services/" target="_blank" rel="noopener" class="text-sm font-semibold tracking-wide text-[#6B7280] hover:text-[#006F5C]">Services</a>
                        <a href="https://mayfair.com.bd/our-physiotherapy-specialists/" target="_blank" rel="noopener" class="text-sm font-semibold tracking-wide text-[#6B7280] hover:text-[#006F5C]">Our Specialists</a>
                        <a href="https://mayfair.com.bd/about-us/" target="_blank" rel="noopener" class="text-sm font-semibold tracking-wide text-[#6B7280] hover:text-[#006F5C]">About Us</a>
                        <a href="https://mayfair.com.bd/contact/" target="_blank" rel="noopener" class="text-sm font-semibold tracking-wide text-[#6B7280] hover:text-[#006F5C]">Contact</a>
                    @endif
                </nav>

                <!-- Header Actions -->
                <div class="hidden md:flex items-center space-x-4">
                    <!-- Wishlist or Cart if needed -->
                    <a href="#" class="text-[#6B7280] hover:text-[#006F5C] p-2 rounded-full hover:bg-[#EEF7F4] transition-colors relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </a>
                    
                    <a href="{{ $websiteSettings->appointment_button_url ?? '#booking-form' }}" class="inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-semibold rounded-full text-white bg-[#006F5C] hover:bg-[#005547] shadow-sm hover:shadow-md transition-all duration-200">
                        <span>{{ $websiteSettings->appointment_button_text ?? 'Appointment Now' }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center md:hidden">
                    <button id="mobile-menu-btn" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-[#6B7280] hover:text-[#006F5C] hover:bg-[#EEF7F4] focus:outline-none transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Drawer (outside header to avoid sticky/fixed conflict) -->
    <div id="mobile-drawer" class="hidden md:hidden">
        <!-- Backdrop z-40 -->
        <div id="mobile-drawer-backdrop" class="fixed inset-0 z-40 bg-[#111827]/40 backdrop-blur-sm"></div>
        <!-- Panel z-50 (above backdrop) -->
        <div class="fixed inset-y-0 right-0 z-50 w-[280px] max-w-[80vw] bg-white shadow-2xl flex flex-col py-6 px-5 overflow-y-auto transform translate-x-full transition-transform duration-300 ease-in-out" id="mobile-drawer-content">
            <div class="flex items-center justify-between pb-4 border-b border-[#EEF7F4] shrink-0">
                <span class="font-bold text-lg text-[#111827]">Menu</span>
                <button id="mobile-drawer-close" type="button" class="-mr-1 p-2 rounded-md text-[#6B7280] hover:text-[#006F5C] hover:bg-[#EEF7F4] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <nav class="flex flex-col mt-4 space-y-0.5">
                @if(isset($headerMenus) && count($headerMenus) > 0)
                    @foreach($headerMenus as $menu)
                        @php $isExternal = str_starts_with($menu->url, 'http'); @endphp
                        <a href="{{ $menu->url }}"{{ $isExternal ? ' target=_blank rel=noopener' : '' }} class="mobile-nav-link block w-full text-base font-semibold text-[#374151] hover:text-[#006F5C] hover:bg-[#F4FAF8] transition-colors py-3 px-3 rounded-xl text-left">
                            {{ $menu->title }}
                        </a>
                    @endforeach
                @else
                    <a href="{{ url('/') }}" class="mobile-nav-link block w-full text-base font-semibold text-[#374151] hover:text-[#006F5C] hover:bg-[#F4FAF8] transition-colors py-3 px-3 rounded-xl text-left">Home</a>
                    <a href="https://mayfair.com.bd/services/" target="_blank" rel="noopener" class="mobile-nav-link block w-full text-base font-semibold text-[#374151] hover:text-[#006F5C] hover:bg-[#F4FAF8] transition-colors py-3 px-3 rounded-xl text-left">Services</a>
                    <a href="https://mayfair.com.bd/our-physiotherapy-specialists/" target="_blank" rel="noopener" class="mobile-nav-link block w-full text-base font-semibold text-[#374151] hover:text-[#006F5C] hover:bg-[#F4FAF8] transition-colors py-3 px-3 rounded-xl text-left">Our Specialists</a>
                    <a href="https://mayfair.com.bd/about-us/" target="_blank" rel="noopener" class="mobile-nav-link block w-full text-base font-semibold text-[#374151] hover:text-[#006F5C] hover:bg-[#F4FAF8] transition-colors py-3 px-3 rounded-xl text-left">About Us</a>
                    <a href="https://mayfair.com.bd/contact/" target="_blank" rel="noopener" class="mobile-nav-link block w-full text-base font-semibold text-[#374151] hover:text-[#006F5C] hover:bg-[#F4FAF8] transition-colors py-3 px-3 rounded-xl text-left">Contact</a>
                @endif
            </nav>
            <div class="mt-auto pt-5 border-t border-[#EEF7F4]">
                <a href="{{ $websiteSettings->appointment_button_url ?? '#booking-form' }}" class="mobile-nav-link w-full inline-flex items-center justify-center px-5 py-3 text-sm font-bold rounded-full text-white bg-[#006F5C] hover:bg-[#005547] text-center shadow-md transition-all duration-200">
                    {{ $websiteSettings->appointment_button_text ?? 'Appointment Now' }}
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer Appointment CTA Section -->
    <section class="bg-[#006F5C] relative overflow-hidden py-16">
        <!-- Background accents -->
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <circle cx="10" cy="20" r="30" fill="white"/>
                <circle cx="90" cy="80" r="40" fill="white"/>
            </svg>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <span class="text-white/80 font-bold uppercase tracking-wider text-sm">Appointment</span>
            <h2 class="text-white text-3xl sm:text-4xl font-extrabold mt-2 mb-6">Book Your Appointment Now</h2>
            <a href="{{ $websiteSettings->appointment_button_url ?? '#booking-form' }}" class="inline-flex items-center justify-center px-8 py-3.5 border-2 border-white text-base font-bold rounded-full text-white hover:bg-white hover:text-[#006F5C] shadow-lg transition-all duration-300">
                <span>Appointment Now</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>
    </section>

    <!-- Footer Area -->
    <footer class="bg-[#111827] text-[#9CA3AF] pt-16 pb-8 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Full-width Logo -->
            <div class="mb-12">
                <a href="{{ url('/') }}" class="inline-block">
                    @if(!empty($websiteSettings->logo))
                    <img src="{{ asset($websiteSettings->logo) }}" alt="{{ $websiteSettings->site_name ?? 'Mayfair' }}" class="h-16 sm:h-20 md:h-24 w-auto object-contain brightness-0 invert">
                    @else
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-[#006F5C] flex items-center justify-center text-white shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <span class="font-bold text-xl tracking-tight text-white">MAYFAIR</span>
                    </div>
                    @endif
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 mb-12">
                <!-- Col 1+2: Links in 2-column layout -->
                <div class="lg:col-span-8">
                    <div class="grid grid-cols-2 gap-8">
                        <!-- Discover Us -->
                        <div>
                            <h3 class="text-white font-bold text-sm tracking-wider uppercase mb-6">Discover Us</h3>
                            <ul class="space-y-3 text-sm">
                                @foreach($discoverMenus ?? [] as $menu)
                                    <li><a href="{{ url($menu->url) }}" class="hover:text-white transition-colors">{{ $menu->title }}</a></li>
                                @endforeach
                                @if(!isset($discoverMenus) || count($discoverMenus) == 0)
                                    <li><a href="#" class="hover:text-white transition-colors">Home</a></li>
                                    <li><a href="#" class="hover:text-white transition-colors">About Us</a></li>
                                    <li><a href="#" class="hover:text-white transition-colors">Blogs</a></li>
                                @endif
                            </ul>
                        </div>

                        <!-- Useful Links -->
                        <div>
                            <h3 class="text-white font-bold text-sm tracking-wider uppercase mb-6">Useful Links</h3>
                            <ul class="space-y-3 text-sm">
                                @foreach($usefulMenus ?? [] as $menu)
                                    <li><a href="{{ url($menu->url) }}" class="hover:text-white transition-colors">{{ $menu->title }}</a></li>
                                @endforeach
                                @if(!isset($usefulMenus) || count($usefulMenus) == 0)
                                    <li><a href="#" class="hover:text-white transition-colors">What we treat</a></li>
                                    <li><a href="#" class="hover:text-white transition-colors">Our Doctors</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Col 3: Address + Social + Contact -->
                <div class="lg:col-span-4">
                    <h3 class="text-white font-bold text-sm tracking-wider uppercase mb-6">Contact Us</h3>
                    <p class="text-sm leading-relaxed mb-4">
                        {{ $footerSettings->address ?? 'MCC Building, Level 02, Road 127, Gulshan Avenue, Dhaka 1212.' }}
                    </p>
                    <div class="space-y-2 text-sm">
                        <a href="tel:{{ $footerSettings->phone ?? '+8801986660000' }}" class="flex items-center hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-[#006F5C] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>{{ $footerSettings->phone ?? '+8801986-660000' }}</span>
                        </a>
                        <a href="mailto:{{ $footerSettings->email ?? 'info@mayfair.com.bd' }}" class="flex items-center hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-[#006F5C] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="truncate">{{ $footerSettings->email ?? 'info@mayfair.com.bd' }}</span>
                        </a>
                    </div>
                    <a href="{{ url('/contact') }}" class="inline-flex items-center justify-center px-5 py-2.5 mt-6 text-xs font-bold uppercase tracking-wider rounded-full text-white bg-[#006F5C] hover:bg-[#005547] transition-all duration-200">
                        Contact Now
                    </a>
                    <!-- Social icons -->
                    <div class="flex space-x-4 mt-6">
                        <a href="{{ $footerSettings->facebook_url ?? '#' }}" class="hover:text-white text-gray-500 transition-colors">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"/></svg>
                        </a>
                        <a href="{{ $footerSettings->instagram_url ?? '#' }}" class="hover:text-white text-gray-500 transition-colors">
                            <span class="sr-only">Instagram</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" clip-rule="evenodd"/></svg>
                        </a>
                        <a href="{{ $footerSettings->linkedin_url ?? '#' }}" class="hover:text-white text-gray-500 transition-colors">
                            <span class="sr-only">LinkedIn</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bottom Line / Copyright -->
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center text-xs">
                <p class="mb-4 md:mb-0">
                    {{ $footerSettings->copyright_text ?? 'Copyright © 2026 Mayfair. All rights reserved. | Crafted with care by Sinodtech Ltd.' }}
                </p>
                <div class="flex space-x-6">
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/8801986660000?text=Hi%20Mayfair%20Wellness%20Clinic%2C%20I%20would%20like%20to%20book%20a%20consultation." target="_blank" rel="noopener noreferrer" style="position:fixed;bottom:24px;right:24px;z-index:99999;width:56px;height:56px;display:flex;align-items:center;justify-content:center;background:#25D366;border-radius:50%;box-shadow:0 4px 15px rgba(0,0,0,0.3);text-decoration:none;" onmouseover="this.style.transform='scale(1.12)'" onmouseout="this.style.transform='scale(1)'" aria-label="Chat on WhatsApp">
        <svg style="width:28px;height:28px;fill:white;display:block" viewBox="0 0 24 24">
            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.73-1.455L0 24zm6.59-4.846c1.6.95 3.397 1.453 5.409 1.454 5.928 0 10.751-4.82 10.754-10.748.002-2.873-1.102-5.574-3.112-7.588-2.01-2.013-4.685-3.123-7.582-3.123-5.938 0-10.761 4.822-10.765 10.75-.001 2.03.526 4.017 1.524 5.786l-1.001 3.654 3.777-.985zM17.78 14.86c-.33-.165-1.954-.964-2.253-1.074-.3-.109-.518-.165-.735.165-.218.33-.842 1.074-1.03 1.293-.19.219-.379.246-.71.081-1.213-.607-2.038-1.09-2.85-2.483-.217-.373.217-.346.621-1.154.068-.137.034-.257-.017-.366-.051-.11-.442-1.066-.606-1.46-.16-.383-.336-.331-.46-.338-.12-.006-.257-.007-.395-.007-.137 0-.361.052-.55.257-.189.206-.723.707-.723 1.724 0 1.018.74 2.002.843 2.141.103.137 1.457 2.224 3.53 3.12.493.213.878.34 1.179.436.496.157.947.135 1.303.082.397-.059 1.954-.799 2.228-1.57.275-.771.275-1.432.193-1.57-.083-.138-.302-.22-.632-.385z"/>
        </svg>
    </a>

    <!-- Mobile Drawer Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('mobile-menu-btn');
            const wrapper = document.getElementById('mobile-drawer');
            const backdrop = document.getElementById('mobile-drawer-backdrop');
            const panel = document.getElementById('mobile-drawer-content');
            const closeBtn = document.getElementById('mobile-drawer-close');
            if (!btn || !wrapper || !backdrop || !panel || !closeBtn) return;

            let isOpen = false;

            function open() {
                if (isOpen) return;
                isOpen = true;
                wrapper.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                setTimeout(() => panel.classList.remove('translate-x-full'), 20);
            }

            function close() {
                if (!isOpen) return;
                isOpen = false;
                panel.classList.add('translate-x-full');
                document.body.style.overflow = '';
                setTimeout(() => wrapper.classList.add('hidden'), 300);
            }

            btn.addEventListener('click', open);
            closeBtn.addEventListener('click', close);

            // Close on backdrop click only (not on panel)
            backdrop.addEventListener('click', close);

            // Prevent backdrop click when clicking inside the panel
            panel.addEventListener('click', (e) => e.stopPropagation());

            // Close on any nav link click
            document.querySelectorAll('.mobile-nav-link').forEach(el => {
                el.addEventListener('click', close);
            });
        });
    </script>
</body>
</html>
