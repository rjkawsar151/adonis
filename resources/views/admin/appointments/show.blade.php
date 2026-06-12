@extends('layouts.admin')

@section('title', 'View Appointment')
@section('page_title', 'Appointment Detail')

@section('admin_content')
<div class="mb-6">
    <a href="{{ url('/admin/appointments') }}" class="inline-flex items-center text-sm text-[#006F5C] hover:text-[#CC205C] font-semibold">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        Back to Appointments
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Patient Info -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white border border-[#EEF7F4] rounded-[24px] p-8 shadow-sm">
            <h3 class="font-extrabold text-lg text-[#111827] border-b border-[#EEF7F4] pb-4 mb-6">Patient Information</h3>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <dt class="text-xs font-bold text-[#6B7280] uppercase tracking-wider mb-1">Full Name</dt>
                    <dd class="font-bold text-[#111827] text-sm">{{ $appointment->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold text-[#6B7280] uppercase tracking-wider mb-1">Phone</dt>
                    <dd class="font-bold text-[#111827] text-sm">{{ $appointment->phone }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold text-[#6B7280] uppercase tracking-wider mb-1">Email</dt>
                    <dd class="font-semibold text-[#374151] text-sm">{{ $appointment->email ?? 'Not Provided' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold text-[#6B7280] uppercase tracking-wider mb-1">Requested Service</dt>
                    <dd>
                        <span class="text-xs font-bold bg-[#EEF7F4] text-[#006F5C] px-3 py-1.5 rounded-full">
                            {{ $appointment->service ? $appointment->service->title : 'General' }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-bold text-[#6B7280] uppercase tracking-wider mb-1">Preferred Date</dt>
                    <dd class="font-bold text-[#111827] text-sm">{{ $appointment->preferred_date }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold text-[#6B7280] uppercase tracking-wider mb-1">Preferred Time</dt>
                    <dd class="font-bold text-[#111827] text-sm">{{ $appointment->preferred_time }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-bold text-[#6B7280] uppercase tracking-wider mb-1">Patient Note</dt>
                    <dd class="text-sm text-[#374151] bg-[#F4FAF8] rounded-2xl p-4 border border-[#EEF7F4]">{{ $appointment->note ?? 'No note provided.' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold text-[#6B7280] uppercase tracking-wider mb-1">Submitted At</dt>
                    <dd class="text-sm text-[#374151]">{{ $appointment->created_at->format('d M Y, h:i A') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Status Management -->
    <div class="space-y-6">
        <!-- Current Status -->
        <div class="bg-white border border-[#EEF7F4] rounded-[24px] p-6 shadow-sm">
            <h3 class="font-extrabold text-sm text-[#111827] uppercase tracking-wider border-b border-[#EEF7F4] pb-3 mb-4">Current Status</h3>
            @php
                $statusColors = [
                    'pending' => 'bg-orange-50 text-orange-600 border-orange-200',
                    'contacted' => 'bg-blue-50 text-blue-600 border-blue-200',
                    'confirmed' => 'bg-[#EEF7F4] text-[#006F5C] border-[#006F5C]/20',
                    'completed' => 'bg-gray-100 text-gray-600 border-gray-200',
                    'cancelled' => 'bg-[#FDF2F5] text-[#CC205C] border-[#CC205C]/15',
                ];
                $col = $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-600';
            @endphp
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-black uppercase tracking-wider border {{ $col }}">
                {{ $appointment->status }}
            </span>
        </div>

        <!-- Update Status Form -->
        <div class="bg-white border border-[#EEF7F4] rounded-[24px] p-6 shadow-sm">
            <h3 class="font-extrabold text-sm text-[#111827] uppercase tracking-wider border-b border-[#EEF7F4] pb-3 mb-4">Update Status</h3>
            <form action="{{ url('/admin/appointments/' . $appointment->id . '/status') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">New Status</label>
                    <select name="status" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-full text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
                        <option value="pending" {{ $appointment->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="contacted" {{ $appointment->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="confirmed" {{ $appointment->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ $appointment->status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $appointment->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Admin Internal Note</label>
                    <textarea name="admin_note" rows="3" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors" placeholder="Internal notes (not visible to patient)...">{{ $appointment->admin_note }}</textarea>
                </div>
                <button type="submit" class="w-full py-3 px-5 text-xs font-bold uppercase tracking-wider rounded-full text-white bg-[#006F5C] hover:bg-[#005547] shadow-sm transition-all">
                    Save Status
                </button>
            </form>
        </div>

        <!-- Delete -->
        <div class="bg-white border border-[#FDF2F5] rounded-[24px] p-6 shadow-sm">
            <h3 class="font-extrabold text-sm text-[#CC205C] uppercase tracking-wider border-b border-[#FDF2F5] pb-3 mb-4">Danger Zone</h3>
            <form action="{{ url('/admin/appointments/' . $appointment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this appointment?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-3 px-5 text-xs font-bold uppercase tracking-wider rounded-full text-white bg-[#CC205C] hover:bg-[#A61A4B] shadow-sm transition-all">
                    Delete Appointment
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
