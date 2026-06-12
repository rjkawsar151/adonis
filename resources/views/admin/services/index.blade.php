@extends('layouts.admin')

@section('title', 'Manage Services')
@section('page_title', 'Services Management')

@section('admin_content')
<div class="mb-6 flex justify-between items-center">
    <p class="text-sm text-[#6B7280]">Manage services shown on the public sidebar, main list, and individual pages.</p>
    <a href="{{ url('/admin/services/create') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-xs font-bold uppercase tracking-wider rounded-full text-white bg-[#006F5C] hover:bg-[#005547] shadow-md hover:shadow-lg transition-all duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Add Service
    </a>
</div>

<div class="bg-white border border-[#EEF7F4] rounded-[24px] shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#F4FAF8] border-b border-[#EEF7F4] text-xs font-bold uppercase tracking-wider text-[#6B7280]">
                    <th class="px-6 py-4">Sort Order</th>
                    <th class="px-6 py-4">Service Title</th>
                    <th class="px-6 py-4">Slug</th>
                    <th class="px-6 py-4">Show in Sidebar</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm font-medium text-[#374151]">
                @if(count($services) > 0)
                    @foreach($services as $service)
                        <tr class="border-b border-[#F4FAF8] hover:bg-[#F4FAF8]/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-[#111827]">{{ $service->sort_order }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-[#111827]">{{ $service->title }}</div>
                                @if($service->is_featured)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black uppercase bg-pink-100 text-[#CC205C] mt-1">
                                        Featured
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs font-mono text-[#6B7280]">{{ $service->slug }}</td>
                            <td class="px-6 py-4">
                                @if($service->show_in_sidebar)
                                    <span class="text-green-600 inline-flex items-center text-xs font-bold">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        Yes
                                    </span>
                                @else
                                    <span class="text-gray-400 inline-flex items-center text-xs">
                                        No
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($service->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-[#EEF7F4] text-[#006F5C] border border-[#006F5C]/10">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-[#FDF2F5] text-[#CC205C] border border-[#CC205C]/10">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ url('/admin/services/' . $service->id . '/edit') }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#EEF7F4] text-[#006F5C] hover:bg-[#006F5C] hover:text-white transition-colors" title="Edit Service">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                <form action="{{ url('/admin/services/' . $service->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this service? All related bullets and steps will also be deleted.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-pink-50 text-[#CC205C] hover:bg-[#CC205C] hover:text-white transition-colors" title="Delete Service">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-[#6B7280]">
                            No services exist. Click "Add Service" to create one.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
