@extends('layouts.admin')

@section('title', 'Manage Services')
@section('page_title', 'Services Management')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">Services are organized by branch. Each branch has its own service catalog visible on the website.</p>
    <form action="{{ url('/admin/services/sync-from-price-list') }}" method="POST" onsubmit="return confirm('This will replace all existing services with categories from the Price List. Continue?')">
        @csrf
        <button type="submit" class="inline-flex items-center px-4 py-2 border border-[#C9A84C]/50 text-xs font-bold uppercase tracking-wider text-[#C9A84C] hover:bg-[#C9A84C] hover:text-black transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Sync from Price List
        </button>
    </form>
</div>

<!-- Gulshan Section -->
<div class="bg-[#111827] border border-gray-800 overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-gray-800 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-2 h-2 rounded-full bg-[#C9A84C]"></div>
            <h3 class="font-bold text-lg text-white">Gulshan Premium Lounge</h3>
            <span class="text-xs text-gray-500 font-semibold">({{ count($gulshan) }} services)</span>
        </div>
        <a href="{{ url('/admin/services/create?branch=gulshan') }}" class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold uppercase tracking-wider text-black bg-[#C9A84C] hover:bg-[#b8973f] shadow-md hover:shadow-lg transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Service
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#0c0f15] border-b border-gray-800 text-xs font-bold uppercase tracking-wider text-gray-500">
                    <th class="px-6 py-4">#</th>
                    <th class="px-6 py-4">Service Title</th>
                    <th class="px-6 py-4">Description</th>
                    <th class="px-6 py-4">Price</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm font-medium text-gray-300">
                @forelse($gulshan as $service)
                    <tr class="border-b border-gray-800 hover:bg-gray-800/30 transition-colors">
                        <td class="px-6 py-4 text-gray-500">{{ $service->sort_order }}</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-white">{{ $service->title }}</div>
                            <div class="text-xs text-gray-500 font-mono">{{ $service->slug }}</div>
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            <span class="text-xs text-gray-400 line-clamp-2">{{ $service->short_description ?? $service->description ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($service->price)
                                <span class="font-mono text-[#C9A84C]">৳{{ number_format($service->price, 0) }}</span>
                            @elseif($service->priceBDT)
                                <span class="font-mono text-[#C9A84C]">৳{{ number_format($service->priceBDT, 0) }}</span>
                            @else
                                <span class="text-gray-600 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($service->status === 'active')
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-bold bg-green-900/30 text-green-400 border border-green-800/50">Active</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-bold bg-red-900/30 text-red-400 border border-red-800/50">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ url('/admin/services/' . $service->id . '/edit') }}" class="inline-flex items-center justify-center w-8 h-8 bg-gray-800 text-gray-400 hover:bg-[#C9A84C] hover:text-black transition-colors" title="Edit Service">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ url('/admin/services/' . $service->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this service?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-gray-800 text-gray-400 hover:bg-red-600 hover:text-white transition-colors" title="Delete Service">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-600">
                            No services in Gulshan branch yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Bashundhara Section -->
<div class="bg-[#111827] border border-gray-800 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-800 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-2 h-2 rounded-full bg-[#C9A84C]"></div>
            <h3 class="font-bold text-lg text-white">Bashundhara Premium Lounge</h3>
            <span class="text-xs text-gray-500 font-semibold">({{ count($bashundhara) }} services)</span>
        </div>
        <a href="{{ url('/admin/services/create?branch=bashundhara') }}" class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold uppercase tracking-wider text-black bg-[#C9A84C] hover:bg-[#b8973f] shadow-md hover:shadow-lg transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Service
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#0c0f15] border-b border-gray-800 text-xs font-bold uppercase tracking-wider text-gray-500">
                    <th class="px-6 py-4">#</th>
                    <th class="px-6 py-4">Service Title</th>
                    <th class="px-6 py-4">Description</th>
                    <th class="px-6 py-4">Price</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm font-medium text-gray-300">
                @forelse($bashundhara as $service)
                    <tr class="border-b border-gray-800 hover:bg-gray-800/30 transition-colors">
                        <td class="px-6 py-4 text-gray-500">{{ $service->sort_order }}</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-white">{{ $service->title }}</div>
                            <div class="text-xs text-gray-500 font-mono">{{ $service->slug }}</div>
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            <span class="text-xs text-gray-400 line-clamp-2">{{ $service->short_description ?? $service->description ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($service->price)
                                <span class="font-mono text-[#C9A84C]">৳{{ number_format($service->price, 0) }}</span>
                            @elseif($service->priceBDT)
                                <span class="font-mono text-[#C9A84C]">৳{{ number_format($service->priceBDT, 0) }}</span>
                            @else
                                <span class="text-gray-600 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($service->status === 'active')
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-bold bg-green-900/30 text-green-400 border border-green-800/50">Active</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-bold bg-red-900/30 text-red-400 border border-red-800/50">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ url('/admin/services/' . $service->id . '/edit') }}" class="inline-flex items-center justify-center w-8 h-8 bg-gray-800 text-gray-400 hover:bg-[#C9A84C] hover:text-black transition-colors" title="Edit Service">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ url('/admin/services/' . $service->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this service?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-gray-800 text-gray-400 hover:bg-red-600 hover:text-white transition-colors" title="Delete Service">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-600">
                            No services in Bashundhara branch yet. Click "Add Service" to add one.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
