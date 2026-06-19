@extends('layouts.admin')

@section('title', 'View Appointment')
@section('page_title', 'Appointment Detail')

@section('admin_content')
<div class="mb-6">
    <a href="{{ url('/admin/appointments') }}" class="inline-flex items-center text-sm text-[#C9A84C] hover:text-white font-semibold">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        Back to Appointments
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Client Info -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-[#111827] border border-gray-800 p-8">
            <h3 class="font-extrabold text-lg text-white border-b border-gray-800 pb-4 mb-6">Client Information</h3>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Full Name</dt>
                    <dd class="font-bold text-white text-sm">{{ $appointment->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Phone</dt>
                    <dd class="font-bold text-white text-sm">{{ $appointment->phone }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Email</dt>
                    <dd class="font-semibold text-gray-300 text-sm">{{ $appointment->email ?? 'Not Provided' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Requested Service</dt>
                    <dd>
                        <span class="text-xs font-bold bg-[#C9A84C]/10 text-[#C9A84C] px-3 py-1.5">
                            {{ $appointment->service ? $appointment->service->title : 'General' }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Preferred Date</dt>
                    <dd class="font-bold text-white text-sm">{{ $appointment->preferred_date }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Preferred Time</dt>
                    <dd class="font-bold text-white text-sm">{{ $appointment->preferred_time }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Client Note</dt>
                    <dd class="text-sm text-gray-300 bg-[#1a1f2e] p-4 border border-gray-800">{{ $appointment->note ?? 'No note provided.' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Submitted At</dt>
                    <dd class="text-sm text-gray-300">{{ $appointment->created_at->format('d M Y, h:i A') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Status Management -->
    <div class="space-y-6">
        <!-- Current Status -->
        <div class="bg-[#111827] border border-gray-800 p-6">
            <h3 class="font-extrabold text-sm text-gray-400 uppercase tracking-wider border-b border-gray-800 pb-3 mb-4">Current Status</h3>
            @php
                $statusColors = [
                    'pending' => 'bg-orange-900/30 text-orange-400 border-orange-800/50',
                    'contacted' => 'bg-blue-900/30 text-blue-400 border-blue-800/50',
                    'confirmed' => 'bg-green-900/30 text-green-400 border-green-800/50',
                    'completed' => 'bg-gray-800 text-gray-400 border-gray-700',
                    'cancelled' => 'bg-red-900/30 text-red-400 border-red-800/50',
                ];
                $col = $statusColors[$appointment->status] ?? 'bg-gray-800 text-gray-400';
            @endphp
            <span class="inline-flex items-center px-4 py-2 text-sm font-black uppercase tracking-wider border {{ $col }}">
                {{ $appointment->status }}
            </span>
        </div>

        <!-- Update Status Form -->
        <div class="bg-[#111827] border border-gray-800 p-6">
            <h3 class="font-extrabold text-sm text-gray-400 uppercase tracking-wider border-b border-gray-800 pb-3 mb-4">Update Status</h3>
            <form action="{{ url('/admin/appointments/' . $appointment->id . '/status') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">New Status</label>
                    <select name="status" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
                        <option value="pending" {{ $appointment->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="contacted" {{ $appointment->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="confirmed" {{ $appointment->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ $appointment->status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $appointment->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Admin Internal Note</label>
                    <textarea name="admin_note" rows="3" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors" placeholder="Internal notes...">{{ $appointment->admin_note }}</textarea>
                </div>
                <button type="submit" class="w-full py-3 px-5 text-xs font-bold uppercase tracking-wider text-black bg-[#C9A84C] hover:bg-[#b8973f] transition-all">
                    Save Status
                </button>
            </form>
        </div>

        <!-- Delete -->
        <div class="bg-[#111827] border border-red-900/50 p-6">
            <h3 class="font-extrabold text-sm text-red-400 uppercase tracking-wider border-b border-red-900/30 pb-3 mb-4">Danger Zone</h3>
            <form action="{{ url('/admin/appointments/' . $appointment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this appointment?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-3 px-5 text-xs font-bold uppercase tracking-wider text-white bg-red-600 hover:bg-red-700 transition-all">
                    Delete Appointment
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
