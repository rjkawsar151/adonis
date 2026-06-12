@extends('layouts.app')

@section('title', "Mayfair Wellness Clinic - Home")

@section('content')
<!-- Hero Section -->
<section class="relative bg-[#006F5C] text-white py-24 sm:py-32 overflow-hidden rounded-b-[40px] shadow-lg">
    @if(!empty($websiteSettings->hero_image))
    <div class="absolute inset-0">
        <img src="{{ asset($websiteSettings->hero_image) }}" alt="" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-[#005547] to-transparent opacity-85"></div>
    </div>
    @else
    <!-- Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-r from-[#005547] to-transparent opacity-85"></div>
    
    <!-- Hero Image Placeholder (Confidential Consultation SVG Accent) -->
    <div class="absolute right-0 bottom-0 top-0 w-full lg:w-1/2 opacity-20 lg:opacity-30 pointer-events-none">
        <svg class="w-full h-full object-cover" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="grid-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#CC205C" />
                    <stop offset="100%" stop-color="#006F5C" />
                </linearGradient>
            </defs>
            <rect width="100" height="100" fill="url(#grid-grad)" opacity="0.1"/>
            <circle cx="50" cy="50" r="30" stroke="white" stroke-width="0.5" stroke-dasharray="2 2"/>
            <path d="M20 50 C40 20, 60 80, 80 50" stroke="white" stroke-width="1"/>
        </svg>
    </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-2xl lg:max-w-xl">
            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-white/10 text-white border border-white/20 mb-6 uppercase tracking-wider">
                Men's Health Services
            </span>
            <h1 id="hero-title" class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white mb-6 leading-tight min-h-[135px] sm:min-h-[180px] lg:min-h-[225px]">
                <span id="hero-title-text"></span><span id="hero-cursor" class="animate-pulse">|</span>
            </h1>
            <p class="text-lg text-white/80 mb-8 leading-relaxed">
                Mayfair Wellness Clinic provides specialized, respectful, and evidence-based physiotherapy for pelvic floor dysfunction, chronic pain, and post-surgery recovery.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ url('/services/mens-health-physiotherapy') }}" class="inline-flex items-center justify-center px-6 py-3.5 border border-transparent text-base font-bold rounded-full text-white bg-[#CC205C] hover:bg-[#A61A4B] shadow-md hover:shadow-lg transition-all duration-200">
                    Discover Our Services
                </a>
                <a href="#booking-form" class="inline-flex items-center justify-center px-6 py-3.5 border-2 border-white text-base font-bold rounded-full text-white hover:bg-white hover:text-[#006F5C] transition-all duration-200">
                    Book Appointment
                </a>
            </div>
        </div>
    </div>
</section>

@if(isset($carouselImages) && count($carouselImages) > 0)
<!-- Image Carousel -->
<section class="py-8 sm:py-12 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div id="carousel-container" class="relative overflow-hidden rounded-[16px] sm:rounded-[24px] shadow-sm select-none">
            <div id="carousel-track" class="flex transition-transform duration-500 ease-in-out will-change-transform">
                @foreach($carouselImages as $img)
                <div class="min-w-full">
                    @if($img->link_url)<a href="{{ $img->link_url }}" target="_blank" rel="noopener">@endif
                    <img src="{{ asset($img->image_path) }}" alt="{{ $img->alt_text ?? 'Carousel' }}" class="w-full h-[200px] sm:h-[300px] md:h-[400px] lg:h-[500px] object-cover" draggable="false">
                    @if($img->link_url)</a>@endif
                </div>
                @endforeach
            </div>

            <!-- Arrows (hidden on mobile) -->
            <button id="carousel-prev" class="hidden sm:flex absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/30 hover:bg-black/50 text-white items-center justify-center transition-all z-10 backdrop-blur-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button id="carousel-next" class="hidden sm:flex absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/30 hover:bg-black/50 text-white items-center justify-center transition-all z-10 backdrop-blur-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </button>

            <!-- Dots -->
            <div class="absolute bottom-3 sm:bottom-4 left-1/2 -translate-x-1/2 flex space-x-2 z-10">
                @foreach($carouselImages as $i => $img)
                <button type="button" class="carousel-dot w-2 h-2 sm:w-2.5 sm:h-2.5 rounded-full bg-white/50 hover:bg-white/80 transition-all {{ $i === 0 ? 'bg-white sm:w-6' : '' }}" data-index="{{ $i }}" aria-label="Slide {{ $i + 1 }}"></button>
                @endforeach
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const track = document.getElementById('carousel-track');
    const container = document.getElementById('carousel-container');
    if (!track || !container) return;
    const slides = track.children;
    const totalSlides = slides.length;
    if (totalSlides < 2) return;
    let current = 0;
    let autoInterval;

    const dots = document.querySelectorAll('.carousel-dot');
    const prevBtn = document.getElementById('carousel-prev');
    const nextBtn = document.getElementById('carousel-next');

    function goTo(index) {
        if (index >= totalSlides) index = 0;
        if (index < 0) index = totalSlides - 1;
        current = index;
        track.style.transform = 'translateX(-' + (current * 100) + '%)';
        dots.forEach((d, i) => {
            d.classList.toggle('bg-white', i === current);
            d.classList.toggle('sm:w-6', i === current);
            d.classList.toggle('bg-white/50', i !== current);
            d.classList.toggle('sm:w-2.5', i !== current);
        });
    }

    function startAuto() {
        stopAuto();
        autoInterval = setInterval(() => goTo(current + 1), 2500);
    }
    function stopAuto() { clearInterval(autoInterval); }

    dots.forEach(dot => dot.addEventListener('click', () => { goTo(parseInt(dot.dataset.index)); startAuto(); }));
    if (prevBtn) prevBtn.addEventListener('click', () => { goTo(current - 1); startAuto(); });
    if (nextBtn) nextBtn.addEventListener('click', () => { goTo(current + 1); startAuto(); });

    // Pause on hover
    container.addEventListener('mouseenter', stopAuto);
    container.addEventListener('mouseleave', startAuto);

    // Touch swipe
    let startX = 0, endX = 0;
    container.addEventListener('touchstart', (e) => { startX = e.changedTouches[0].screenX; stopAuto(); }, { passive: true });
    container.addEventListener('touchend', (e) => {
        endX = e.changedTouches[0].screenX;
        const diff = startX - endX;
        if (Math.abs(diff) > 50) {
            goTo(diff > 0 ? current + 1 : current - 1);
        }
        startAuto();
    }, { passive: true });

    startAuto();
});
</script>
@endif

