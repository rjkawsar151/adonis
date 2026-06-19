@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard Overview')

@section('admin_content')
<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <!-- Total Services -->
    <div class="bg-[#111827] border border-gray-800 p-6 flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Total Services</span>
            <span class="text-3xl font-extrabold text-white mt-1 block">{{ $stats['total_services'] }}</span>
        </div>
        <div class="w-12 h-12 bg-[#C9A84C]/10 text-[#C9A84C] flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
            </svg>
        </div>
    </div>

    <!-- Total Appointments -->
    <div class="bg-[#111827] border border-gray-800 p-6 flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Total Bookings</span>
            <span class="text-3xl font-extrabold text-[#C9A84C] mt-1 block">{{ $stats['total_appointments'] }}</span>
        </div>
        <div class="w-12 h-12 bg-[#C9A84C]/10 text-[#C9A84C] flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
    </div>

    <!-- Pending Appointments -->
    <div class="bg-[#111827] border border-gray-800 p-6 flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Pending</span>
            <span class="text-3xl font-extrabold text-orange-400 mt-1 block">{{ $stats['pending_appointments'] }}</span>
        </div>
        <div class="w-12 h-12 bg-orange-900/30 text-orange-400 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>

    <!-- Confirmed Appointments -->
    <div class="bg-[#111827] border border-gray-800 p-6 flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Confirmed</span>
            <span class="text-3xl font-extrabold text-green-400 mt-1 block">{{ $stats['confirmed_appointments'] }}</span>
        </div>
        <div class="w-12 h-12 bg-green-900/30 text-green-400 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>

    <!-- Today's Appointments -->
    <div class="bg-[#111827] border border-gray-800 p-6 flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Today's Bookings</span>
            <span class="text-3xl font-extrabold text-blue-400 mt-1 block">{{ count($todayAppointments) }}</span>
        </div>
        <div class="w-12 h-12 bg-blue-900/30 text-blue-400 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
        </div>
    </div>

    <!-- Completed -->
    <div class="bg-[#111827] border border-gray-800 p-6 flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Completed</span>
            <span class="text-3xl font-extrabold text-gray-300 mt-1 block">{{ $stats['completed_appointments'] }}</span>
        </div>
        <div class="w-12 h-12 bg-gray-800 text-gray-400 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
            </svg>
        </div>
    </div>

    <!-- Cancelled -->
    <div class="bg-[#111827] border border-gray-800 p-6 flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Cancelled</span>
            <span class="text-3xl font-extrabold text-red-400 mt-1 block">{{ $stats['cancelled_appointments'] }}</span>
        </div>
        <div class="w-12 h-12 bg-red-900/30 text-red-400 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>
    </div>

    <!-- Contacted -->
    <div class="bg-[#111827] border border-gray-800 p-6 flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Contacted</span>
            <span class="text-3xl font-extrabold text-sky-400 mt-1 block">{{ $stats['contacted_appointments'] }}</span>
        </div>
        <div class="w-12 h-12 bg-sky-900/30 text-sky-400 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>
    </div>

</div>

