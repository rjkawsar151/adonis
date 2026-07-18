@extends('layouts.admin')

@section('title', 'Edit Service')
@section('page_title', 'Edit: ' . $service->title)

@section('admin_content')
<div class="mb-6">
    <a href="{{ url('/admin/services') }}" class="inline-flex items-center text-sm text-[#C9A84C] hover:text-white font-semibold">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        Back to Services
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

<form action="{{ url('/admin/services/' . $service->id) }}" method="POST" class="space-y-8">
    @csrf
    @method('PUT')

    <div class="bg-[#111827] border border-gray-800 p-8 space-y-6">
        <h3 class="font-extrabold text-lg text-white border-b border-gray-800 pb-4">Service Details</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Service Title *</label>
                <input type="text" name="title" value="{{ old('title', $service->title) }}" required class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">URL Slug *</label>
                <input type="text" name="slug" value="{{ old('slug', $service->slug) }}" required class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors font-mono">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Description</label>
            <textarea name="short_description" rows="3" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">{{ old('short_description', $service->short_description ?? $service->description) }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Branch *</label>
                <select name="branch_id" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
                    <option value="gulshan" {{ old('branch_id', $service->branch_id) === 'gulshan' ? 'selected' : '' }}>Gulshan Premium Lounge</option>
                    <option value="bashundhara" {{ old('branch_id', $service->branch_id) === 'bashundhara' ? 'selected' : '' }}>Bashundhara Premium Lounge</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Price (৳)</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $service->price ?? $service->priceBDT) }}" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Status *</label>
                <select name="status" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
                    <option value="active" {{ old('status', $service->status) === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $service->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Sort Order *</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $service->sort_order) }}" required class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
            </div>
        </div>
    </div>

    <div class="flex space-x-4">
        <button type="submit" class="inline-flex items-center justify-center px-8 py-3.5 text-sm font-bold tracking-widest uppercase text-black bg-[#C9A84C] hover:bg-[#b8973f] shadow-md hover:shadow-lg transition-all duration-200">
            Save Changes
        </button>
        <a href="{{ url('/admin/services') }}" class="inline-flex items-center justify-center px-8 py-3.5 border border-gray-700 text-sm font-bold text-gray-400 hover:bg-gray-800 transition-colors">
            Cancel
        </a>
    </div>
</form>
@endsection