<!-- Confidentiality Intro -->
<section class="py-16 bg-[#F4FAF8]">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 text-center">
        <h2 class="text-3xl font-extrabold text-[#111827] tracking-tight sm:text-4xl">
            Private, Respectful, & Comprehensive
        </h2>
        <p class="mt-4 text-lg text-[#6B7280] max-w-3xl mx-auto leading-relaxed">
            We understand that many men delay seeking help for pelvic, urinary, or sexual health problems due to embarrassment or discomfort. Our clinic provides a secure environment with medical specialists who deliver confidential assessment and personalized physiotherapy support to restore your comfort and confidence.
        </p>
    </div>
</section>

<!-- Services Grid -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-[#111827] tracking-tight">
                Featured Medical Treatments
            </h2>
            <p class="mt-4 text-base text-[#6B7280]">
                Select a service to view specialized recovery plans, step guides, and FAQ resources.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @if(isset($featuredServices) && count($featuredServices) > 0)
                @foreach($featuredServices as $service)
                    <div class="bg-white border border-[#EEF7F4] rounded-[24px] overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col hover:-translate-y-1">
                        <div class="p-8 flex-grow">
                            <!-- Service Header Badge/Icon -->
                            <div class="w-12 h-12 rounded-2xl bg-[#EEF7F4] text-[#006F5C] flex items-center justify-center mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-[#111827] mb-3">{{ $service->title }}</h3>
                            <p class="text-sm text-[#6B7280] leading-relaxed line-clamp-3">
                                {{ $service->short_description }}
                            </p>
                        </div>
                        <div class="px-8 pb-8 pt-0">
                            <a href="{{ url('/services/' . $service->slug) }}" class="inline-flex items-center text-sm font-bold text-[#006F5C] hover:text-[#CC205C] transition-colors duration-200">
                                <span>Learn More</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Fallback card -->
                <div class="bg-white border border-[#EEF7F4] rounded-[24px] overflow-hidden shadow-sm p-8 text-center col-span-3">
                    <p class="text-[#6B7280]">No services found in database. Please run seeders.</p>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-20 bg-[#EEF7F4] rounded-[40px] mx-4 sm:mx-8 my-8 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-[#006F5C] font-bold text-xs uppercase tracking-widest block mb-2">Our Advantage</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#111827] mb-6">Why Patients Trust Mayfair Clinic</h2>
                <p class="text-base text-[#6B7280] leading-relaxed mb-8">
                    We employ experienced male physiotherapists who specialize in pelvic floor health. Our treatments are supported by medical evidence and conducted in completely private, single-patient clinical suites.
                </p>
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-[#006F5C] text-white flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-[#111827]">Strict Confidentiality</h4>
                            <p class="text-xs text-[#6B7280] mt-1">Discreet records, private suites, and personal consultation protocols.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-[#006F5C] text-white flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-[#111827]">Qualified Male Clinicians</h4>
                            <p class="text-xs text-[#6B7280] mt-1">Physiotherapy treatments conducted by male specialists experienced in pelvic therapy.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-[#006F5C] text-white flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-[#111827]">Modern Medical Tech</h4>
                            <p class="text-xs text-[#6B7280] mt-1">Advanced pelvic floor assessment tools and customized rehabilitation protocols.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Graphics Card -->
            <div class="relative bg-white border border-[#EEF7F4] rounded-[32px] p-8 shadow-md">
                <h3 class="text-lg font-bold text-[#111827] mb-6">Confidential Care Cycle</h3>
                <div class="space-y-6">
                    <div class="flex items-center space-x-4 border-b border-[#EEF7F4] pb-4">
                        <span class="text-xl font-black text-[#CC205C]">01</span>
                        <div>
                            <h4 class="font-bold text-sm text-[#111827]">Private Online Booking</h4>
                            <p class="text-xs text-[#6B7280]">Book your slot securely using our Bangladesh phone-verified scheduling form.</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 border-b border-[#EEF7F4] pb-4">
                        <span class="text-xl font-black text-[#006F5C]">02</span>
                        <div>
                            <h4 class="font-bold text-sm text-[#111827]">Initial Diagnostic Assessment</h4>
                            <p class="text-xs text-[#6B7280]">A thorough medical evaluation in a private, supportive workspace.</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-xl font-black text-[#FF8A00]">03</span>
                        <div>
                            <h4 class="font-bold text-sm text-[#111827]">Evidence-Guided Treatment</h4>
                            <p class="text-xs text-[#6B7280]">Progressive exercises, lifestyle changes, and therapy support.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- General CTA Form Section -->
<section id="booking-form" class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        <div class="bg-white border border-[#EEF7F4] rounded-[32px] p-8 sm:p-12 shadow-xl">
            <div class="text-center mb-8">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#111827]">Schedule Your Appointment</h2>
                <p class="text-sm text-[#6B7280] mt-2">All submissions are strictly confidential. We will call you within 1-2 hours.</p>
            </div>

            <!-- Validation Errors -->
            <div id="booking-errors" class="hidden mb-6 p-4 bg-[#FDF2F5] text-[#CC205C] rounded-2xl text-sm border border-[#CC205C]/20"></div>
            <!-- Success Message -->
            <div id="booking-success" class="hidden mb-6 p-4 bg-[#EEF7F4] text-[#006F5C] rounded-2xl text-sm border border-[#006F5C]/20"></div>

            <form action="{{ url('/appointments') }}" method="POST" id="main-appointment-form" class="space-y-6">
                @csrf
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
                        <label for="email" class="block text-sm font-semibold text-[#111827] mb-2">Email Address (Optional)</label>
                        <input type="email" name="email" id="email" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-full text-sm focus:outline-none focus:border-[#006F5C] transition-colors" placeholder="e.g. name@domain.com">
                    </div>
                    <div>
                        <label for="service_id" class="block text-sm font-semibold text-[#111827] mb-2">Select Treatment Service *</label>
                        <select name="service_id" id="service_id" required class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-full text-sm focus:outline-none focus:border-[#006F5C] transition-colors appearance-none">
                            <option value="">Choose a treatment...</option>
                            @if(isset($featuredServices))
                                @foreach($featuredServices as $serv)
                                    <option value="{{ $serv->id }}" {{ $serv->slug === 'mens-health-physiotherapy' ? 'selected' : '' }}>{{ $serv->title }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="preferred_date" class="block text-sm font-semibold text-[#111827] mb-2">Preferred Date *</label>
                        <input type="date" name="preferred_date" id="preferred_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ date('Y-m-d', strtotime('+1 day')) }}" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-full text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
                    </div>
                    <div>
                        <label for="preferred_time" class="block text-sm font-semibold text-[#111827] mb-2">Preferred Time *</label>
                        <select name="preferred_time" id="preferred_time" required class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-full text-sm focus:outline-none focus:border-[#006F5C] transition-colors appearance-none">
                            <option value="10:00 AM">10:00 AM</option>
                            <option value="11:00 AM">11:00 AM</option>
                            <option value="12:00 PM">12:00 PM</option>
                            <option value="03:00 PM">03:00 PM</option>
                            <option value="04:00 PM">04:00 PM</option>
                            <option value="05:00 PM">05:00 PM</option>
                            <option value="06:00 PM">06:00 PM</option>
                            <option value="07:00 PM">07:00 PM</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="note" class="block text-sm font-semibold text-[#111827] mb-2">Brief Message / Symptoms (Optional)</label>
                    <textarea name="note" id="note" rows="3" class="w-full px-5 py-4 bg-[#F4FAF8] border border-[#EEF7F4] rounded-3xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors" placeholder="Describe your situation in a few words..."></textarea>
                </div>

                <div class="text-center pt-4">
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 border border-transparent text-sm font-bold rounded-full text-white bg-[#006F5C] hover:bg-[#005547] shadow-md hover:shadow-lg transition-all duration-200">
                        Confirm Appointment Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Appointment Script -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('main-appointment-form');
        const errorsDiv = document.getElementById('booking-errors');
        const successDiv = document.getElementById('booking-success');

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

<script>
document.addEventListener('DOMContentLoaded', () => {
    const textEl = document.getElementById('hero-title-text');
    const cursorEl = document.getElementById('hero-cursor');
    if (!textEl) return;

    const text = "Confidential & Professional Men's Care";
    let i = 0;
    const speed = 60;

    function type() {
        if (i < text.length) {
            textEl.textContent += text.charAt(i);
            i++;
            setTimeout(type, speed);
        } else {
            if (cursorEl) cursorEl.style.animation = 'pulse 1s infinite';
        }
    }

    setTimeout(type, 400);
});
</script>
@endsection