<!-- Two Column Layout: Weekly Trend + Status Distribution -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

    <!-- Weekly Trend -->
    <div class="bg-[#111827] border border-gray-800 p-6">
        <h3 class="font-bold text-base text-white mb-4">Weekly Booking Trend</h3>
        <div class="flex items-end justify-between gap-2 h-40">
            @php $maxTrend = max(1, max($trendData)); @endphp
            @foreach($trendData as $idx => $val)
                <div class="flex-1 flex flex-col items-center gap-1.5">
                    <span class="text-xs font-bold text-{{ $val > 0 ? 'white' : 'gray-600' }}">{{ $val }}</span>
                    <div class="w-full rounded-sm" style="height: {{ max(4, ($val / $maxTrend) * 120) }}px; background-color: {{ $val > 0 ? '#C9A84C' : '#1f2937' }}; min-height: 4px;"></div>
                    <span class="text-[10px] font-semibold text-gray-500 uppercase">{{ $trendLabels[$idx] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Status Distribution -->
    <div class="bg-[#111827] border border-gray-800 p-6">
        <h3 class="font-bold text-base text-white mb-4">Booking Status Overview</h3>
        @php
            $statusTotal = max(1, array_sum($statusDistribution));
        @endphp
        <div class="space-y-3">
            @foreach([
                ['label' => 'Pending', 'key' => 'pending', 'color' => 'bg-orange-400', 'bg' => 'bg-orange-900/30', 'text' => 'text-orange-400'],
                ['label' => 'Contacted', 'key' => 'contacted', 'color' => 'bg-sky-400', 'bg' => 'bg-sky-900/30', 'text' => 'text-sky-400'],
                ['label' => 'Confirmed', 'key' => 'confirmed', 'color' => 'bg-green-400', 'bg' => 'bg-green-900/30', 'text' => 'text-green-400'],
                ['label' => 'Completed', 'key' => 'completed', 'color' => 'bg-gray-400', 'bg' => 'bg-gray-800', 'text' => 'text-gray-400'],
                ['label' => 'Cancelled', 'key' => 'cancelled', 'color' => 'bg-red-400', 'bg' => 'bg-red-900/30', 'text' => 'text-red-400'],
            ] as $item)
                @php
                    $count = $statusDistribution[$item['key']];
                    $pct = round(($count / $statusTotal) * 100);
                @endphp
                <div class="flex items-center gap-3">
                    <span class="w-20 text-xs font-semibold {{ $item['text'] }}">{{ $item['label'] }}</span>
                    <div class="flex-1 h-5 bg-gray-800 rounded-sm overflow-hidden">
                        <div class="h-full {{ $item['color'] }} rounded-sm transition-all" style="width: {{ $pct }}%"></div>
                    </div>
                    <span class="w-16 text-right text-xs font-bold text-white">{{ $count }} ({{ $pct }}%)</span>
                </div>
            @endforeach
        </div>
    </div>

</div>

<!-- Today's Appointments -->
<div class="bg-[#111827] border border-gray-800 overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-gray-800 flex items-center justify-between">
        <h3 class="font-bold text-lg text-white">Today's Appointments</h3>
        <span class="text-xs text-gray-500 font-semibold">{{ now()->format('l, F j, Y') }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#0c0f15] border-b border-gray-800 text-xs font-bold uppercase tracking-wider text-gray-500">
                    <th class="px-6 py-4">Time</th>
                    <th class="px-6 py-4">Client</th>
                    <th class="px-6 py-4">Phone</th>
                    <th class="px-6 py-4">Service</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="text-sm font-medium text-gray-300">
                @forelse($todayAppointments as $appt)
                    <tr class="border-b border-gray-800 hover:bg-gray-800/30 transition-colors">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 font-bold text-[#C9A84C]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $appt->preferred_time }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-white">{{ $appt->name }}</td>
                        <td class="px-6 py-4 text-gray-400">{{ $appt->phone }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-bold bg-[#C9A84C]/10 text-[#C9A84C]">
                                {{ $appt->service ? $appt->service->title : "-" }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $sc = [
                                    'pending' => 'bg-orange-900/30 text-orange-400 border border-orange-800/50',
                                    'contacted' => 'bg-blue-900/30 text-blue-400 border border-blue-800/50',
                                    'confirmed' => 'bg-green-900/30 text-green-400 border border-green-800/50',
                                    'completed' => 'bg-gray-800 text-gray-400 border border-gray-700',
                                    'cancelled' => 'bg-red-900/30 text-red-400 border border-red-800/50',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1.5 text-xs font-bold uppercase tracking-wider border {{ $sc[$appt->status] ?? 'bg-gray-800 text-gray-400' }}">
                                {{ $appt->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ url('/admin/appointments/' . $appt->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-gray-800 text-gray-400 hover:bg-[#C9A84C] hover:text-black transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-600">
                            No appointments scheduled for today.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Quick Actions + Branch Breakdown Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

    <!-- Quick Actions -->
    <div class="bg-[#111827] border border-gray-800 p-6">
        <h3 class="font-bold text-base text-white mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ url('/admin/appointments') }}" class="p-4 border border-gray-800 hover:border-[#C9A84C]/30 bg-gray-900/50 hover:bg-gray-800 transition-colors text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto mb-2 text-[#C9A84C]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <span class="text-xs font-bold text-gray-300">View Bookings</span>
            </a>
            <a href="{{ url('/admin/price-list/create') }}" class="p-4 border border-gray-800 hover:border-[#C9A84C]/30 bg-gray-900/50 hover:bg-gray-800 transition-colors text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto mb-2 text-[#C9A84C]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span class="text-xs font-bold text-gray-300">Add Service</span>
            </a>
            <a href="{{ url('/admin/price-list') }}" class="p-4 border border-gray-800 hover:border-[#C9A84C]/30 bg-gray-900/50 hover:bg-gray-800 transition-colors text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto mb-2 text-[#C9A84C]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="text-xs font-bold text-gray-300">Manage Services</span>
            </a>
            <a href="{{ url('/admin/settings') }}" class="p-4 border border-gray-800 hover:border-[#C9A84C]/30 bg-gray-900/50 hover:bg-gray-800 transition-colors text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto mb-2 text-[#C9A84C]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="text-xs font-bold text-gray-300">Settings</span>
            </a>
        </div>
    </div>

    <!-- Branch Breakdown -->
    <div class="bg-[#111827] border border-gray-800 p-6">
        <h3 class="font-bold text-base text-white mb-4">Branch Breakdown</h3>
        @php
            $branchMax = max(1, $branchBreakdown['gulshan'] + $branchBreakdown['bashundhara']);
        @endphp
        <div class="space-y-4">
            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <span class="text-sm font-bold text-white">Gulshan</span>
                    <span class="text-sm font-bold text-[#C9A84C]">{{ $branchBreakdown['gulshan'] }} bookings</span>
                </div>
                <div class="h-3 bg-gray-800 rounded-sm overflow-hidden">
                    <div class="h-full bg-[#C9A84C] rounded-sm" style="width: {{ ($branchBreakdown['gulshan'] / $branchMax) * 100 }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <span class="text-sm font-bold text-white">Bashundhara</span>
                    <span class="text-sm font-bold text-[#C9A84C]">{{ $branchBreakdown['bashundhara'] }} bookings</span>
                </div>
                <div class="h-3 bg-gray-800 rounded-sm overflow-hidden">
                    <div class="h-full bg-[#C9A84C] rounded-sm" style="width: {{ ($branchBreakdown['bashundhara'] / $branchMax) * 100 }}%"></div>
                </div>
            </div>
            <div class="pt-2 text-xs text-gray-500">
                Total: <span class="font-bold text-white">{{ array_sum($branchBreakdown) }}</span> branch-identified bookings
            </div>
        </div>
    </div>

</div>

<!-- Recent Appointments Table -->
<div class="bg-[#111827] border border-gray-800 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-800 flex items-center justify-between">
        <h3 class="font-bold text-lg text-white">Recent Booking Requests</h3>
        <a href="{{ url('/admin/appointments') }}" class="text-xs font-bold text-[#C9A84C] hover:text-white uppercase tracking-wider">
            View All &rarr;
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#0c0f15] border-b border-gray-800 text-xs font-bold uppercase tracking-wider text-gray-500">
                    <th class="px-6 py-4">Client Name</th>
                    <th class="px-6 py-4">Phone / Email</th>
                    <th class="px-6 py-4">Preferred Slot</th>
                    <th class="px-6 py-4">Service</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Created</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm font-medium text-gray-300">
                @forelse($recentAppointments as $appt)
                    <tr class="border-b border-gray-800 hover:bg-gray-800/30 transition-colors">
                        <td class="px-6 py-4 font-bold text-white">{{ $appt->name }}</td>
                        <td class="px-6 py-4">
                            <div class="text-white">{{ $appt->phone }}</div>
                            <div class="text-xs text-gray-500">{{ $appt->email ?? 'No email' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div>{{ $appt->preferred_date }}</div>
                            <div class="text-xs text-gray-500 font-semibold">{{ $appt->preferred_time }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-bold bg-[#C9A84C]/10 text-[#C9A84C]">
                                {{ $appt->service ? $appt->service->title : "-" }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $sc = [
                                    'pending' => 'bg-orange-900/30 text-orange-400 border border-orange-800/50',
                                    'contacted' => 'bg-blue-900/30 text-blue-400 border border-blue-800/50',
                                    'confirmed' => 'bg-green-900/30 text-green-400 border border-green-800/50',
                                    'completed' => 'bg-gray-800 text-gray-400 border border-gray-700',
                                    'cancelled' => 'bg-red-900/30 text-red-400 border border-red-800/50',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1.5 text-xs font-bold uppercase tracking-wider border {{ $sc[$appt->status] ?? 'bg-gray-800 text-gray-400' }}">
                                {{ $appt->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500">{{ $appt->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ url('/admin/appointments/' . $appt->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-gray-800 text-gray-400 hover:bg-[#C9A84C] hover:text-black transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-600">
                            No booking requests received yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
