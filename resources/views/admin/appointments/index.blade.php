@extends('layouts.admin')

@section('title', 'Manage Appointments')
@section('page_title', 'Appointments Management')

@section('admin_content')
<!-- Filters -->
<div class="bg-white border border-[#EEF7F4] rounded-[24px] p-6 mb-6 shadow-sm">
    <form method="GET" action="{{ url('/admin/appointments') }}" class="flex flex-col sm:flex-row gap-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, phone or email..." class="flex-grow px-4 py-2.5 bg-[#F4FAF8] border border-[#EEF7F4] rounded-full text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
        <select name="status" class="px-4 py-2.5 bg-[#F4FAF8] border border-[#EEF7F4] rounded-full text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
            <option value="all" {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>All Statuses</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <button type="submit" class="px-5 py-2.5 bg-[#006F5C] text-white text-sm font-bold rounded-full hover:bg-[#005547] transition-all shadow-sm">Filter</button>
        <a href="{{ url('/admin/appointments') }}" class="px-5 py-2.5 border border-[#EEF7F4] text-[#6B7280] text-sm font-bold rounded-full hover:bg-[#EEF7F4] transition-all text-center">Clear</a>
    </form>
</div>

<div class="bg-white border border-[#EEF7F4] rounded-[24px] shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#F4FAF8] border-b border-[#EEF7F4] text-xs font-bold uppercase tracking-wider text-[#6B7280]">
                    <th class="px-6 py-4">Patient</th>
                    <th class="px-6 py-4">Phone / Email</th>
                    <th class="px-6 py-4">Preferred Slot</th>
                    <th class="px-6 py-4">Service</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Submitted</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm text-[#374151]">
                @if(count($appointments) > 0)
                    @foreach($appointments as $appt)
                        <tr class="border-b border-[#F4FAF8] hover:bg-[#F4FAF8]/60 transition-colors">
                            <td class="px-6 py-4 font-bold text-[#111827]">{{ $appt->name }}</td>
                            <td class="px-6 py-4">
                                <div class="font-semibold">{{ $appt->phone }}</div>
                                <div class="text-xs text-[#6B7280]">{{ $appt->email ?? '—' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold">{{ $appt->preferred_date }}</div>
                                <div class="text-xs text-[#6B7280]">{{ $appt->preferred_time }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-semibold bg-[#EEF7F4] text-[#006F5C] px-2 py-1 rounded-full">
                                    {{ $appt->service ? $appt->service->title : "General" }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-orange-50 text-orange-600 border border-orange-200',
                                        'contacted' => 'bg-blue-50 text-blue-600 border border-blue-200',
                                        'confirmed' => 'bg-[#EEF7F4] text-[#006F5C] border border-[#006F5C]/20',
                                        'completed' => 'bg-gray-100 text-gray-600 border border-gray-200',
                                        'cancelled' => 'bg-[#FDF2F5] text-[#CC205C] border border-[#CC205C]/15',
                                    ];
                                    $col = $statusColors[$appt->status] ?? 'bg-gray-100 text-gray-600';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $col }}">
                                    {{ $appt->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-[#6B7280]">{{ $appt->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ url('/admin/appointments/' . $appt->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#EEF7F4] text-[#006F5C] hover:bg-[#006F5C] hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-[#6B7280]">No appointments found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-[#EEF7F4]">
        {{ $appointments->withQueryString()->links() }}
    </div>
</div>
@endsection
