@extends('layouts.admin')

@section('title', 'Application: ' . $application->full_name)
@section('page_title', 'Job Application Profile')

@section('admin_content')
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('admin.applications.index') }}" class="inline-flex items-center text-xs font-bold uppercase tracking-wider text-[#C9A84C] hover:underline">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        Back to Applications
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Applicant core profile and custom answers -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Profile Card -->
        <div class="bg-[#111827] border border-gray-800 p-6 space-y-6">
            <div class="flex items-center gap-4 border-b border-gray-800 pb-4">
                <div class="h-12 w-12 rounded-full bg-[#C9A84C]/10 border border-[#C9A84C]/25 flex items-center justify-center font-serif text-lg font-bold text-[#C9A84C]">
                    {{ strtoupper(substr($application->full_name, 0, 1)) }}
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wide">{{ $application->full_name }}</h3>
                    <p class="text-xs text-gray-500 font-mono mt-0.5">Reference ID: {{ $application->reference_number }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                    <span class="text-gray-500 uppercase font-mono block mb-1">Email Address</span>
                    <a href="mailto:{{ $application->email }}" class="text-white font-semibold hover:text-[#C9A84C]">{{ $application->email }}</a>
                </div>
                <div>
                    <span class="text-gray-500 uppercase font-mono block mb-1">Phone Number</span>
                    <a href="tel:{{ $application->phone }}" class="text-white font-semibold hover:text-[#C9A84C]">{{ $application->phone }}</a>
                </div>
                @if($application->present_address)
                    <div class="sm:col-span-2">
                        <span class="text-gray-500 uppercase font-mono block mb-1">Present Address</span>
                        <span class="text-gray-300">{{ $application->present_address }}</span>
                    </div>
                @endif
                @if($application->linkedin_url)
                    <div>
                        <span class="text-gray-500 uppercase font-mono block mb-1">LinkedIn Profile</span>
                        <a href="{{ $application->linkedin_url }}" target="_blank" class="text-[#C9A84C] hover:underline">{{ $application->linkedin_url }}</a>
                    </div>
                @endif
                @if($application->portfolio_url)
                    <div>
                        <span class="text-gray-500 uppercase font-mono block mb-1">Portfolio Link</span>
                        <a href="{{ $application->portfolio_url }}" target="_blank" class="text-[#C9A84C] hover:underline">{{ $application->portfolio_url }}</a>
                    </div>
                @endif
                @if($application->current_company || $application->current_designation)
                    <div>
                        <span class="text-gray-500 uppercase font-mono block mb-1">Current Employment</span>
                        <span class="text-gray-300 font-semibold">{{ $application->current_designation ?? 'Groomer' }} at {{ $application->current_company ?? 'N/A' }}</span>
                    </div>
                @endif
                @if($application->expected_salary)
                    <div>
                        <span class="text-gray-500 uppercase font-mono block mb-1">Expected Salary</span>
                        <span class="text-gray-300 font-semibold">{{ number_format($application->expected_salary) }} BDT</span>
                    </div>
                @endif
                @if($application->available_joining_date)
                    <div>
                        <span class="text-gray-500 uppercase font-mono block mb-1">Available Joining Date</span>
                        <span class="text-gray-300 font-semibold">{{ $application->available_joining_date->format('Y-m-d') }}</span>
                    </div>
                @endif
                <div>
                    <span class="text-gray-500 uppercase font-mono block mb-1">Submitted IP</span>
                    <span class="text-gray-400 font-mono">{{ $application->submitted_ip }}</span>
                </div>
            </div>

            @if($application->cover_letter)
                <div class="border-t border-gray-800 pt-4">
                    <h4 class="text-xs font-bold uppercase tracking-widest text-white mb-2">Cover Letter</h4>
                    <p class="text-xs text-gray-300 leading-relaxed italic bg-[#0c0f15] p-4 border border-gray-800 whitespace-pre-wrap">"{{ $application->cover_letter }}"</p>
                </div>
            @endif
        </div>

        <!-- Custom Answers Display -->
        <div class="bg-[#111827] border border-gray-800 p-6 space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-white border-b border-gray-800 pb-3 mb-4">Answers to Custom Application Questions</h3>
            
            @forelse($application->answers as $ans)
                <div class="p-4 bg-[#0c0f15] border border-gray-800 space-y-2 text-xs">
                    <div class="flex justify-between items-start">
                        <div class="font-semibold text-white">{{ $ans->question_snapshot['question'] }}</div>
                        <span class="text-[9px] bg-gray-800 text-gray-500 px-2 py-0.5 font-mono uppercase tracking-wider">
                            {{ str_replace('_', ' ', $ans->question_snapshot['question_type']) }}
                        </span>
                    </div>
                    
                    @if($ans->question_snapshot['question_type'] === 'file' && $ans->file_path)
                        <div class="pt-1">
                            <span class="text-gray-400 block mb-1">Attachment:</span>
                            <a href="{{ route('admin.applications.download-cv', $application->id) }}?file_path={{ urlencode($ans->file_path) }}" class="inline-flex items-center text-xs text-[#C9A84C] hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                Download Question File
                            </a>
                        </div>
                    @else
                        <div class="text-gray-300 font-sans leading-relaxed">{{ $ans->answer ?? 'Left Blank' }}</div>
                    @endif
                </div>
            @empty
                <p class="text-xs text-gray-600 text-center py-6">No custom questions answered by applicant.</p>
            @endforelse
        </div>
    </div>

    <!-- Sidebar controls and Notes -->
    <div class="space-y-6">
        <!-- Actions & status -->
        <div class="bg-[#111827] border border-gray-800 p-6 space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-white border-b border-gray-800 pb-3 mb-4">Application Status</h3>

            <div class="text-xs space-y-3">
                <div>
                    <span class="text-gray-500 uppercase font-mono block mb-1.5">Current Status</span>
                    @if($application->status === 'new')
                        <span class="text-xs font-bold bg-blue-900/30 text-blue-400 px-3 py-1 border border-blue-800/50 uppercase">New</span>
                    @elseif($application->status === 'under_review')
                        <span class="text-xs font-bold bg-yellow-900/30 text-yellow-400 px-3 py-1 border border-yellow-800/30 uppercase">Under Review</span>
                    @elseif($application->status === 'shortlisted')
                        <span class="text-xs font-bold bg-green-900/30 text-green-400 px-3 py-1 border border-green-800/50 uppercase">Shortlisted</span>
                    @elseif($application->status === 'selected')
                        <span class="text-xs font-bold bg-green-500/10 text-green-400 px-3 py-1 border border-green-500/20 uppercase">Selected</span>
                    @elseif($application->status === 'rejected')
                        <span class="text-xs font-bold bg-red-900/30 text-red-400 px-3 py-1 border border-red-800/50 uppercase">Rejected</span>
                    @else
                        <span class="text-xs font-bold bg-gray-800 text-gray-500 px-3 py-1 uppercase">{{ str_replace('_', ' ', $application->status) }}</span>
                    @endif
                </div>

                <form action="{{ route('admin.applications.status', $application->id) }}" method="POST" class="space-y-3 pt-3 border-t border-gray-800/60">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">Change Status</label>
                        <select name="status" class="w-full bg-[#0c0f15] border border-gray-800 text-xs text-gray-200 px-3 py-2 focus:outline-none focus:border-[#C9A84C]">
                            <option value="new" {{ $application->status === 'new' ? 'selected' : '' }}>New</option>
                            <option value="under_review" {{ $application->status === 'under_review' ? 'selected' : '' }}>Under Review</option>
                            <option value="shortlisted" {{ $application->status === 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                            <option value="interview_scheduled" {{ $application->status === 'interview_scheduled' ? 'selected' : '' }}>Interview Scheduled</option>
                            <option value="selected" {{ $application->status === 'selected' ? 'selected' : '' }}>Selected</option>
                            <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="withdrawn" {{ $application->status === 'withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">Change Log Note</label>
                        <input type="text" name="note" placeholder="Reason for change" class="w-full bg-[#0c0f15] border border-gray-800 text-xs text-gray-200 px-3 py-2 focus:outline-none focus:border-[#C9A84C]">
                    </div>

                    <button type="submit" class="w-full py-2 bg-[#C9A84C] hover:bg-[#b8973f] text-black text-xs font-bold uppercase tracking-widest transition-colors">
                        Update Status
                    </button>
                </form>
            </div>
        </div>

        <!-- CV download -->
        <div class="bg-[#111827] border border-gray-800 p-6 space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-white border-b border-gray-800 pb-3 mb-4">Resume Attachment</h3>
            <a href="{{ route('admin.applications.download-cv', $application->id) }}" class="w-full py-3 bg-blue-900/20 text-blue-400 border border-blue-800/40 hover:bg-blue-900/30 text-xs font-bold uppercase tracking-widest flex items-center justify-center gap-2 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                Download Candidate CV
            </a>
        </div>

        <!-- Private Notes -->
        <div class="bg-[#111827] border border-gray-800 p-6 space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-white border-b border-gray-800 pb-3 mb-4">Private Admin Notes</h3>
            <form action="{{ route('admin.applications.notes', $application->id) }}" method="POST" class="space-y-3">
                @csrf
                @method('PATCH')
                <textarea name="admin_note" rows="5" placeholder="Write private notes visible only to the recruitment committee..." class="w-full bg-[#0c0f15] border border-gray-800 text-xs text-gray-200 p-3 focus:outline-none focus:border-[#C9A84C]">{{ $application->admin_note }}</textarea>
                <button type="submit" class="w-full py-2 bg-gray-800 hover:bg-gray-700 text-gray-300 text-xs font-bold uppercase tracking-widest transition-colors">
                    Save Note
                </button>
            </form>
        </div>

        <!-- History Log -->
        <div class="bg-[#111827] border border-gray-800 p-6 space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-white border-b border-gray-800 pb-3 mb-4">Recruitment Audit Trail</h3>
            <div class="space-y-3 text-xs">
                @forelse($application->histories as $log)
                    <div class="border-b border-gray-800/60 pb-2">
                        <div class="flex justify-between text-gray-500 font-mono text-[10px]">
                            <span>By: {{ $log->user ? $log->user->name : 'System' }}</span>
                            <span>{{ $log->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                        <div class="mt-1 text-white">
                            <span class="text-gray-400 font-semibold">{{ strtoupper($log->previous_status) }}</span> &rarr; 
                            <span class="text-[#C9A84C] font-semibold">{{ strtoupper($log->new_status) }}</span>
                        </div>
                        @if($log->note)
                            <div class="text-gray-500 text-[10px] italic mt-0.5">Note: "{{ $log->note }}"</div>
                        @endif
                    </div>
                @empty
                    <p class="text-[10px] text-gray-600 text-center py-2">No audits logged yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
