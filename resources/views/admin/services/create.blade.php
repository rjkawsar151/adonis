@extends('layouts.admin')

@section('title', 'Add New Service')
@section('page_title', 'Add New Service')

@section('admin_content')
<div class="mb-6">
    <a href="{{ url('/admin/services') }}" class="inline-flex items-center text-sm text-[#006F5C] hover:text-[#CC205C] font-semibold">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        Back to Services
    </a>
</div>

@if($errors->any())
    <div class="mb-6 p-4 bg-[#FDF2F5] text-[#CC205C] rounded-2xl text-sm border border-[#CC205C]/20">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ url('/admin/services') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
    @csrf

    <!-- Basic Info -->
    <div class="bg-white border border-[#EEF7F4] rounded-[24px] p-8 shadow-sm space-y-6">
        <h3 class="font-extrabold text-lg text-[#111827] border-b border-[#EEF7F4] pb-4">Basic Information</h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Service Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" required class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors" placeholder="e.g. Men's Health Physiotherapy">
            </div>
            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">URL Slug *</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors font-mono" placeholder="e.g. mens-health-physiotherapy">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Short Description</label>
            <textarea name="short_description" rows="2" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors" placeholder="Brief summary shown in service cards and sidebar...">{{ old('short_description') }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Main Description</label>
            <textarea name="main_description" rows="4" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors" placeholder="Full description of the service shown at the top of the detail page...">{{ old('main_description') }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Status *</label>
                <select name="status" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Sort Order *</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" required class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
            </div>
            <div class="flex flex-col justify-end space-y-3">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-4 h-4 accent-[#006F5C]">
                    <span class="text-sm font-semibold text-[#374151]">Featured Service</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="show_in_sidebar" value="1" {{ old('show_in_sidebar', true) ? 'checked' : '' }} class="w-4 h-4 accent-[#006F5C]">
                    <span class="text-sm font-semibold text-[#374151]">Show in Sidebar</span>
                </label>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="bg-white border border-[#EEF7F4] rounded-[24px] p-8 shadow-sm space-y-6">
        <h3 class="font-extrabold text-lg text-[#111827] border-b border-[#EEF7F4] pb-4">Hero Banner Settings</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Hero Title</label>
                <input type="text" name="hero_title" value="{{ old('hero_title') }}" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors" placeholder="e.g. Men's Health">
            </div>
            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Hero Subtitle</label>
                <input type="text" name="hero_subtitle" value="{{ old('hero_subtitle') }}" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors" placeholder="e.g. Confidential Care For Men's Wellness">
            </div>
        </div>
        <div>
            <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Hero Image</label>
            <input type="file" name="hero_image" accept="image/*" class="block w-full text-sm text-[#6B7280] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-[#EEF7F4] file:text-[#006F5C] hover:file:bg-[#006F5C] hover:file:text-white transition-all">
        </div>
        <div>
            <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Main Service Image</label>
            <input type="file" name="main_image" accept="image/*" class="block w-full text-sm text-[#6B7280] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-[#EEF7F4] file:text-[#006F5C] hover:file:bg-[#006F5C] hover:file:text-white transition-all">
        </div>
    </div>

    <!-- Cause Section -->
    <div class="bg-white border border-[#EEF7F4] rounded-[24px] p-8 shadow-sm space-y-6">
        <h3 class="font-extrabold text-lg text-[#111827] border-b border-[#EEF7F4] pb-4">Why This Problem Happens Section</h3>
        <div>
            <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Section Title</label>
            <input type="text" name="cause_title" value="{{ old('cause_title') }}" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors" placeholder="e.g. Why Does This Problem Happen?">
        </div>
        <div>
            <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Section Paragraph</label>
            <textarea name="cause_description" rows="3" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors">{{ old('cause_description') }}</textarea>
        </div>
        <div>
            <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Cause Bullet Points (one per line)</label>
            <div id="cause-bullets-container" class="space-y-2">
                @foreach(old('cause_bullets', ['']) as $bullet)
                    <div class="flex items-center space-x-2">
                        <input type="text" name="cause_bullets[]" value="{{ $bullet }}" class="flex-grow px-4 py-2 bg-[#F4FAF8] border border-[#EEF7F4] rounded-full text-sm focus:outline-none focus:border-[#006F5C]" placeholder="e.g. Pelvic floor muscle weakness...">
                        <button type="button" onclick="this.parentElement.remove()" class="text-[#CC205C] hover:text-red-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                @endforeach
            </div>
            <button type="button" onclick="addBullet('cause-bullets-container', 'cause_bullets[]')" class="mt-3 inline-flex items-center text-xs font-bold text-[#006F5C] hover:text-[#CC205C] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                Add Bullet Point
            </button>
        </div>
        <div>
            <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Cause Image</label>
            <input type="file" name="cause_image" accept="image/*" class="block w-full text-sm text-[#6B7280] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-[#EEF7F4] file:text-[#006F5C] hover:file:bg-[#006F5C] hover:file:text-white transition-all">
        </div>
    </div>

    <!-- Treatment Section -->
    <div class="bg-white border border-[#EEF7F4] rounded-[24px] p-8 shadow-sm space-y-6">
        <h3 class="font-extrabold text-lg text-[#111827] border-b border-[#EEF7F4] pb-4">How Mayfair Helps Section</h3>
        <div>
            <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Section Title</label>
            <input type="text" name="treatment_title" value="{{ old('treatment_title') }}" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors" placeholder="e.g. How Mayfair Helps You Recover">
        </div>
        <div>
            <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Treatment Paragraph</label>
            <textarea name="treatment_description" rows="3" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors">{{ old('treatment_description') }}</textarea>
        </div>
        <div>
            <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Treatment Bullet Points</label>
            <div id="treatment-bullets-container" class="space-y-2">
                @foreach(old('treatment_bullets', ['']) as $bullet)
                    <div class="flex items-center space-x-2">
                        <input type="text" name="treatment_bullets[]" value="{{ $bullet }}" class="flex-grow px-4 py-2 bg-[#F4FAF8] border border-[#EEF7F4] rounded-full text-sm focus:outline-none focus:border-[#006F5C]" placeholder="e.g. Pelvic floor muscle training...">
                        <button type="button" onclick="this.parentElement.remove()" class="text-[#CC205C] hover:text-red-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                @endforeach
            </div>
            <button type="button" onclick="addBullet('treatment-bullets-container', 'treatment_bullets[]')" class="mt-3 inline-flex items-center text-xs font-bold text-[#006F5C] hover:text-[#CC205C] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                Add Bullet Point
            </button>
        </div>
        <div>
            <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Treatment Image</label>
            <input type="file" name="treatment_image" accept="image/*" class="block w-full text-sm text-[#6B7280] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-[#EEF7F4] file:text-[#006F5C] hover:file:bg-[#006F5C] hover:file:text-white transition-all">
        </div>
    </div>

    <!-- Treatment Steps -->
    <div class="bg-white border border-[#EEF7F4] rounded-[24px] p-8 shadow-sm space-y-6">
        <h3 class="font-extrabold text-lg text-[#111827] border-b border-[#EEF7F4] pb-4">Treatment Process Steps</h3>
        <div id="steps-container" class="space-y-4">
            @foreach(old('steps', [
                ['title' => 'Get Consultation', 'description' => 'Share your symptoms privately and get expert support.', 'status' => 'active'],
                ['title' => 'Make Appointment', 'description' => 'Choose your preferred time and book easily online.', 'status' => 'active'],
                ['title' => 'Select Doctor', 'description' => 'Meet a specialist for a personalized treatment plan.', 'status' => 'active'],
                ['title' => 'Start Treatment', 'description' => 'Follow your plan and track your recovery progress.', 'status' => 'active'],
            ]) as $index => $step)
                <div class="step-row grid grid-cols-1 lg:grid-cols-12 gap-4 p-4 bg-[#F4FAF8] rounded-2xl border border-[#EEF7F4]">
                    <div class="lg:col-span-3">
                        <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Step Title</label>
                        <input type="text" name="steps[{{ $index }}][title]" value="{{ $step['title'] ?? '' }}" class="w-full px-4 py-3 bg-white border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C]">
                    </div>
                    <div class="lg:col-span-6">
                        <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Description</label>
                        <textarea name="steps[{{ $index }}][description]" rows="2" class="w-full px-4 py-3 bg-white border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C]">{{ $step['description'] ?? '' }}</textarea>
                    </div>
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Status</label>
                        <select name="steps[{{ $index }}][status]" class="w-full px-4 py-3 bg-white border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C]">
                            <option value="active" {{ ($step['status'] ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ ($step['status'] ?? 'active') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="lg:col-span-1 flex items-end">
                        <button type="button" onclick="this.closest('.step-row').remove()" class="w-full h-11 inline-flex items-center justify-center rounded-full text-[#CC205C] bg-white hover:bg-[#CC205C] hover:text-white transition-colors" title="Remove step">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        <button type="button" onclick="addStep()" class="inline-flex items-center text-xs font-bold text-[#006F5C] hover:text-[#CC205C] transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            Add Treatment Step
        </button>
    </div>

    <!-- SEO Section -->
    <div class="bg-white border border-[#EEF7F4] rounded-[24px] p-8 shadow-sm space-y-6">
        <h3 class="font-extrabold text-lg text-[#111827] border-b border-[#EEF7F4] pb-4">SEO Settings</h3>
        <div>
            <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">SEO Title</label>
            <input type="text" name="seo_title" value="{{ old('seo_title') }}" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors" placeholder="e.g. Men's Health Physiotherapy in Dhaka | Mayfair Wellness Clinic">
        </div>
        <div>
            <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">SEO Meta Description</label>
            <textarea name="seo_description" rows="2" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors" placeholder="Short meta description (155 chars)...">{{ old('seo_description') }}</textarea>
        </div>
    </div>

    <div class="flex space-x-4">
        <button type="submit" class="inline-flex items-center justify-center px-8 py-3.5 border border-transparent text-sm font-bold rounded-full text-white bg-[#006F5C] hover:bg-[#005547] shadow-md hover:shadow-lg transition-all duration-200">
            Create Service
        </button>
        <a href="{{ url('/admin/services') }}" class="inline-flex items-center justify-center px-8 py-3.5 border-2 border-[#EEF7F4] text-sm font-bold rounded-full text-[#6B7280] hover:bg-[#EEF7F4] transition-colors">
            Cancel
        </a>
    </div>
</form>

<script>
    function addBullet(containerId, inputName) {
        const container = document.getElementById(containerId);
        const row = document.createElement('div');
        row.className = 'flex items-center space-x-2';
        row.innerHTML = `
            <input type="text" name="${inputName}" class="flex-grow px-4 py-2 bg-[#F4FAF8] border border-[#EEF7F4] rounded-full text-sm focus:outline-none focus:border-[#006F5C]" placeholder="Enter bullet point...">
            <button type="button" onclick="this.parentElement.remove()" class="text-[#CC205C] hover:text-red-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        `;
        container.appendChild(row);
    }

    function addStep() {
        const container = document.getElementById('steps-container');
        const index = container.querySelectorAll('.step-row').length;
        const row = document.createElement('div');
        row.className = 'step-row grid grid-cols-1 lg:grid-cols-12 gap-4 p-4 bg-[#F4FAF8] rounded-2xl border border-[#EEF7F4]';
        row.innerHTML = `
            <div class="lg:col-span-3">
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Step Title</label>
                <input type="text" name="steps[${index}][title]" class="w-full px-4 py-3 bg-white border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C]">
            </div>
            <div class="lg:col-span-6">
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Description</label>
                <textarea name="steps[${index}][description]" rows="2" class="w-full px-4 py-3 bg-white border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C]"></textarea>
            </div>
            <div class="lg:col-span-2">
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Status</label>
                <select name="steps[${index}][status]" class="w-full px-4 py-3 bg-white border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C]">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="lg:col-span-1 flex items-end">
                <button type="button" onclick="this.closest('.step-row').remove()" class="w-full h-11 inline-flex items-center justify-center rounded-full text-[#CC205C] bg-white hover:bg-[#CC205C] hover:text-white transition-colors" title="Remove step">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        `;
        container.appendChild(row);
    }

    // Auto-generate slug from title
    document.querySelector('[name="title"]').addEventListener('input', function() {
        const slug = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
        document.getElementById('slug').value = slug;
    });
</script>
@endsection
