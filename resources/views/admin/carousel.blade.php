@extends('layouts.admin')

@section('title', 'Carousel Images')
@section('page_title', 'Homepage Carousel Images')

@section('admin_content')
@if(session('success'))
    <div class="mb-6 p-4 bg-[#EEF7F4] text-[#006F5C] rounded-2xl text-sm font-semibold border border-[#006F5C]/20">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-6 p-4 bg-[#FDF2F5] text-[#CC205C] rounded-2xl text-sm border border-[#CC205C]/20">
        <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<!-- Upload Form -->
<div class="bg-white border border-[#EEF7F4] rounded-[24px] p-6 sm:p-8 shadow-sm space-y-6 mb-8">
    <h3 class="font-extrabold text-lg text-[#111827] border-b border-[#EEF7F4] pb-4">Add Carousel Images</h3>

    <form action="{{ url('/admin/carousel') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Images * (1920x700 recommended, select multiple)</label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-[#D1D5DB] rounded-2xl hover:border-[#006F5C] transition-colors cursor-pointer bg-[#F9FAFB]" onclick="document.getElementById('image-input').click()">
                <div class="space-y-2 text-center">
                    <svg class="mx-auto h-12 w-12 text-[#9CA3AF]" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <div class="text-sm text-[#6B7280]">
                        <span class="font-semibold text-[#006F5C]">Click to upload</span>
                        <span> or drag and drop</span>
                    </div>
                    <p class="text-xs text-[#9CA3AF]">JPEG, PNG, WebP, GIF up to 5MB each</p>
                    <p id="file-count" class="text-xs font-bold text-[#006F5C] hidden"></p>
                </div>
            </div>
            <input id="image-input" type="file" name="images[]" accept="image/*" multiple required class="hidden" onchange="document.getElementById('file-count').textContent=this.files.length+' file(s) selected';document.getElementById('file-count').classList.remove('hidden')">
        </div>
        <div class="mt-6">
            <button type="submit" class="inline-flex items-center justify-center px-6 py-3 text-sm font-bold rounded-full text-white bg-[#006F5C] hover:bg-[#005547] shadow-md transition-all">
                Upload Images
            </button>
        </div>
    </form>
</div>

<!-- Image List -->
<div class="bg-white border border-[#EEF7F4] rounded-[24px] p-6 sm:p-8 shadow-sm space-y-6">
    <h3 class="font-extrabold text-lg text-[#111827] border-b border-[#EEF7F4] pb-4">
        Current Carousel Images ({{ count($images) }})
    </h3>

    @if(count($images) > 0)
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4" id="carousel-grid">
        @foreach($images as $img)
        <div class="bg-[#F4FAF8] border border-[#EEF7F4] rounded-[16px] overflow-hidden shadow-sm relative group">
            <div class="aspect-[16/10] overflow-hidden bg-[#EEF7F4]">
                <img src="{{ asset($img->image_path) }}" alt="{{ $img->alt_text ?? 'Carousel' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            </div>
            <div class="p-2.5">
                <p class="text-[10px] text-[#9CA3AF]">#{{ $img->sort_order }}</p>
            </div>
            <form action="{{ url('/admin/carousel/' . $img->id) }}" method="POST" onsubmit="return confirm('Remove this image?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="absolute top-1.5 right-1.5 w-7 h-7 rounded-full bg-[#CC205C] text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-md hover:bg-[#A61A4B]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </form>
        </div>
        @endforeach
    </div>

    <p class="text-xs text-[#6B7280] pt-4 border-t border-[#EEF7F4]">
        Images display in a carousel on the homepage, auto-scrolling every 2.5 seconds.
    </p>
    @else
    <div class="text-center py-12">
        <p class="text-[#6B7280]">No carousel images yet. Upload some above.</p>
    </div>
    @endif
</div>
@endsection
