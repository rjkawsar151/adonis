@extends('layouts.app')

@section('title', "Mayfair Wellness Clinic - Services")

@section('content')
<!-- Hero Section -->
<section class="bg-[#006F5C] text-white py-16 rounded-b-[32px] shadow-sm relative overflow-hidden">
    @if(!empty($websiteSettings->hero_image))
    <div class="absolute inset-0">
        <img src="{{ asset($websiteSettings->hero_image) }}" alt="" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-[#005547]/60"></div>
    </div>
    @else
    <div class="absolute inset-0 bg-[#005547]/60"></div>
    @endif
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Our Services</h1>
        <p class="mt-3 text-lg text-white/85 max-w-2xl mx-auto">
            Comprehensive clinic-friendly care and therapy treatments for pelvic, urological, and musculoskeletal health.
        </p>
    </div>
</section>

<!-- Services Grid -->
<section class="py-20 bg-[#F4FAF8]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @if(isset($services) && count($services) > 0)
                @foreach($services as $serv)
                    <div class="bg-white border border-[#EEF7F4] rounded-[24px] overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-between p-8">
                        <div>
                            <div class="w-10 h-10 rounded-2xl bg-[#EEF7F4] text-[#006F5C] flex items-center justify-center mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-[#111827] mb-2">{{ $serv->title }}</h3>
                            <p class="text-sm text-[#6B7280] leading-relaxed mb-6 line-clamp-3">
                                {{ $serv->short_description }}
                            </p>
                        </div>
                        <div>
                            <a href="{{ url('/services/' . $serv->slug) }}" class="inline-flex items-center text-sm font-semibold text-[#006F5C] hover:text-[#CC205C] transition-colors">
                                <span>View Details</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-span-3 text-center py-12">
                    <p class="text-[#6B7280]">No services found in database.</p>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Condition Categories / Info Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center mb-12">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#111827]">Why Men Should Not Ignore Symptoms</h2>
            <p class="mt-4 text-base text-[#6B7280] leading-relaxed">
                Many men live with chronic pelvic pain, bladder control issues, or sexual discomfort thinking it is normal or untreatable. Pelvic floor physiotherapy and target rehabilitation are highly effective, non-surgical approaches that resolve symptoms and return you to active life.
            </p>
        </div>
    </div>
</section>
@endsection
