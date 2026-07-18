@extends('layouts.admin')

@section('title', 'Manage FAQs')
@section('page_title', 'FAQs Management')

@section('admin_content')
<div class="mb-6 flex justify-between items-center">
    <p class="text-sm text-gray-500">Manage frequently asked questions assigned to services or general pages.</p>
    <a href="{{ url('/admin/faqs/create') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-black bg-[#C9A84C] hover:bg-[#b8973f] shadow-md transition-all duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
        Add FAQ
    </a>
</div>

<div class="bg-[#111827] border border-gray-800 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#0c0f15] border-b border-gray-800 text-xs font-bold uppercase tracking-wider text-gray-500">
                    <th class="px-6 py-4">#</th>
                    <th class="px-6 py-4">Question</th>
                    <th class="px-6 py-4">Service</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-300">
                @forelse($faqs as $faq)
                    <tr class="border-b border-gray-800 hover:bg-gray-800/30 transition-colors">
                        <td class="px-6 py-4 text-xs text-gray-500">{{ $faq->sort_order }}</td>
                        <td class="px-6 py-4 font-semibold text-white max-w-md">{{ Str::limit($faq->question, 80) }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs bg-[#C9A84C]/10 text-[#C9A84C] px-2 py-1 font-semibold">
                                {{ $faq->service ? $faq->service->title : 'General' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($faq->status === 'active')
                                <span class="text-xs font-bold bg-green-900/30 text-green-400 px-2.5 py-1 border border-green-800/50">Active</span>
                            @else
                                <span class="text-xs font-bold bg-gray-800 text-gray-500 px-2.5 py-1">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ url('/admin/faqs/' . $faq->id . '/edit') }}" class="inline-flex items-center justify-center w-8 h-8 bg-gray-800 text-gray-400 hover:bg-[#C9A84C] hover:text-black transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </a>
                            <form action="{{ url('/admin/faqs/' . $faq->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this FAQ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-gray-800 text-gray-400 hover:bg-red-600 hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-gray-600">No FAQs yet. Click "Add FAQ" to create one.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
