@extends('layouts.admin')

@section('title', 'Manage About Us Page')
@section('page_title', 'About Us Page Content Management')

@section('admin_content')
<!-- CKEditor CDN -->
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>

<!-- Tabs Navigation -->
<div class="mb-8 border-b border-gray-800 shrink-0">
    <div class="flex flex-wrap -mb-px text-sm font-medium text-center" id="about-tabs" role="tablist">
        <button onclick="switchTab('tab-chairman')" class="tab-btn active mr-2 py-3 px-4 border-b-2 border-[#C9A84C] text-[#C9A84C] focus:outline-none" id="btn-tab-chairman">Chairman Message</button>
        <button onclick="switchTab('tab-md')" class="tab-btn mr-2 py-3 px-4 border-b-2 border-transparent text-gray-400 hover:text-white focus:outline-none" id="btn-tab-md">MD Message</button>
        <button onclick="switchTab('tab-intro')" class="tab-btn mr-2 py-3 px-4 border-b-2 border-transparent text-gray-400 hover:text-white focus:outline-none" id="btn-tab-intro">Company Intro</button>
        <button onclick="switchTab('tab-mission')" class="tab-btn mr-2 py-3 px-4 border-b-2 border-transparent text-gray-400 hover:text-white focus:outline-none" id="btn-tab-mission">Mission & Vision</button>
        <button onclick="switchTab('tab-values')" class="tab-btn mr-2 py-3 px-4 border-b-2 border-transparent text-gray-400 hover:text-white focus:outline-none" id="btn-tab-values">Core Values & Stats</button>
        <button onclick="switchTab('tab-timeline')" class="tab-btn mr-2 py-3 px-4 border-b-2 border-transparent text-gray-400 hover:text-white focus:outline-none" id="btn-tab-timeline">Timeline & Team</button>
        <button onclick="switchTab('tab-cta')" class="tab-btn mr-2 py-3 px-4 border-b-2 border-transparent text-gray-400 hover:text-white focus:outline-none" id="btn-tab-cta">CTA Settings</button>
    </div>
</div>

<!-- Alert Notifications -->
@if(session('success'))
    <div class="mb-6 p-4 bg-green-900/30 text-green-400 text-sm border border-green-800/50">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-6 p-4 bg-red-900/30 text-red-400 text-sm border border-red-800/50">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Tab Contents -->
