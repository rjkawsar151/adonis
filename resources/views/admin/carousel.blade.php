@extends('layouts.admin')

@section('title', 'Carousel Images')
@section('page_title', 'Homepage Carousel Images')

@section('admin_content')
@if(session('success'))
    <div class="mb-6 p-4 bg-green-900/30 text-green-400 text-sm font-semibold border border-green-800/50">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-6 p-4 bg-red-900/30 text-red-400 text-sm border border-red-800/50">
        <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<!-- Upload Form -->
<div class="bg-[#111827] border border-gray-800 p-6 sm:p-8 space-y-6 mb-8">
    <h3 class="font-extrabold text-lg text-white border-b border-gray-800 pb-4">Add Carousel Images</h3>

    <form action="{{ url('/admin/carousel') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Images * (1920x700 recommended, select multiple)</label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-gray-700 hover:border-[#C9A84C] transition-colors cursor-pointer bg-[#1a1f2e]" onclick="document.getElementById('image-input').click()">
                <div class="space-y-2 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-600" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <div class="text-sm text-gray-500">
                        <span class="font-semibold text-[#C9A84C]">Click to upload</span>
                        <span> or drag and drop</span>
                    </div>
                    <p class="text-xs text-gray-600">JPEG, PNG, WebP, GIF up to 5MB each</p>
                    <p id="file-count" class="text-xs font-bold text-[#C9A84C] hidden"></p>
                </div>
            </div>
            <input id="image-input" type="file" name="images[]" accept="image/*" multiple required class="hidden" onchange="document.getElementById('file-count').textContent=this.files.length+' file(s) selected';document.getElementById('file-count').classList.remove('hidden')">
        </div>
        <div class="mt-6">
            <button type="submit" class="inline-flex items-center justify-center px-6 py-3 text-sm font-bold tracking-widest uppercase text-black bg-[#C9A84C] hover:bg-[#b8973f] shadow-md transition-all">
                Upload Images
            </button>
        </div>
    </form>
</div>

<!-- Image List -->
<div class="bg-[#111827] border border-gray-800 p-6 sm:p-8 space-y-6">
    <h3 class="font-extrabold text-lg text-white border-b border-gray-800 pb-4">
        Current Carousel Images ({{ count($images) }})
    </h3>

    @if(count($images) > 0)
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4" id="carousel-grid">
        @foreach($images as $img)
        <div class="bg-[#1a1f2e] border border-gray-800 overflow-hidden relative group">
            <div class="aspect-[16/10] overflow-hidden bg-[#0c0f15]">
                <img src="{{ asset($img->image_path) }}" alt="{{ $img->alt_text ?? 'Carousel' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            </div>
            <div class="p-2.5">
                <p class="text-[10px] text-gray-600">#{{ $img->sort_order }}</p>
            </div>
            <form action="{{ url('/admin/carousel/' . $img->id) }}" method="POST" onsubmit="return confirm('Remove this image?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="absolute top-1.5 right-1.5 w-7 h-7 bg-red-600 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-md hover:bg-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </form>
        </div>
        @endforeach
    </div>

    <p class="text-xs text-gray-500 pt-4 border-t border-gray-800">
        Images display in a carousel on the homepage, auto-scrolling every 2.5 seconds.
    </p>
    @else
    <div class="text-center py-12">
        <p class="text-gray-600">No carousel images yet. Upload some above.</p>
    </div>
    @endif
</div>
@endsection
