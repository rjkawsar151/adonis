@extends('layouts.admin')

@section('title', 'Add FAQ')
@section('page_title', 'Add New FAQ')

@section('admin_content')
<div class="mb-6">
    <a href="{{ url('/admin/faqs') }}" class="inline-flex items-center text-sm text-[#006F5C] hover:text-[#CC205C] font-semibold">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        Back to FAQs
    </a>
</div>

@if($errors->any())
    <div class="mb-6 p-4 bg-[#FDF2F5] text-[#CC205C] rounded-2xl text-sm border border-[#CC205C]/20">
        <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form action="{{ url('/admin/faqs') }}" method="POST" class="space-y-6">
    @csrf
    <div class="bg-white border border-[#EEF7F4] rounded-[24px] p-8 shadow-sm space-y-6">
        <div>
            <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Question *</label>
            <input type="text" name="question" value="{{ old('question') }}" required class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
        </div>
        <div>
            <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Answer *</label>
            <textarea name="answer" rows="4" required class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors">{{ old('answer') }}</textarea>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Assign to Service</label>
                <select name="service_id" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
                    <option value="">General (All Pages)</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>{{ $service->title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Sort Order *</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" required class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Status *</label>
                <select name="status" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
                    <option value="active" {{ old('status') !== 'inactive' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
    </div>
    <div class="flex space-x-4">
        <button type="submit" class="inline-flex items-center justify-center px-8 py-3.5 text-sm font-bold rounded-full text-white bg-[#006F5C] hover:bg-[#005547] shadow-md transition-all">Save FAQ</button>
        <a href="{{ url('/admin/faqs') }}" class="inline-flex items-center justify-center px-8 py-3.5 border-2 border-[#EEF7F4] text-sm font-bold rounded-full text-[#6B7280] hover:bg-[#EEF7F4] transition-colors">Cancel</a>
    </div>
</form>
@endsection