<div id="tab-contents">
    
    <!-- 1. Chairman Message Tab -->
    <div id="tab-chairman" class="tab-panel space-y-6">
        <form action="{{ route('admin.about-page.chairman') }}" method="POST" enctype="multipart/form-data" class="bg-[#111827] border border-gray-800 p-8 space-y-6">
            @csrf
            <h3 class="font-extrabold text-lg text-white border-b border-gray-800 pb-4">Chairman Message Section</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Chairman's Name *</label>
                    <input type="text" name="name" value="{{ old('name', $chairmanMessage->name) }}" required class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Designation *</label>
                    <input type="text" name="designation" value="{{ old('designation', $chairmanMessage->designation) }}" required class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Message Title *</label>
                    <input type="text" name="title" value="{{ old('title', $chairmanMessage->title) }}" required class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Highlighted Quotation</label>
                    <input type="text" name="quotation" value="{{ old('quotation', $chairmanMessage->quotation) }}" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Chairman Speech / Message *</label>
                <textarea name="speech" id="editor-speech">{{ old('speech', $chairmanMessage->speech) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                <div class="space-y-4">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Chairman's Photo</label>
                    @if($chairmanMessage->photo)
                        <div class="mb-2">
                            <img src="{{ asset($chairmanMessage->photo) }}" alt="Chairman" class="h-32 w-auto object-cover border border-gray-800">
                        </div>
                    @endif
                    <input type="file" name="photo" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-bold file:bg-gray-800 file:text-[#C9A84C] hover:file:bg-[#C9A84C] hover:file:text-black">
                </div>

                <div class="space-y-4">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Signature Image</label>
                    @if($chairmanMessage->signature_image)
                        <div class="mb-2">
                            <img src="{{ asset($chairmanMessage->signature_image) }}" alt="Signature" class="h-16 w-auto object-contain border border-gray-800 bg-white p-1">
                        </div>
                    @endif
                    <input type="file" name="signature_image" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-bold file:bg-gray-800 file:text-[#C9A84C] hover:file:bg-[#C9A84C] hover:file:text-black">
                </div>
            </div>

            <div class="flex items-center pt-4">
                <input type="checkbox" name="is_active" id="chairman_active" value="1" {{ $chairmanMessage->is_active ? 'checked' : '' }} class="h-4 w-4 bg-gray-800 border-gray-700 text-[#C9A84C] rounded focus:ring-0 focus:ring-offset-0">
                <label for="chairman_active" class="ml-2 text-xs font-bold text-gray-300 uppercase tracking-wider">Display this section on website</label>
            </div>

            <div class="pt-4">
                <button type="submit" class="px-6 py-3 bg-[#C9A84C] hover:bg-[#b08d3c] text-black font-serif text-xs font-extrabold uppercase tracking-widest transition-colors">Save Chairman Message</button>
            </div>
        </form>
    </div>

    <!-- MD Message Tab -->
    <div id="tab-md" class="tab-panel hidden space-y-6">
        <form action="{{ route('admin.about-page.md') }}" method="POST" enctype="multipart/form-data" class="bg-[#111827] border border-gray-800 p-8 space-y-6">
            @csrf
            <h3 class="font-extrabold text-lg text-white border-b border-gray-800 pb-4">Managing Director Message Section</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">MD's Name *</label>
                    <input type="text" name="name" value="{{ old('name', $mdMessage->name) }}" required class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Designation *</label>
                    <input type="text" name="designation" value="{{ old('designation', $mdMessage->designation) }}" required class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Message Title *</label>
                    <input type="text" name="title" value="{{ old('title', $mdMessage->title) }}" required class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Highlighted Quotation</label>
                    <input type="text" name="quotation" value="{{ old('quotation', $mdMessage->quotation) }}" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">MD Speech / Message *</label>
                <textarea name="speech" id="editor-md-speech">{{ old('speech', $mdMessage->speech) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                <div class="space-y-4">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">MD's Photo</label>
                    @if($mdMessage->photo)
                        <div class="mb-2">
                            <img src="{{ asset($mdMessage->photo) }}" alt="MD" class="h-32 w-auto object-cover border border-gray-800">
                        </div>
                    @endif
                    <input type="file" name="photo" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-bold file:bg-gray-800 file:text-[#C9A84C] hover:file:bg-[#C9A84C] hover:file:text-black">
                </div>

                <div class="space-y-4">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Signature Image</label>
                    @if($mdMessage->signature_image)
                        <div class="mb-2">
                            <img src="{{ asset($mdMessage->signature_image) }}" alt="Signature" class="h-16 w-auto object-contain border border-gray-800 bg-white p-1">
                        </div>
                    @endif
                    <input type="file" name="signature_image" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-bold file:bg-gray-800 file:text-[#C9A84C] hover:file:bg-[#C9A84C] hover:file:text-black">
                </div>
            </div>

            <div class="flex items-center pt-4">
                <input type="checkbox" name="is_active" id="md_active" value="1" {{ $mdMessage->is_active ? 'checked' : '' }} class="h-4 w-4 bg-gray-800 border-gray-700 text-[#C9A84C] rounded focus:ring-0 focus:ring-offset-0">
                <label for="md_active" class="ml-2 text-xs font-bold text-gray-300 uppercase tracking-wider">Display this section on website</label>
            </div>

            <div class="pt-4">
                <button type="submit" class="px-6 py-3 bg-[#C9A84C] hover:bg-[#b08d3c] text-black font-serif text-xs font-extrabold uppercase tracking-widest transition-colors">Save MD Message</button>
            </div>
        </form>
    </div>

    <!-- 2. Company Intro Tab -->
    <div id="tab-intro" class="tab-panel hidden space-y-6">
        <form action="{{ route('admin.about-page.intro') }}" method="POST" enctype="multipart/form-data" class="bg-[#111827] border border-gray-800 p-8 space-y-6">
            @csrf
            <h3 class="font-extrabold text-lg text-white border-b border-gray-800 pb-4">Company Introduction</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Section Title *</label>
                    <input type="text" name="title" value="{{ old('title', $companyIntro->title) }}" required class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Short Subtitle</label>
                    <input type="text" name="subtitle" value="{{ old('subtitle', $companyIntro->subtitle) }}" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Full Description *</label>
                <textarea name="description" id="editor-description">{{ old('description', $companyIntro->description) }}</textarea>
            </div>

            <div class="space-y-4">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Featured Image</label>
                @if($companyIntro->featured_image)
                    <div class="mb-2">
                        <img src="{{ asset($companyIntro->featured_image) }}" alt="Featured" class="h-32 w-auto object-cover border border-gray-800">
                    </div>
                @endif
                <input type="file" name="featured_image" accept="image/jpeg,image/png,image/gif,image/webp" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-bold file:bg-gray-800 file:text-[#C9A84C] hover:file:bg-[#C9A84C] hover:file:text-black">
                <p class="text-[11px] text-gray-500">JPG, PNG, GIF, or WebP. Maximum file size: 15 MB.</p>
            </div>

            <div class="flex items-center pt-4">
                <input type="checkbox" name="is_active" id="intro_active" value="1" {{ $companyIntro->is_active ? 'checked' : '' }} class="h-4 w-4 bg-gray-800 border-gray-700 text-[#C9A84C] rounded focus:ring-0 focus:ring-offset-0">
                <label for="intro_active" class="ml-2 text-xs font-bold text-gray-300 uppercase tracking-wider">Display this section on website</label>
            </div>

            <div class="pt-4">
                <button type="submit" class="px-6 py-3 bg-[#C9A84C] hover:bg-[#b08d3c] text-black font-serif text-xs font-extrabold uppercase tracking-widest transition-colors">Save Introduction</button>
            </div>
        </form>
    </div>

    <!-- 3. Mission & Vision Tab -->
    <div id="tab-mission" class="tab-panel hidden space-y-8">
        <!-- New Mission/Vision Form -->
        <div class="bg-[#111827] border border-gray-800 p-8 space-y-6">
            <h3 class="font-extrabold text-lg text-white border-b border-gray-800 pb-4">Add Mission or Vision</h3>
            <form action="{{ route('admin.about-page.missions-visions.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Section Type *</label>
                        <select name="type" required class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                            <option value="mission">Mission</option>
                            <option value="vision">Vision</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Title *</label>
                        <input type="text" name="title" required placeholder="e.g. Our Purpose" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Icon (Lucide name)</label>
                        <input type="text" name="icon_or_image" placeholder="e.g. Compass, Sparkles" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Short Description</label>
                    <input type="text" name="short_description" placeholder="Brief summary" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Full Content *</label>
                    <textarea name="content" id="editor-mission" class="w-full bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]"></textarea>
                </div>

                <div class="flex items-center pt-2 mb-4">
                    <input type="checkbox" name="is_active" id="mission_active_new" value="1" checked class="h-4 w-4 bg-gray-800 border-gray-700 text-[#C9A84C] rounded focus:ring-0">
                    <label for="mission_active_new" class="ml-2 text-xs font-bold text-gray-300 uppercase tracking-wider">Active</label>
                </div>

                <button type="submit" class="px-5 py-2.5 bg-[#C9A84C] hover:bg-[#b08d3c] text-black font-serif text-xs font-extrabold uppercase tracking-widest transition-colors">Add Section</button>
            </form>
        </div>

        <!-- List Missions & Visions -->
        <div class="bg-[#111827] border border-gray-800 p-8 space-y-6">
            <h3 class="font-extrabold text-lg text-white border-b border-gray-800 pb-4">Current Sections</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-400">
                    <thead class="text-xs uppercase bg-[#0c1017] text-gray-400 font-bold border-b border-gray-800">
                        <tr>
                            <th class="px-6 py-4">Sort</th>
                            <th class="px-6 py-4">Type</th>
                            <th class="px-6 py-4">Title</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="mission-sort-list" class="divide-y divide-gray-800">
                        @forelse($missionsVisions as $m)
                            <tr data-id="{{ $m->id }}">
                                <td class="px-6 py-4 cursor-move drag-handle text-[#C9A84C]">☰</td>
                                <td class="px-6 py-4 uppercase text-xs">{{ $m->type }}</td>
                                <td class="px-6 py-4 text-white font-medium">{{ $m->title }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ $m->is_active ? 'bg-green-900/30 text-green-400 border border-green-800/40' : 'bg-gray-800 text-gray-500 border border-gray-700/50' }}">
                                        {{ $m->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <!-- Edit Trigger -->
                                    <button onclick="openEditMissionModal({{ json_encode($m) }})" class="text-blue-400 hover:text-blue-300 text-xs">Edit</button>
                                    
                                    <form action="{{ route('admin.about-page.missions-visions.destroy', $m->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this section?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-400 text-xs">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500 text-xs">No mission or vision statements found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 4. Core Values & Stats Tab -->
    <div id="tab-values" class="tab-panel hidden space-y-8">
        <!-- Dynamic Core Values CRUD -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Core Value Management -->
            <div class="bg-[#111827] border border-gray-800 p-8 space-y-6">
                <h3 class="font-extrabold text-lg text-white border-b border-gray-800 pb-4">Core Values</h3>
                
                <form action="{{ route('admin.about-page.core-values.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Value Title *</label>
                            <input type="text" name="title" required placeholder="e.g. Integrity" class="w-full px-4 py-2.5 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Icon (Lucide name)</label>
                            <input type="text" name="icon" placeholder="e.g. ShieldAlert" class="w-full px-4 py-2.5 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Short Description *</label>
                        <textarea name="description" required rows="2" placeholder="Explain value..." class="w-full px-4 py-2.5 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]"></textarea>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="val_active" value="1" checked class="h-4 w-4 bg-gray-800 border-gray-700 text-[#C9A84C] rounded focus:ring-0">
                        <label for="val_active" class="ml-2 text-xs font-bold text-gray-300 uppercase tracking-wider">Active</label>
                    </div>
                    <button type="submit" class="px-5 py-2.5 bg-[#C9A84C] text-black font-serif text-xs font-extrabold uppercase tracking-widest">Add Value</button>
                </form>

                <div class="mt-6 border-t border-gray-800 pt-6 overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-400">
                        <thead class="text-xs uppercase bg-[#0c1017] text-gray-400 font-bold border-b border-gray-800">
                            <tr>
                                <th class="px-4 py-3">Sort</th>
                                <th class="px-4 py-3">Value</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="values-sort-list" class="divide-y divide-gray-800">
                            @forelse($coreValues as $cv)
                                <tr data-id="{{ $cv->id }}">
                                    <td class="px-4 py-3 text-[#C9A84C] cursor-move drag-handle">☰</td>
                                    <td class="px-4 py-3 text-white font-medium">{{ $cv->title }}</td>
                                    <td class="px-4 py-3 text-right space-x-2">
                                        <button onclick="openEditValueModal({{ json_encode($cv) }})" class="text-blue-400 hover:text-blue-300 text-xs">Edit</button>
                                        <form action="{{ route('admin.about-page.core-values.destroy', $cv->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-400 text-xs">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-4 text-center text-gray-500 text-xs">No core values set.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Why Choose Us Management -->
            <div class="bg-[#111827] border border-gray-800 p-8 space-y-6">
                <h3 class="font-extrabold text-lg text-white border-b border-gray-800 pb-4">Why Choose Us Points</h3>
                
                <form action="{{ route('admin.about-page.why-choose-us.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Point Title *</label>
                            <input type="text" name="title" required placeholder="e.g. Reliable support" class="w-full px-4 py-2.5 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Icon (Lucide name)</label>
                            <input type="text" name="icon" placeholder="e.g. PhoneCall" class="w-full px-4 py-2.5 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Short Description *</label>
                        <textarea name="description" required rows="2" placeholder="Explain details..." class="w-full px-4 py-2.5 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]"></textarea>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="choose_active" value="1" checked class="h-4 w-4 bg-gray-800 border-gray-700 text-[#C9A84C] rounded focus:ring-0">
                        <label for="choose_active" class="ml-2 text-xs font-bold text-gray-300 uppercase tracking-wider">Active</label>
                    </div>
                    <button type="submit" class="px-5 py-2.5 bg-[#C9A84C] text-black font-serif text-xs font-extrabold uppercase tracking-widest">Add Point</button>
                </form>

                <div class="mt-6 border-t border-gray-800 pt-6 overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-400">
                        <thead class="text-xs uppercase bg-[#0c1017] text-gray-400 font-bold border-b border-gray-800">
                            <tr>
                                <th class="px-4 py-3">Sort</th>
                                <th class="px-4 py-3">Point</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="choose-sort-list" class="divide-y divide-gray-800">
                            @forelse($whyChooseUs as $point)
                                <tr data-id="{{ $point->id }}">
                                    <td class="px-4 py-3 text-[#C9A84C] cursor-move drag-handle">☰</td>
                                    <td class="px-4 py-3 text-white font-medium">{{ $point->title }}</td>
                                    <td class="px-4 py-3 text-right space-x-2">
                                        <button onclick="openEditChooseModal({{ json_encode($point) }})" class="text-blue-400 hover:text-blue-300 text-xs">Edit</button>
                                        <form action="{{ route('admin.about-page.why-choose-us.destroy', $point->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-400 text-xs">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-4 text-center text-gray-500 text-xs">No points set.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Dynamic Statistics CRUD -->
        <div class="bg-[#111827] border border-gray-800 p-8 space-y-6">
            <h3 class="font-extrabold text-lg text-white border-b border-gray-800 pb-4">Company Statistics (Counters)</h3>
            
            <form action="{{ route('admin.about-page.statistics.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Number *</label>
                    <input type="text" name="counter_number" required placeholder="e.g. 50000" class="w-full px-4 py-2.5 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Suffix</label>
                    <input type="text" name="suffix" placeholder="e.g. +, %" class="w-full px-4 py-2.5 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Label *</label>
                    <input type="text" name="label" required placeholder="e.g. Happy Clients" class="w-full px-4 py-2.5 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Icon</label>
                    <input type="text" name="icon" placeholder="e.g. Smile" class="w-full px-4 py-2.5 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="stat_active" value="1" checked class="h-4 w-4 bg-gray-800 border-gray-700 text-[#C9A84C] rounded focus:ring-0">
                        <label for="stat_active" class="ml-2 text-xs font-bold text-gray-300 uppercase tracking-wider">Active</label>
                    </div>
                    <button type="submit" class="px-5 py-2.5 bg-[#C9A84C] text-black font-serif text-xs font-extrabold uppercase tracking-widest leading-none">Add Stat</button>
                </div>
            </form>

            <div class="mt-6 border-t border-gray-800 pt-6 overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-400">
                    <thead class="text-xs uppercase bg-[#0c1017] text-gray-400 font-bold border-b border-gray-800">
                        <tr>
                            <th class="px-4 py-3">Sort</th>
                            <th class="px-4 py-3">Counter</th>
                            <th class="px-4 py-3">Label</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="stats-sort-list" class="divide-y divide-gray-800">
                        @forelse($statistics as $stat)
                            <tr data-id="{{ $stat->id }}">
                                <td class="px-4 py-3 text-[#C9A84C] cursor-move drag-handle">☰</td>
                                <td class="px-4 py-3 text-white font-medium">{{ $stat->counter_number }}{{ $stat->suffix }}</td>
                                <td class="px-4 py-3">{{ $stat->label }}</td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <button onclick="openEditStatModal({{ json_encode($stat) }})" class="text-blue-400 hover:text-blue-300 text-xs">Edit</button>
                                    <form action="{{ route('admin.about-page.statistics.destroy', $stat->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-400 text-xs">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-gray-500 text-xs">No statistics configured.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 5. Timeline & Team Tab -->
    <div id="tab-timeline" class="tab-panel hidden space-y-8">
        
        <!-- Company Journey Timeline CRUD -->
        <div class="bg-[#111827] border border-gray-800 p-8 space-y-6">
            <h3 class="font-extrabold text-lg text-white border-b border-gray-800 pb-4">Our Journey Timeline</h3>
            
            <form action="{{ route('admin.about-page.timelines.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Year / Date *</label>
                        <input type="text" name="year_or_date" required placeholder="e.g. 2014" class="w-full px-4 py-2.5 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Milestone Title *</label>
                        <input type="text" name="title" required placeholder="e.g. Grand Opening" class="w-full px-4 py-2.5 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Image</label>
                        <input type="file" name="image" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-bold file:bg-gray-800 file:text-[#C9A84C] hover:file:bg-[#C9A84C] hover:file:text-black">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Short Description *</label>
                    <textarea name="description" required rows="2" placeholder="Briefly describe what happened..." class="w-full px-4 py-2.5 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]"></textarea>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="time_active" value="1" checked class="h-4 w-4 bg-gray-800 border-gray-700 text-[#C9A84C] rounded focus:ring-0">
                    <label for="time_active" class="ml-2 text-xs font-bold text-gray-300 uppercase tracking-wider">Active</label>
                </div>
                <button type="submit" class="px-5 py-2.5 bg-[#C9A84C] text-black font-serif text-xs font-extrabold uppercase tracking-widest">Add Milestone</button>
            </form>

            <div class="mt-6 border-t border-gray-800 pt-6 overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-400">
                    <thead class="text-xs uppercase bg-[#0c1017] text-gray-400 font-bold border-b border-gray-800">
                        <tr>
                            <th class="px-4 py-3">Sort</th>
                            <th class="px-4 py-3">Year</th>
                            <th class="px-4 py-3">Milestone</th>
                            <th class="px-4 py-3">Image</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="timeline-sort-list" class="divide-y divide-gray-800">
                        @forelse($timelines as $t)
                            <tr data-id="{{ $t->id }}">
                                <td class="px-4 py-3 text-[#C9A84C] cursor-move drag-handle">☰</td>
                                <td class="px-4 py-3 text-white font-medium">{{ $t->year_or_date }}</td>
                                <td class="px-4 py-3">{{ $t->title }}</td>
                                <td class="px-4 py-3">
                                    @if($t->image)
                                        <img src="{{ asset($t->image) }}" class="h-10 w-10 object-cover border border-gray-800">
                                    @else
                                        <span class="text-gray-600 text-xs">No image</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <button onclick="openEditTimelineModal({{ json_encode($t) }})" class="text-blue-400 hover:text-blue-300 text-xs">Edit</button>
                                    <form action="{{ route('admin.about-page.timelines.destroy', $t->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-400 text-xs">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-gray-500 text-xs">No milestones added.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Management Team CRUD -->
        <div class="bg-[#111827] border border-gray-800 p-8 space-y-6">
            <h3 class="font-extrabold text-lg text-white border-b border-gray-800 pb-4">Leadership & Management Team</h3>
            
            <form action="{{ route('admin.about-page.team-members.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Name *</label>
                        <input type="text" name="name" required placeholder="Full Name" class="w-full px-4 py-2.5 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Designation *</label>
                        <input type="text" name="designation" required placeholder="e.g. Managing Director" class="w-full px-4 py-2.5 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Profile Photo</label>
                        <input type="file" name="photo" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-bold file:bg-gray-800 file:text-[#C9A84C] hover:file:bg-[#C9A84C] hover:file:text-black">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Facebook URL</label>
                        <input type="url" name="facebook_url" placeholder="https://facebook.com/username" class="w-full px-4 py-2.5 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">LinkedIn URL</label>
                        <input type="url" name="linkedin_url" placeholder="https://linkedin.com/in/username" class="w-full px-4 py-2.5 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Email Address</label>
                        <input type="email" name="email" placeholder="email@domain.com" class="w-full px-4 py-2.5 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Short Biography</label>
                    <textarea name="biography" rows="2" placeholder="Introduce this leader..." class="w-full px-4 py-2.5 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]"></textarea>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="team_active" value="1" checked class="h-4 w-4 bg-gray-800 border-gray-700 text-[#C9A84C] rounded focus:ring-0">
                    <label for="team_active" class="ml-2 text-xs font-bold text-gray-300 uppercase tracking-wider">Active</label>
                </div>

                <button type="submit" class="px-5 py-2.5 bg-[#C9A84C] text-black font-serif text-xs font-extrabold uppercase tracking-widest">Add Member</button>
            </form>

            <div class="mt-6 border-t border-gray-800 pt-6 overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-400">
                    <thead class="text-xs uppercase bg-[#0c1017] text-gray-400 font-bold border-b border-gray-800">
                        <tr>
                            <th class="px-4 py-3">Sort</th>
                            <th class="px-4 py-3">Photo</th>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Designation</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="team-sort-list" class="divide-y divide-gray-800">
                        @forelse($teamMembers as $member)
                            <tr data-id="{{ $member->id }}">
                                <td class="px-4 py-3 text-[#C9A84C] cursor-move drag-handle">☰</td>
                                <td class="px-4 py-3">
                                    @if($member->photo)
                                        <img src="{{ asset($member->photo) }}" class="h-10 w-10 object-cover rounded-full border border-gray-800">
                                    @else
                                        <div class="h-10 w-10 bg-gray-800 rounded-full border border-gray-700"></div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-white font-medium">{{ $member->name }}</td>
                                <td class="px-4 py-3 text-xs">{{ $member->designation }}</td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <button onclick="openEditTeamModal({{ json_encode($member) }})" class="text-blue-400 hover:text-blue-300 text-xs">Edit</button>
                                    <form action="{{ route('admin.about-page.team-members.destroy', $member->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-400 text-xs">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-gray-500 text-xs">No team members added.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 6. CTA Settings Tab -->
    <div id="tab-cta" class="tab-panel hidden space-y-6">
        <form action="{{ route('admin.about-page.cta') }}" method="POST" enctype="multipart/form-data" class="bg-[#111827] border border-gray-800 p-8 space-y-6">
            @csrf
            <h3 class="font-extrabold text-lg text-white border-b border-gray-800 pb-4">CTA Section Settings</h3>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">CTA Title *</label>
                <input type="text" name="title" value="{{ old('title', $cta->title) }}" required class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">CTA Description *</label>
                <textarea name="description" required rows="3" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">{{ old('description', $cta->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Primary Button Text *</label>
                    <input type="text" name="primary_button_text" value="{{ old('primary_button_text', $cta->primary_button_text) }}" required class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Primary Button URL *</label>
                    <input type="text" name="primary_button_url" value="{{ old('primary_button_url', $cta->primary_button_url) }}" required class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Secondary Button Text *</label>
                    <input type="text" name="secondary_button_text" value="{{ old('secondary_button_text', $cta->secondary_button_text) }}" required class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Secondary Button URL *</label>
                    <input type="text" name="secondary_button_url" value="{{ old('secondary_button_url', $cta->secondary_button_url) }}" required class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                </div>
            </div>

            <div class="space-y-4">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Background Image</label>
                @if($cta->background_image)
                    <div class="mb-2">
                        <img src="{{ asset($cta->background_image) }}" alt="CTA Background" class="h-32 w-auto object-cover border border-gray-800">
                    </div>
                @endif
                <input type="file" name="background_image" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-bold file:bg-gray-800 file:text-[#C9A84C] hover:file:bg-[#C9A84C] hover:file:text-black">
            </div>

            <div class="flex items-center pt-4">
                <input type="checkbox" name="is_active" id="cta_active" value="1" {{ $cta->is_active ? 'checked' : '' }} class="h-4 w-4 bg-gray-800 border-gray-700 text-[#C9A84C] rounded focus:ring-0 focus:ring-offset-0">
                <label for="cta_active" class="ml-2 text-xs font-bold text-gray-300 uppercase tracking-wider">Display CTA on website</label>
            </div>

            <div class="pt-4">
                <button type="submit" class="px-6 py-3 bg-[#C9A84C] hover:bg-[#b08d3c] text-black font-serif text-xs font-extrabold uppercase tracking-widest transition-colors">Save CTA settings</button>
            </div>
        </form>
    </div>

</div>

<!-- Universal Edit Modal for Missions/Visions -->
<div id="modal-edit-mission" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/60 backdrop-blur-sm">
    <div class="bg-[#111827] border border-gray-800 p-8 max-w-2xl w-full mx-4 space-y-4">
        <div class="flex justify-between items-center border-b border-gray-800 pb-3">
            <h3 class="text-sm font-bold uppercase tracking-wider text-white">Modify Mission/Vision</h3>
            <button onclick="closeModal('modal-edit-mission')" class="text-gray-400 hover:text-white">&times;</button>
        </div>
        <form id="form-edit-mission" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Type *</label>
                    <select name="type" id="edit-mission-type" required class="w-full px-4 py-2.5 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none">
                        <option value="mission">Mission</option>
                        <option value="vision">Vision</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Title *</label>
                    <input type="text" name="title" id="edit-mission-title" required class="w-full px-4 py-2.5 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Short Description</label>
                <input type="text" name="short_description" id="edit-mission-short-description" class="w-full px-4 py-2.5 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none">
            </div>
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Icon</label>
                <input type="text" name="icon_or_image" id="edit-mission-icon" class="w-full px-4 py-2.5 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none">
            </div>
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Content *</label>
                <textarea name="content" id="edit-mission-content"></textarea>
            </div>
            <div class="flex items-center mb-4">
                <input type="checkbox" name="is_active" id="edit-mission-active" value="1" class="h-4 w-4 bg-gray-800 border-gray-700 text-[#C9A84C]">
                <label for="edit-mission-active" class="ml-2 text-xs font-bold text-gray-300 uppercase tracking-wider">Active</label>
            </div>
            <div class="flex justify-end space-x-3 pt-3 border-t border-gray-800">
                <button type="button" onclick="closeModal('modal-edit-mission')" class="px-4 py-2 bg-gray-800 text-gray-300 text-xs font-bold uppercase tracking-wider">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-[#C9A84C] text-black text-xs font-serif font-bold uppercase tracking-wider">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Add similar modal containers for edit Value, Choose Us, Stat, Timeline and Team -->
<div id="modal-edit-item" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/60 backdrop-blur-sm">
    <div class="bg-[#111827] border border-gray-800 p-8 max-w-lg w-full mx-4 space-y-4">
        <div class="flex justify-between items-center border-b border-gray-800 pb-3">
            <h3 class="text-sm font-bold uppercase tracking-wider text-white" id="edit-item-modal-title">Edit Item</h3>
            <button onclick="closeModal('modal-edit-item')" class="text-gray-400 hover:text-white">&times;</button>
        </div>
        <form id="form-edit-item" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div id="edit-item-fields" class="space-y-4">
                <!-- Dynamically populated fields -->
            </div>
            <div class="flex justify-end space-x-3 pt-3 border-t border-gray-800">
                <button type="button" onclick="closeModal('modal-edit-item')" class="px-4 py-2 bg-gray-800 text-gray-300 text-xs font-bold uppercase tracking-wider">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-[#C9A84C] text-black text-xs font-serif font-bold uppercase tracking-wider">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Tab switching logic
    function switchTab(tabId) {
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('text-[#C9A84C]', 'border-[#C9A84C]', 'active');
            b.classList.add('text-gray-400', 'border-transparent');
        });
        
        document.getElementById(tabId).classList.remove('hidden');
        const activeBtn = document.getElementById('btn-' + tabId);
        activeBtn.classList.remove('text-gray-400', 'border-transparent');
        activeBtn.classList.add('text-[#C9A84C]', 'border-[#C9A84C]', 'active');
        localStorage.setItem('about_active_tab', tabId);
    }

    // Restore active tab
    window.addEventListener('DOMContentLoaded', () => {
        const activeTab = localStorage.getItem('about_active_tab') || 'tab-chairman';
        switchTab(activeTab);

        // Initialize CKEditors
        CKEDITOR.replace('editor-speech');
        CKEDITOR.replace('editor-md-speech');
        CKEDITOR.replace('editor-description');
        CKEDITOR.replace('editor-mission');
    });

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    // Modal opens for edit
    function openEditMissionModal(item) {
        const form = document.getElementById('form-edit-mission');
        form.action = `/admin/about-page/missions-visions/${item.id}`;
        
        document.getElementById('edit-mission-type').value = item.type;
        document.getElementById('edit-mission-title').value = item.title;
        document.getElementById('edit-mission-short-description').value = item.short_description || '';
        document.getElementById('edit-mission-icon').value = item.icon_or_image || '';
        
        // CKEditor content setting
        if (CKEDITOR.instances['edit-mission-content']) {
            CKEDITOR.instances['edit-mission-content'].destroy();
        }
        document.getElementById('edit-mission-content').value = item.content || '';
        CKEDITOR.replace('edit-mission-content');

        document.getElementById('edit-mission-active').checked = !!item.is_active;
        
        document.getElementById('modal-edit-mission').classList.remove('hidden');
    }

    // Core Value edit modal
    function openEditValueModal(item) {
        const form = document.getElementById('form-edit-item');
        form.action = `/admin/about-page/core-values/${item.id}`;
        
        document.getElementById('edit-item-modal-title').textContent = 'Edit Core Value';
        
        const container = document.getElementById('edit-item-fields');
        container.innerHTML = `
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Title *</label>
                <input type="text" name="title" value="${item.title}" required class="w-full px-4 py-2 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Icon</label>
                <input type="text" name="icon" value="${item.icon || ''}" class="w-full px-4 py-2 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Description *</label>
                <textarea name="description" required rows="3" class="w-full px-4 py-2 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none">${item.description}</textarea>
            </div>
            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="edit-val-active" value="1" ${item.is_active ? 'checked' : ''} class="h-4 w-4 bg-gray-800 border-gray-700 text-[#C9A84C]">
                <label for="edit-val-active" class="ml-2 text-xs font-bold text-gray-300 uppercase tracking-wider">Active</label>
            </div>
        `;
        
        document.getElementById('modal-edit-item').classList.remove('hidden');
    }

    // Choose Us edit modal
    function openEditChooseModal(item) {
        const form = document.getElementById('form-edit-item');
        form.action = `/admin/about-page/why-choose-us/${item.id}`;
        
        document.getElementById('edit-item-modal-title').textContent = 'Edit Why Choose Us Point';
        
        const container = document.getElementById('edit-item-fields');
        container.innerHTML = `
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Title *</label>
                <input type="text" name="title" value="${item.title}" required class="w-full px-4 py-2 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Icon</label>
                <input type="text" name="icon" value="${item.icon || ''}" class="w-full px-4 py-2 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Description *</label>
                <textarea name="description" required rows="3" class="w-full px-4 py-2 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none">${item.description}</textarea>
            </div>
            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="edit-choose-active" value="1" ${item.is_active ? 'checked' : ''} class="h-4 w-4 bg-gray-800 border-gray-700 text-[#C9A84C]">
                <label for="edit-choose-active" class="ml-2 text-xs font-bold text-gray-300 uppercase tracking-wider">Active</label>
            </div>
        `;
        
        document.getElementById('modal-edit-item').classList.remove('hidden');
    }

    // Stat edit modal
    function openEditStatModal(item) {
        const form = document.getElementById('form-edit-item');
        form.action = `/admin/about-page/statistics/${item.id}`;
        
        document.getElementById('edit-item-modal-title').textContent = 'Edit Counter Statistic';
        
        const container = document.getElementById('edit-item-fields');
        container.innerHTML = `
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Number *</label>
                <input type="text" name="counter_number" value="${item.counter_number}" required class="w-full px-4 py-2 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Suffix</label>
                <input type="text" name="suffix" value="${item.suffix || ''}" class="w-full px-4 py-2 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Label *</label>
                <input type="text" name="label" value="${item.label}" required class="w-full px-4 py-2 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Icon</label>
                <input type="text" name="icon" value="${item.icon || ''}" class="w-full px-4 py-2 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none">
            </div>
            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="edit-stat-active" value="1" ${item.is_active ? 'checked' : ''} class="h-4 w-4 bg-gray-800 border-gray-700 text-[#C9A84C]">
                <label for="edit-stat-active" class="ml-2 text-xs font-bold text-gray-300 uppercase tracking-wider">Active</label>
            </div>
        `;
        
        document.getElementById('modal-edit-item').classList.remove('hidden');
    }

    // Timeline edit modal
    function openEditTimelineModal(item) {
        const form = document.getElementById('form-edit-item');
        form.action = `/admin/about-page/timelines/${item.id}`;
        
        document.getElementById('edit-item-modal-title').textContent = 'Edit Timeline Milestone';
        
        const container = document.getElementById('edit-item-fields');
        container.innerHTML = `
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Year / Date *</label>
                <input type="text" name="year_or_date" value="${item.year_or_date}" required class="w-full px-4 py-2 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Milestone Title *</label>
                <input type="text" name="title" value="${item.title}" required class="w-full px-4 py-2 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Short Description *</label>
                <textarea name="description" required rows="2" class="w-full px-4 py-2 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none">${item.description}</textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Replace Image</label>
                ${item.image ? `<img src="/${item.image}" class="h-10 mb-2">` : ''}
                <input type="file" name="image" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:bg-gray-800 file:text-[#C9A84C]">
            </div>
            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="edit-time-active" value="1" ${item.is_active ? 'checked' : ''} class="h-4 w-4 bg-gray-800 border-gray-700 text-[#C9A84C]">
                <label for="edit-time-active" class="ml-2 text-xs font-bold text-gray-300 uppercase tracking-wider">Active</label>
            </div>
        `;
        
        document.getElementById('modal-edit-item').classList.remove('hidden');
    }

    // Team Member edit modal
    function openEditTeamModal(item) {
        const form = document.getElementById('form-edit-item');
        form.action = `/admin/about-page/team-members/${item.id}`;
        
        document.getElementById('edit-item-modal-title').textContent = 'Edit Team Member';
        
        const container = document.getElementById('edit-item-fields');
        container.innerHTML = `
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Name *</label>
                    <input type="text" name="name" value="${item.name}" required class="w-full px-4 py-2 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Designation *</label>
                    <input type="text" name="designation" value="${item.designation}" required class="w-full px-4 py-2 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Facebook URL</label>
                    <input type="url" name="facebook_url" value="${item.facebook_url || ''}" class="w-full px-4 py-2 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">LinkedIn URL</label>
                    <input type="url" name="linkedin_url" value="${item.linkedin_url || ''}" class="w-full px-4 py-2 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Email</label>
                <input type="email" name="email" value="${item.email || ''}" class="w-full px-4 py-2 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Short Biography</label>
                <textarea name="biography" rows="2" class="w-full px-4 py-2 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none">${item.biography || ''}</textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Replace Photo</label>
                ${item.photo ? `<img src="/${item.photo}" class="h-10 mb-2 rounded-full">` : ''}
                <input type="file" name="photo" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:bg-gray-800 file:text-[#C9A84C]">
            </div>
            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="edit-team-active" value="1" ${item.is_active ? 'checked' : ''} class="h-4 w-4 bg-gray-800 border-gray-700 text-[#C9A84C]">
                <label for="edit-team-active" class="ml-2 text-xs font-bold text-gray-300 uppercase tracking-wider">Active</label>
            </div>
        `;
        
        document.getElementById('modal-edit-item').classList.remove('hidden');
    }
</script>
@endsection
