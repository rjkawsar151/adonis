@extends('layouts.admin')

@section('title', 'Edit Barber')
@section('page_title', 'Edit Stylist')

@section('admin_content')
<form action="{{ url('/admin/barbers/' . $barber->id) }}" method="POST" enctype="multipart/form-data" class="max-w-2xl bg-[#111827] border border-gray-800 p-8 space-y-6">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="block text-[10px] font-mono uppercase tracking-widest text-gray-500 mb-1.5">Full Name</label>
            <input type="text" name="name" value="{{ old('name', $barber->name) }}" required class="w-full bg-[#0c0f15] text-white text-sm border border-gray-700 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
        </div>
        <div>
            <label class="block text-[10px] font-mono uppercase tracking-widest text-gray-500 mb-1.5">Specialty</label>
            <input type="text" name="specialty" value="{{ old('specialty', $barber->specialty) }}" class="w-full bg-[#0c0f15] text-white text-sm border border-gray-700 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
        </div>
        <div>
            <label class="block text-[10px] font-mono uppercase tracking-widest text-gray-500 mb-1.5">Experience (Years)</label>
            <input type="number" name="experienceYears" value="{{ old('experienceYears', $barber->experienceYears) }}" min="0" class="w-full bg-[#0c0f15] text-white text-sm border border-gray-700 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
        </div>
        <div>
            <label class="block text-[10px] font-mono uppercase tracking-widest text-gray-500 mb-1.5">Rating (0–5)</label>
            <input type="number" step="0.1" name="rating" value="{{ old('rating', $barber->rating) }}" min="0" max="5" class="w-full bg-[#0c0f15] text-white text-sm border border-gray-700 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
        </div>
        <div class="md:col-span-2">
            <label class="block text-[10px] font-mono uppercase tracking-widest text-gray-500 mb-1.5">Portrait Image</label>
            <input type="file" name="portrait" accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml,image/webp" class="w-full bg-[#0c0f15] text-white text-sm border border-gray-700 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C] file:mr-3 file:py-1.5 file:px-3 file:border-0 file:text-xs file:font-bold file:uppercase file:tracking-wider file:text-black file:bg-[#C9A84C] file:cursor-pointer hover:file:bg-[#b8973f]">
            @if($barber->portraitUrl)
                <div class="mt-2 w-20 h-24 border border-gray-700 overflow-hidden bg-black" id="current-portrait">
                    <img src="{{ asset($barber->portraitUrl) }}" alt="Current" class="h-full w-full object-cover">
                </div>
            @endif
            <div id="portrait-preview" class="mt-2 hidden w-20 h-24 border border-gray-700 overflow-hidden bg-black">
                <img id="portrait-preview-img" src="" alt="Preview" class="h-full w-full object-cover">
            </div>
        </div>
        <div class="md:col-span-2">
            <label class="block text-[10px] font-mono uppercase tracking-widest text-gray-500 mb-1.5">Bio</label>
            <textarea name="bio" rows="4" class="w-full bg-[#0c0f15] text-white text-sm border border-gray-700 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C] resize-none">{{ old('bio', $barber->bio) }}</textarea>
        </div>
    </div>

    <div class="flex gap-4 pt-2">
        <button type="submit" class="px-6 py-2.5 bg-[#C9A84C] hover:bg-[#b8973f] text-black text-xs font-bold uppercase tracking-wider transition-colors">Update Stylist</button>
        <a href="{{ url('/admin/barbers') }}" class="px-6 py-2.5 border border-gray-700 text-gray-300 hover:border-gray-500 text-xs font-bold uppercase tracking-wider transition-colors">Cancel</a>
    </div>
</form>

<script>
document.querySelector('input[name="portrait"]')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('portrait-preview-img').src = ev.target.result;
            document.getElementById('portrait-preview').classList.remove('hidden');
            const current = document.getElementById('current-portrait');
            if (current) current.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
