@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard Overview')

@section('admin_content')
<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Total Services -->
    <div class="bg-white border border-[#EEF7F4] rounded-[24px] p-6 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-[#6B7280] uppercase tracking-wider block">Total Services</span>
            <span class="text-3xl font-extrabold text-[#111827] mt-1 block">{{ $stats['total_services'] }}</span>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-[#EEF7F4] text-[#006F5C] flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
            </svg>
        </div>
    </div>

    <!-- Pending Appointments -->
    <div class="bg-white border border-[#EEF7F4] rounded-[24px] p-6 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-[#6B7280] uppercase tracking-wider block">Pending Bookings</span>
            <span class="text-3xl font-extrabold text-[#FF8A00] mt-1 block">{{ $stats['pending_appointments'] }}</span>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-orange-50 text-[#FF8A00] flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>

    <!-- Confirmed Appointments -->
    <div class="bg-white border border-[#EEF7F4] rounded-[24px] p-6 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-[#6B7280] uppercase tracking-wider block">Confirmed Bookings</span>
            <span class="text-3xl font-extrabold text-[#006F5C] mt-1 block">{{ $stats['confirmed_appointments'] }}</span>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-[#EEF7F4] text-[#006F5C] flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>

    <!-- Total FAQs -->
    <div class="bg-white border border-[#EEF7F4] rounded-[24px] p-6 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-[#6B7280] uppercase tracking-wider block">Total FAQs</span>
            <span class="text-3xl font-extrabold text-[#CC205C] mt-1 block">{{ $stats['total_faqs'] }}</span>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-pink-50 text-[#CC205C] flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>

</div>

<!-- Recent Appointments Table -->
<div class="bg-white border border-[#EEF7F4] rounded-[24px] shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-[#EEF7F4] flex items-center justify-between">
        <h3 class="font-extrabold text-lg text-[#111827]">Recent Appointment Requests</h3>
        <a href="{{ url('/admin/appointments') }}" class="text-xs font-bold text-[#006F5C] hover:text-[#CC205C] uppercase tracking-wider">
            View All
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#F4FAF8] border-b border-[#EEF7F4] text-xs font-bold uppercase tracking-wider text-[#6B7280]">
                    <th class="px-6 py-4">Patient Name</th>
                    <th class="px-6 py-4">Phone / Email</th>
                    <th class="px-6 py-4">Preferred Slot</th>
                    <th class="px-6 py-4">Requested Service</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Created Date</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm font-medium text-[#374151]">
                @if(count($recentAppointments) > 0)
                    @foreach($recentAppointments as $appt)
                        <tr class="border-b border-[#F4FAF8] hover:bg-[#F4FAF8]/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-[#111827]">{{ $appt->name }}</td>
                            <td class="px-6 py-4">
                                <div class="text-[#111827]">{{ $appt->phone }}</div>
                                <div class="text-xs text-[#6B7280]">{{ $appt->email ?? 'No email' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div>{{ $appt->preferred_date }}</div>
                                <div class="text-xs text-[#6B7280] font-semibold">{{ $appt->preferred_time }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#EEF7F4] text-[#006F5C]">
                                    {{ $appt->service ? $appt->service->title : "Men's Health" }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-orange-50 text-[#FF8A00] border border-orange-200',
                                        'contacted' => 'bg-blue-50 text-blue-600 border border-blue-200',
                                        'confirmed' => 'bg-[#EEF7F4] text-[#006F5C] border border-[#006F5C]/20',
                                        'completed' => 'bg-gray-100 text-gray-700 border border-gray-200',
                                        'cancelled' => 'bg-[#FDF2F5] text-[#CC205C] border border-[#CC205C]/15',
                                    ];
                                    $col = $statusColors[$appt->status] ?? 'bg-gray-100 text-gray-600';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider {{ $col }}">
                                    {{ $appt->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-[#6B7280]">{{ $appt->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ url('/admin/appointments/' . $appt->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#EEF7F4] text-[#006F5C] hover:bg-[#006F5C] hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-[#6B7280]">
                            No appointment requests received yet.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
