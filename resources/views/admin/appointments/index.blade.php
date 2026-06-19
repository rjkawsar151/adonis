@extends('layouts.admin')

@section('title', 'Manage Appointments')
@section('page_title', 'Appointments Management')

@section('admin_content')
<!-- Filters -->
<div class="bg-[#111827] border border-gray-800 p-6 mb-6">
    <form method="GET" action="{{ url('/admin/appointments') }}" class="flex flex-col sm:flex-row gap-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, phone or email..." class="flex-grow px-4 py-2.5 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors placeholder-gray-600">
        <select name="status" class="px-4 py-2.5 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
            <option value="all" {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>All Statuses</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <button type="submit" class="px-5 py-2.5 bg-[#C9A84C] text-black text-sm font-bold hover:bg-[#b8973f] transition-all">Filter</button>
        <a href="{{ url('/admin/appointments') }}" class="px-5 py-2.5 border border-gray-700 text-gray-400 text-sm font-bold hover:bg-gray-800 transition-all text-center">Clear</a>
    </form>
</div>

<div class="bg-[#111827] border border-gray-800 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#0c0f15] border-b border-gray-800 text-xs font-bold uppercase tracking-wider text-gray-500">
                    <th class="px-6 py-4">Client</th>
                    <th class="px-6 py-4">Phone / Email</th>
                    <th class="px-6 py-4">Preferred Slot</th>
                    <th class="px-6 py-4">Service</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Submitted</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-300">
                @if(count($appointments) > 0)
                    @foreach($appointments as $appt)
                        <tr class="border-b border-gray-800 hover:bg-gray-800/30 transition-colors">
                            <td class="px-6 py-4 font-bold text-white">{{ $appt->name }}</td>
                            <td class="px-6 py-4">
                                <div class="font-semibold">{{ $appt->phone }}</div>
                                <div class="text-xs text-gray-500">{{ $appt->email ?? '—' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold">{{ $appt->preferred_date }}</div>
                                <div class="text-xs text-gray-500">{{ $appt->preferred_time }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-semibold bg-[#C9A84C]/10 text-[#C9A84C] px-2 py-1">
                                    {{ $appt->service ? $appt->service->title : "General" }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-orange-900/30 text-orange-400 border border-orange-800/50',
                                        'contacted' => 'bg-blue-900/30 text-blue-400 border border-blue-800/50',
                                        'confirmed' => 'bg-green-900/30 text-green-400 border border-green-800/50',
                                        'completed' => 'bg-gray-800 text-gray-400 border border-gray-700',
                                        'cancelled' => 'bg-red-900/30 text-red-400 border border-red-800/50',
                                    ];
                                    $col = $statusColors[$appt->status] ?? 'bg-gray-800 text-gray-400';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-bold uppercase tracking-wider border {{ $col }}">
                                    {{ $appt->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500">{{ $appt->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ url('/admin/appointments/' . $appt->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-gray-800 text-gray-400 hover:bg-[#C9A84C] hover:text-black transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-600">No appointments found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-800 text-gray-400 text-sm">
        {{ $appointments->withQueryString()->links() }}
    </div>
</div>
@endsection
