@extends('layouts.admin')

@section('title', 'Add Offer')
@section('page_title', 'Add Offer / Package')

@section('admin_content')
<form action="{{ url('/admin/offers') }}" method="POST" enctype="multipart/form-data" class="max-w-3xl bg-[#111827] border border-gray-800 p-8 space-y-6">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="md:col-span-2">
            <label class="block text-[10px] font-mono uppercase tracking-widest text-gray-500 mb-1.5">Offer Title *</label>
            <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. REGULAR PACKAGE" class="w-full bg-[#0c0f15] text-white text-sm border border-gray-700 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
        </div>
        <div>
            <label class="block text-[10px] font-mono uppercase tracking-widest text-gray-500 mb-1.5">Subtitle</label>
            <input type="text" name="subtitle" value="{{ old('subtitle') }}" placeholder="e.g. Essential Grooming Bundle" class="w-full bg-[#0c0f15] text-white text-sm border border-gray-700 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
        </div>
        <div>
            <label class="block text-[10px] font-mono uppercase tracking-widest text-gray-500 mb-1.5">Badge</label>
            <input type="text" name="badge" value="{{ old('badge') }}" placeholder="e.g. HOT DEAL / POPULAR / LIMITED TIME" class="w-full bg-[#0c0f15] text-white text-sm border border-gray-700 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
        </div>
        <div class="md:col-span-2">
            <label class="block text-[10px] font-mono uppercase tracking-widest text-gray-500 mb-1.5">Description</label>
            <textarea name="description" rows="3" placeholder="What's included in this offer..." class="w-full bg-[#0c0f15] text-white text-sm border border-gray-700 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C] resize-none">{{ old('description') }}</textarea>
        </div>
        <div class="md:col-span-2">
            <label class="block text-[10px] font-mono uppercase tracking-widest text-gray-500 mb-1.5">Included Services (one per line)</label>
            <textarea name="services" rows="4" placeholder="Enter services, one per line..." class="w-full bg-[#0c0f15] text-white text-sm border border-gray-700 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C] resize-y">{{ old('services') }}</textarea>
        </div>
        <div>
            <label class="block text-[10px] font-mono uppercase tracking-widest text-gray-500 mb-1.5">Icon (Lucide)</label>
            <select name="icon" class="w-full bg-[#0c0f15] text-white text-sm border border-gray-700 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
                @foreach(['Tag','Crown','Sparkles','Award','Briefcase','Flower','Gift','Star','Gem','Scissors','Calendar','Clock','Percent','BadgePercent'] as $icon)
                    <option value="{{ $icon }}" @selected(old('icon', 'Tag') === $icon)>{{ $icon }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-mono uppercase tracking-widest text-gray-500 mb-1.5">Branch</label>
            <select name="branch" class="w-full bg-[#0c0f15] text-white text-sm border border-gray-700 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
                <option value="all" @selected(old('branch') === 'all')>All Branches</option>
                <option value="gulshan" @selected(old('branch') === 'gulshan')>Gulshan</option>
                <option value="bashundhara" @selected(old('branch') === 'bashundhara')>Bashundhara</option>
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-mono uppercase tracking-widest text-gray-500 mb-1.5">Original Price (৳)</label>
            <input type="number" step="0.01" min="0" name="original_price" value="{{ old('original_price') }}" placeholder="e.g. 6800" class="w-full bg-[#0c0f15] text-white text-sm border border-gray-700 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
        </div>
        <div>
            <label class="block text-[10px] font-mono uppercase tracking-widest text-gray-500 mb-1.5">Discounted Price (৳)</label>
            <input type="number" step="0.01" min="0" name="discounted_price" value="{{ old('discounted_price') }}" placeholder="e.g. 5800" class="w-full bg-[#0c0f15] text-white text-sm border border-gray-700 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
        </div>
        <div>
            <label class="block text-[10px] font-mono uppercase tracking-widest text-gray-500 mb-1.5">Discount Percent (%)</label>
            <input type="number" min="0" max="100" name="discount_percent" value="{{ old('discount_percent') }}" placeholder="e.g. 15" class="w-full bg-[#0c0f15] text-white text-sm border border-gray-700 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
        </div>
        <div>
            <label class="block text-[10px] font-mono uppercase tracking-widest text-gray-500 mb-1.5">Valid Until (display text)</label>
            <input type="text" name="valid_until" value="{{ old('valid_until') }}" placeholder="e.g. Aug 31, 2026 / Limited Time" class="w-full bg-[#0c0f15] text-white text-sm border border-gray-700 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
        </div>
        <div>
            <label class="block text-[10px] font-mono uppercase tracking-widest text-gray-500 mb-1.5">Sort Order</label>
            <input type="number" min="0" name="sort_order" value="{{ old('sort_order', 0) }}" class="w-full bg-[#0c0f15] text-white text-sm border border-gray-700 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
        </div>
        <div class="md:col-span-2 space-y-4">
            <div>
                <label class="block text-[10px] font-mono uppercase tracking-widest text-gray-500 mb-1.5">Banner Image (Upload)</label>
                <input type="file" name="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml,image/webp" class="w-full bg-[#0c0f15] text-white text-sm border border-gray-700 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C] file:mr-3 file:py-1.5 file:px-3 file:border-0 file:text-xs file:font-bold file:uppercase file:tracking-wider file:text-black file:bg-[#C9A84C] file:cursor-pointer hover:file:bg-[#b8973f]">
            </div>
            <div>
                <label class="block text-[10px] font-mono uppercase tracking-widest text-gray-500 mb-1.5">Or Image URL</label>
                <input type="text" name="image_url" value="{{ old('image_url') }}" placeholder="e.g. /assets/images/offer.png or https://..." class="w-full bg-[#0c0f15] text-white text-sm border border-gray-700 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
            </div>
            <div id="image-preview" class="hidden w-40 h-24 border border-gray-700 overflow-hidden bg-black">
                <img id="image-preview-img" src="" alt="Preview" class="h-full w-full object-cover">
            </div>
        </div>
        <div class="md:col-span-2">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked class="h-4 w-4 border-gray-600 bg-[#0c0f15] text-[#C9A84C] focus:ring-[#C9A84C] focus:ring-offset-0">
                <span class="text-xs font-bold text-gray-300 uppercase tracking-widest">Active (visible on the public Offers page)</span>
            </label>
        </div>
    </div>

    <div class="flex gap-4 pt-2">
        <button type="submit" class="px-6 py-2.5 bg-[#C9A84C] hover:bg-[#b8973f] text-black text-xs font-bold uppercase tracking-wider transition-colors">Save Offer</button>
        <a href="{{ url('/admin/offers') }}" class="px-6 py-2.5 border border-gray-700 text-gray-300 hover:border-gray-500 text-xs font-bold uppercase tracking-wider transition-colors">Cancel</a>
    </div>
</form>

<script>
document.querySelector('input[name="image"]')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('image-preview-img').src = ev.target.result;
            document.getElementById('image-preview').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
