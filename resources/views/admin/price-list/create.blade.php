@extends('layouts.admin')

@section('title', 'Add Price List Item')
@section('page_title', 'Add Service Item')

@section('admin_content')
<div class="mb-6">
    <a href="{{ url('/admin/price-list') }}" class="inline-flex items-center text-sm text-[#C9A84C] hover:text-white font-semibold">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        Back to Price List
    </a>
</div>

@if($errors->any())
    <div class="mb-6 p-4 bg-red-900/30 text-red-400 text-sm border border-red-800/50">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ url('/admin/price-list') }}" method="POST" class="max-w-2xl">
    @csrf

    <div class="bg-[#111827] border border-gray-800 p-8 space-y-6">
        <h3 class="font-extrabold text-lg text-white border-b border-gray-800 pb-4">Item Details</h3>

        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Category *</label>
            <div class="flex space-x-2">
                <select name="category" class="flex-grow px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ $presetCategory === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                <input type="text" name="new_category" placeholder="Or type new..." class="flex-grow px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors" value="{{ old('new_category') }}">
            </div>
            <p class="text-[10px] text-gray-600 mt-1">Select existing or type a new category name.</p>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Service Name *</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors" placeholder="e.g. Hair Cut with Shampoo">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Description</label>
            <textarea name="description" rows="2" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors" placeholder="Brief description...">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Price *</label>
                <input type="text" name="price" value="{{ old('price') }}" required class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors" placeholder="e.g. 700/- or 1,000/- & Above">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Branch</label>
                <select name="branch_id" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
                    <option value="all" {{ old('branch_id') === 'all' ? 'selected' : '' }}>All Branches</option>
                    <option value="gulshan" {{ old('branch_id') === 'gulshan' ? 'selected' : '' }}>Gulshan Only</option>
                    <option value="bashundhara" {{ old('branch_id') === 'bashundhara' ? 'selected' : '' }}>Bashundhara Only</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Sort Order *</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" required class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
            </div>
        </div>
    </div>

    <div class="flex space-x-4 mt-6">
        <button type="submit" class="inline-flex items-center justify-center px-8 py-3.5 text-sm font-bold tracking-widest uppercase text-black bg-[#C9A84C] hover:bg-[#b8973f] shadow-md hover:shadow-lg transition-all duration-200">
            Create Item
        </button>
        <a href="{{ url('/admin/price-list') }}" class="inline-flex items-center justify-center px-8 py-3.5 border border-gray-700 text-sm font-bold text-gray-400 hover:bg-gray-800 transition-colors">
            Cancel
        </a>
    </div>
</form>

<script>
    const catSelect = document.querySelector('[name="category"]');
    const catNew = document.querySelector('[name="new_category"]');
    catNew?.addEventListener('input', function() {
        if (this.value.trim()) catSelect.value = '';
    });
    catSelect?.addEventListener('change', function() {
        if (this.value) catNew.value = '';
    });
</script>
@endsection
