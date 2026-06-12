@extends('layouts.app')

@section('title', ($service->seo_title ?? $service->title . " | Mayfair Wellness Clinic"))
@section('meta_description', ($service->seo_description ?? $service->short_description))

@section('content')
<!-- Page Hero Banner -->
<section class="relative bg-[#006F5C] text-white py-20 rounded-b-[40px] shadow-md overflow-hidden">
    @if(!empty($service->hero_image))
    <div class="absolute inset-0">
        <img src="{{ asset('uploads/services/' . $service->hero_image) }}" alt="" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-[#005547]/90 to-transparent"></div>
    </div>
    @elseif(!empty($websiteSettings->hero_image))
    <div class="absolute inset-0">
        <img src="{{ asset($websiteSettings->hero_image) }}" alt="" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-[#005547]/90 to-transparent"></div>
    </div>
    @else
    <div class="absolute inset-0 bg-gradient-to-r from-[#005547]/90 to-transparent"></div>
    @endif
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <nav class="flex text-xs uppercase tracking-widest text-white/70 mb-4 font-semibold" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ url('/') }}" class="hover:text-white transition-colors">Home</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <span class="mx-2.5 text-white/40">/</span>
                        <a href="{{ url('/services') }}" class="hover:text-white transition-colors">Services</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <span class="mx-2.5 text-white/40">/</span>
                        <span class="text-white">{{ $service->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>
        
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white mb-2">
            {{ $service->hero_title ?? $service->title }}
        </h1>
        @if($service->hero_subtitle)
            <p class="text-base text-white/80 max-w-xl font-medium">{{ $service->hero_subtitle }}</p>
        @endif
    </div>
</section>

<!-- Main Grid Container -->
<section class="py-16 bg-[#F4FAF8]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
            
            <!-- Left Column: Sidebar (1 Col on Desktop) -->
            <aside class="space-y-8 lg:col-span-1">
                
                <!-- Our Services Card -->
                <div class="bg-[#006F5C] text-white rounded-[24px] p-6 sm:p-8 shadow-md">
                    <h3 class="font-extrabold text-lg tracking-tight mb-6 pb-2 border-b border-white/10 uppercase text-white">
                        Our Services
                    </h3>
                    <ul class="space-y-3.5 text-sm font-semibold">
                        @if(isset($sidebarServices) && count($sidebarServices) > 0)
                            @foreach($sidebarServices as $side)
                                @php
                                    $isCurrent = $service->id === $side->id;
                                @endphp
                                <li>
                                    <a href="{{ url('/services/' . $side->slug) }}" class="flex items-center justify-between group py-2.5 px-4 rounded-xl transition-all duration-200 {{ $isCurrent ? 'bg-[#CC205C] text-white shadow-md' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                                        <span class="truncate pr-2">{{ $side->title }}</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-200 {{ $isCurrent ? 'translate-x-1' : 'group-hover:translate-x-1' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>

                <!-- Have Additional Questions Card -->
                <div class="bg-white border border-[#EEF7F4] rounded-[24px] p-6 sm:p-8 shadow-sm">
                    <div class="w-10 h-10 rounded-2xl bg-[#EEF7F4] text-[#006F5C] flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="font-extrabold text-[#111827] text-lg mb-4">Have Questions?</h3>
                    <p class="text-xs text-[#6B7280] leading-relaxed mb-4">
                        {{ $footerSettings->address ?? 'MCC Building, Level 02, Road 127, Gulshan Avenue, Dhaka 1212.' }}
                    </p>
                    <div class="space-y-3 mb-6 text-xs font-semibold text-[#111827]">
                        <div class="flex items-center">
                            <span class="text-[#006F5C] mr-2">P:</span>
                            <span>{{ $footerSettings->phone ?? '+8801986-660000' }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-[#006F5C] mr-2">E:</span>
                            <span class="truncate">{{ $footerSettings->email ?? 'info@mayfair.com.bd' }}</span>
                        </div>
                    </div>
                    <a href="#booking-form" class="w-full inline-flex items-center justify-center py-3 px-5 text-center text-xs font-bold uppercase tracking-wider rounded-full text-white bg-[#006F5C] hover:bg-[#005547] transition-all duration-200">
                        Book Appointment
                    </a>
                </div>

                <!-- Chat With Our Experts Card -->
                <div class="relative bg-[#111827] text-white rounded-[24px] p-6 sm:p-8 shadow-sm overflow-hidden min-h-[220px] flex flex-col justify-between">
                    <!-- Overlay SVG shape -->
                    <div class="absolute right-0 bottom-0 opacity-10 pointer-events-none">
                        <svg class="w-24 h-24" fill="white" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01s11.953 5.347 11.95 11.996c-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.73-1.455L0 24z"/>
                        </svg>
                    </div>
                    
                    <div>
                        <h3 class="font-extrabold text-lg tracking-tight mb-2">Chat with Experts</h3>
                        <p class="text-xs text-white/75 leading-relaxed">
                            Tap to ask anything about your pain or treatment options.
                        </p>
                    </div>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', ($websiteSettings->whatsapp_number ?? '+8801986660000')) }}" target="_blank" rel="noopener noreferrer" class="w-full mt-6 inline-flex items-center justify-center py-3 px-5 text-center text-xs font-bold uppercase tracking-wider rounded-full text-white bg-[#25D366] hover:bg-[#20ba59] transition-all duration-200">
                        Chat With Us
                    </a>
                </div>
            </aside>

            <!-- Right Column: Main Service Content (3 Cols on Desktop) -->
            <div class="lg:col-span-3 space-y-12">
                
                <!-- Main Service Image Banner -->
                <div class="bg-white border border-[#EEF7F4] rounded-[24px] p-4 shadow-sm relative overflow-hidden">
                    @if($service->main_image)
                        <img src="{{ asset($service->main_image) }}" alt="{{ $service->title }}" class="w-full h-[260px] sm:h-[350px] object-cover rounded-2xl">
                    @else
                        <!-- Elegant CSS medical cross themed placeholder if no image -->
                        <div class="w-full h-[260px] sm:h-[350px] rounded-2xl bg-gradient-to-tr from-[#006F5C] to-[#EEF7F4] flex flex-col items-center justify-center relative p-8 text-center text-[#006F5C]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="font-extrabold text-2xl tracking-tight text-[#111827]">{{ $service->title }}</span>
                            <span class="text-xs uppercase tracking-widest text-[#006F5C] font-semibold mt-1">Confidential Care For Men's Wellness</span>
                        </div>
                    @endif
                </div>

                <!-- Service Title & Description -->
                <div class="bg-white border border-[#EEF7F4] rounded-[24px] p-8 sm:p-10 shadow-sm space-y-6">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-[#111827]">
                        What Is {{ $service->title }}?
                    </h2>
                    <div class="text-sm text-[#6B7280] leading-relaxed space-y-4">
                        <p>{{ $service->main_description }}</p>
                    </div>
                </div>

                <!-- Why This Problem Happens Section (Side by side) -->
                @if($service->cause_title)
                    <div class="bg-white border border-[#EEF7F4] rounded-[24px] p-8 sm:p-10 shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                            <div class="space-y-6">
                                <h3 class="text-xl sm:text-2xl font-extrabold text-[#111827]">{{ $service->cause_title }}</h3>
                                <p class="text-sm text-[#6B7280] leading-relaxed">{{ $service->cause_description }}</p>
                                
                                @if(isset($causeBullets) && count($causeBullets) > 0)
                                    <ul class="space-y-3">
                                        @foreach($causeBullets as $bullet)
                                            <li class="flex items-start">
                                                <div class="flex-shrink-0 w-5 h-5 rounded-full bg-[#CC205C]/10 text-[#CC205C] flex items-center justify-center mt-0.5 mr-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </div>
                                                <span class="text-sm text-[#374151]">{{ $bullet->bullet_text }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>

                            <!-- Side Cause Image -->
                            <div class="relative overflow-hidden rounded-2xl bg-[#EEF7F4]/50 p-4 border border-[#EEF7F4]">
                                @if($service->cause_image)
                                    <img src="{{ asset($service->cause_image) }}" alt="Cause illustration" class="w-full h-[220px] sm:h-[280px] object-cover rounded-xl shadow-sm">
                                @else
                                    <div class="w-full h-[220px] sm:h-[280px] rounded-xl bg-gradient-to-br from-[#EEF7F4] to-[#006F5C]/10 flex flex-col items-center justify-center text-center p-6">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-[#CC205C] mb-3 opacity-65" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <span class="font-bold text-xs uppercase tracking-wider text-[#111827]">Understand Your Symptoms</span>
                                        <span class="text-[10px] text-[#6B7280] mt-1">Diagnosis by certified pelvic therapists</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- How Mayfair Helps Section (Side by side) -->
                @if($service->treatment_title)
                    <div class="bg-white border border-[#EEF7F4] rounded-[24px] p-8 sm:p-10 shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                            <!-- Side Treatment Image -->
                            <div class="relative overflow-hidden rounded-2xl bg-[#EEF7F4]/50 p-4 border border-[#EEF7F4] md:order-first order-last">
                                @if($service->treatment_image)
                                    <img src="{{ asset($service->treatment_image) }}" alt="Treatment illustration" class="w-full h-[220px] sm:h-[280px] object-cover rounded-xl shadow-sm">
                                @else
                                    <div class="w-full h-[220px] sm:h-[280px] rounded-xl bg-gradient-to-br from-[#EEF7F4] to-[#006F5C]/20 flex flex-col items-center justify-center text-center p-6">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-[#006F5C] mb-3 opacity-65" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                        <span class="font-bold text-xs uppercase tracking-wider text-[#111827]">Evidence-Guided Care</span>
                                        <span class="text-[10px] text-[#6B7280] mt-1">Regain bladder control, ease pain, and restore function</span>
                                    </div>
                                @endif
                            </div>

                            <div class="space-y-6">
                                <h3 class="text-xl sm:text-2xl font-extrabold text-[#111827]">{{ $service->treatment_title }}</h3>
                                <p class="text-sm text-[#6B7280] leading-relaxed">{{ $service->treatment_description }}</p>
                                
                                @if(isset($treatmentBullets) && count($treatmentBullets) > 0)
                                    <ul class="space-y-3">
                                        @foreach($treatmentBullets as $bullet)
                                            <li class="flex items-start">
                                                <div class="flex-shrink-0 w-5 h-5 rounded-full bg-[#006F5C]/10 text-[#006F5C] flex items-center justify-center mt-0.5 mr-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                                <span class="text-sm text-[#374151]">{{ $bullet->bullet_text }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Step By Step Treatment Process Section -->
                @if(isset($steps) && count($steps) > 0)
                    <div class="space-y-8">
                        <div class="text-center">
                            <span class="text-[#006F5C] font-bold text-xs uppercase tracking-widest block mb-2">Rehab Cycle</span>
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#111827] tracking-tight">
                                Take the first step toward better men’s health
                            </h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                            @foreach($steps as $step)
                                <div class="bg-white border border-[#EEF7F4] rounded-[24px] p-6 shadow-sm flex flex-col justify-between relative overflow-hidden group hover:shadow-md transition-shadow">
                                    <div>
                                        <!-- Step Badge -->
                                        <div class="inline-flex items-center justify-center px-3 py-1 rounded-full text-[10px] font-black uppercase bg-[#006F5C]/10 text-[#006F5C] mb-4">
                                            Step 0{{ $step->step_number }}
                                        </div>
                                        <h3 class="font-extrabold text-base text-[#111827] mb-2">{{ $step->title }}</h3>
                                        <p class="text-xs text-[#6B7280] leading-relaxed">{{ $step->description }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Appointment Booking Form -->
                <div id="booking-form" class="bg-white border border-[#EEF7F4] rounded-[32px] p-8 sm:p-10 shadow-lg">
                    <div class="mb-8">
                        <h2 class="text-2xl font-extrabold text-[#111827]">Book Your Appointment</h2>
                        <p class="text-xs text-[#6B7280] mt-1">Schedule a private assessment. No referral necessary.</p>
                    </div>

                    <!-- Validation Errors -->
                    <div id="form-errors" class="hidden mb-6 p-4 bg-[#FDF2F5] text-[#CC205C] rounded-2xl text-sm border border-[#CC205C]/20"></div>
                    <!-- Success Message -->
                    <div id="form-success" class="hidden mb-6 p-4 bg-[#EEF7F4] text-[#006F5C] rounded-2xl text-sm border border-[#006F5C]/20"></div>

                    <form action="{{ url('/appointments') }}" method="POST" id="service-booking-form" class="space-y-6">
                        @csrf
                        <input type="hidden" name="service_id" value="{{ $service->id }}">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-[#111827] mb-2">Name *</label>
                                <input type="text" name="name" id="name" required class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-full text-sm focus:outline-none focus:border-[#006F5C] transition-colors" placeholder="e.g. Rahat Ahmed">
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-[#111827] mb-2">Phone Number *</label>
                                <input type="text" name="phone" id="phone" required class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-full text-sm focus:outline-none focus:border-[#006F5C] transition-colors" placeholder="e.g. 01712345678">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="email" class="block text-sm font-semibold text-[#111827] mb-2">Email (Optional)</label>
                                <input type="email" name="email" id="email" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-full text-sm focus:outline-none focus:border-[#006F5C] transition-colors" placeholder="e.g. name@domain.com">
                            </div>
                            <div>
                                <label for="preferred_date" class="block text-sm font-semibold text-[#111827] mb-2">Preferred Date *</label>
                                <input type="date" name="preferred_date" id="preferred_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ date('Y-m-d', strtotime('+1 day')) }}" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-full text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="preferred_time" class="block text-sm font-semibold text-[#111827] mb-2">Preferred Time *</label>
                                <select name="preferred_time" id="preferred_time" required class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-full text-sm focus:outline-none focus:border-[#006F5C] transition-colors appearance-none">
                                    <option value="10:00 AM">Tomorrow, 10:00 AM</option>
                                    <option value="11:00 AM">Tomorrow, 11:00 AM</option>
                                    <option value="12:00 PM">Tomorrow, 12:00 PM</option>
                                    <option value="03:00 PM">Tomorrow, 03:00 PM</option>
                                    <option value="04:00 PM">Tomorrow, 04:00 PM</option>
                                    <option value="05:00 PM">Tomorrow, 05:00 PM</option>
                                    <option value="06:00 PM">Tomorrow, 06:00 PM</option>
                                    <option value="07:00 PM">Tomorrow, 07:00 PM</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="note" class="block text-sm font-semibold text-[#111827] mb-2">Brief Symptoms / Notes (Optional)</label>
                            <textarea name="note" id="note" rows="3" class="w-full px-5 py-4 bg-[#F4FAF8] border border-[#EEF7F4] rounded-3xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors" placeholder="Let us know what you'd like support with..."></textarea>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 border border-transparent text-sm font-bold rounded-full text-white bg-[#006F5C] hover:bg-[#005547] shadow-sm hover:shadow-md transition-all duration-200">
                                Confirm Appointment
                            </button>
                        </div>
                    </form>
                </div>

                <!-- FAQ Section (Accordion) -->
                @if(isset($faqs) && count($faqs) > 0)
                    <div class="bg-white border border-[#EEF7F4] rounded-[24px] p-8 sm:p-10 shadow-sm space-y-6">
                        <h2 class="text-2xl font-extrabold text-[#111827] mb-6">Frequently Asked Questions</h2>
                        <div class="space-y-4" id="faq-accordion">
                            @foreach($faqs as $index => $faq)
                                <div class="border-b border-[#EEF7F4] pb-4">
                                    <button class="w-full flex justify-between items-center text-left py-3 focus:outline-none group" onclick="toggleFaq({{ $index }})">
                                        <span class="font-bold text-sm text-[#111827] group-hover:text-[#006F5C] transition-colors leading-relaxed">{{ $faq->question }}</span>
                                        <svg id="faq-icon-{{ $index }}" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#6B7280] group-hover:text-[#006F5C] transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div id="faq-answer-{{ $index }}" class="hidden mt-2 text-xs text-[#6B7280] leading-relaxed transition-all duration-300">
                                        {{ $faq->answer }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</section>

<!-- Accordion logic & Form submission scripts -->
<script>
    function toggleFaq(index) {
        const answer = document.getElementById(`faq-answer-${index}`);
        const icon = document.getElementById(`faq-icon-${index}`);
        if (answer.classList.contains('hidden')) {
            answer.classList.remove('hidden');
            icon.classList.add('rotate-180');
        } else {
            answer.classList.add('hidden');
            icon.classList.remove('rotate-180');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('service-booking-form');
        const errorsDiv = document.getElementById('form-errors');
        const successDiv = document.getElementById('form-success');

        if (form) {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                errorsDiv.classList.add('hidden');
                successDiv.classList.add('hidden');

                const formData = new FormData(form);
                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const data = await response.json();
                    if (response.ok) {
                        successDiv.textContent = data.message || 'Appointment requested successfully!';
                        successDiv.classList.remove('hidden');
                        form.reset();
                    } else {
                        let errMsg = data.message || 'Something went wrong.';
                        if (data.errors) {
                            const firstErrKey = Object.keys(data.errors)[0];
                            errMsg = data.errors[firstErrKey][0];
                        }
                        errorsDiv.textContent = errMsg;
                        errorsDiv.classList.remove('hidden');
                    }
                } catch (err) {
                    errorsDiv.textContent = 'Server connection error. Please try again.';
                    errorsDiv.classList.remove('hidden');
                }
            });
        }
    });
</script>
@endsection
