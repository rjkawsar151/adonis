@extends('layouts.admin')

@section('title', 'Edit FAQ')
@section('page_title', 'Edit FAQ')

@section('admin_content')
<div class="mb-6">
    <a href="{{ url('/admin/faqs') }}" class="inline-flex items-center text-sm text-[#C9A84C] hover:text-white font-semibold">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        Back to FAQs
    </a>
</div>

@if($errors->any())
    <div class="mb-6 p-4 bg-red-900/30 text-red-400 text-sm border border-red-800/50">
        <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form action="{{ url('/admin/faqs/' . $faq->id) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')
    <div class="bg-[#111827] border border-gray-800 p-8 space-y-6">
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Question *</label>
            <input type="text" name="question" value="{{ old('question', $faq->question) }}" required class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Answer *</label>
            <textarea name="answer" rows="4" required class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">{{ old('answer', $faq->answer) }}</textarea>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Assign to Service</label>
                <select name="service_id" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
                    <option value="">General (All Pages)</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ old('service_id', $faq->service_id) == $service->id ? 'selected' : '' }}>{{ $service->title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Sort Order *</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $faq->sort_order) }}" required class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Status *</label>
                <select name="status" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
                    <option value="active" {{ old('status', $faq->status) === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $faq->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
    </div>
    <div class="flex space-x-4">
        <button type="submit" class="inline-flex items-center justify-center px-8 py-3.5 text-sm font-bold tracking-widest uppercase text-black bg-[#C9A84C] hover:bg-[#b8973f] shadow-md transition-all">Save Changes</button>
        <a href="{{ url('/admin/faqs') }}" class="inline-flex items-center justify-center px-8 py-3.5 border border-gray-700 text-sm font-bold text-gray-400 hover:bg-gray-800 transition-colors">Cancel</a>
    </div>
</form>
@endsection
