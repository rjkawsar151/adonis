@extends('layouts.admin')

@section('title', 'Job Details: ' . $career->title)
@section('page_title', 'Job Posting Details')

@section('admin_content')
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('admin.careers.index') }}" class="inline-flex items-center text-xs font-bold uppercase tracking-wider text-[#C9A84C] hover:underline">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        Back to Careers
    </a>
    <div class="space-x-3">
        <a href="{{ route('admin.careers.edit', $career->id) }}" class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold uppercase tracking-wider text-black bg-[#C9A84C] hover:bg-[#b8973f] shadow-md transition-all">
            Edit Job
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Detailed Info -->
    <div class="lg:col-span-2 space-y-6">
        @if($career->featured_image)
            <div class="bg-[#111827] border border-gray-800 p-2 overflow-hidden shadow-sm">
                <img src="{{ asset($career->featured_image) }}" class="w-full h-64 object-cover">
            </div>
        @endif

        <div class="bg-[#111827] border border-gray-800 p-6 space-y-6">
            <div>
                <span class="text-xs font-mono uppercase tracking-[0.2em] text-[#C9A84C] block mb-1">
                    {{ $career->department ? $career->department->name : 'No Department' }} • 
                    {{ $career->employmentType ? $career->employmentType->name : 'No Type' }}
                </span>
                <h2 class="text-2xl font-bold text-white uppercase tracking-wide">{{ $career->title }}</h2>
                <p class="text-xs text-gray-500 font-mono mt-1">Slug: /career/{{ $career->slug }}</p>
            </div>

            @if($career->short_description)
                <div class="border-t border-gray-800 pt-4">
                    <h4 class="text-xs font-bold uppercase tracking-widest text-white mb-2">Short Description</h4>
                    <p class="text-xs text-gray-400 leading-relaxed">{{ $career->short_description }}</p>
                </div>
            @endif

            @if($career->description)
                <div class="border-t border-gray-800 pt-4">
                    <h4 class="text-xs font-bold uppercase tracking-widest text-white mb-3">Full Description</h4>
                    <div class="text-xs text-gray-300 space-y-3 leading-relaxed font-light prose prose-invert max-w-none">
                        {!! $career->description !!}
                    </div>
                </div>
            @endif

            @if($career->responsibilities)
                <div class="border-t border-gray-800 pt-4">
                    <h4 class="text-xs font-bold uppercase tracking-widest text-white mb-3">Responsibilities</h4>
                    <div class="text-xs text-gray-300 leading-relaxed prose prose-invert max-w-none">
                        {!! $career->responsibilities !!}
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-2 gap-6 border-t border-gray-800 pt-4">
                @if($career->educational_requirements)
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-widest text-white mb-2">Education Requirements</h4>
                        <div class="text-xs text-gray-400 leading-relaxed prose prose-invert">
                            {!! $career->educational_requirements !!}
                        </div>
                    </div>
                @endif
                @if($career->experience_requirements)
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-widest text-white mb-2">Experience Requirements</h4>
                        <div class="text-xs text-gray-400 leading-relaxed prose prose-invert">
                            {!! $career->experience_requirements !!}
                        </div>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-6 border-t border-gray-800 pt-4">
                @if($career->additional_requirements)
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-widest text-white mb-2">Additional Requirements</h4>
                        <div class="text-xs text-gray-400 leading-relaxed prose prose-invert">
                            {!! $career->additional_requirements !!}
                        </div>
                    </div>
                @endif
                @if($career->benefits)
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-widest text-white mb-2">Benefits Offered</h4>
                        <div class="text-xs text-gray-400 leading-relaxed prose prose-invert">
                            {!! $career->benefits !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Custom Questions Display -->
        <div class="bg-[#111827] border border-gray-800 p-6 space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-white border-b border-gray-800 pb-3 mb-4">Application Form Custom Questions</h3>
            @forelse($career->questions as $q)
                <div class="p-4 bg-[#0c0f15] border border-gray-800 flex justify-between items-center text-xs">
                    <div>
                        <div class="font-semibold text-white">{{ $q->question }}</div>
                        @if($q->help_text)
                            <div class="text-gray-500 mt-0.5">{{ $q->help_text }}</div>
                        @endif
                        @if($q->options)
                            <div class="text-gray-400 mt-1 font-mono text-[10px]">Options: {{ implode(', ', $q->options) }}</div>
                        @endif
                    </div>
                    <div class="text-right font-mono text-[10px] space-y-1">
                        <div class="text-gray-500 uppercase">Type: {{ strtoupper($q->question_type) }}</div>
                        @if($q->is_required)
                            <span class="text-[9px] bg-red-900/30 text-red-400 border border-red-800/40 px-2 py-0.5 font-bold uppercase">Mandatory</span>
                        @else
                            <span class="text-[9px] bg-gray-800 text-gray-500 px-2 py-0.5 font-bold uppercase">Optional</span>
                        @endif
                        <div class="text-gray-600">Sort: {{ $q->sort_order }}</div>
                    </div>
                </div>
            @empty
                <p class="text-xs text-gray-600 text-center py-6">No custom questions configured. Only default fields will be asked on submission.</p>
            @endforelse
        </div>

        <!-- Candidates Applied Card -->
        <div class="bg-[#111827] border border-gray-800 p-6 space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-white border-b border-gray-800 pb-3 mb-4">Candidates Applied for this Job</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#0c0f15] border-b border-gray-800 text-xs font-bold uppercase tracking-wider text-gray-500">
                            <th class="px-6 py-4">Reference</th>
                            <th class="px-6 py-4">Applicant</th>
                            <th class="px-6 py-4">Expected Salary</th>
                            <th class="px-6 py-4">Applied Date</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-300">
                        @forelse($career->applications as $app)
                            <tr class="border-b border-gray-800 hover:bg-gray-800/30 transition-colors {{ $app->trashed() ? 'opacity-50 bg-red-950/5' : '' }}">
                                <td class="px-6 py-4 font-mono text-xs text-white">
                                    {{ $app->reference_number }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-white">{{ $app->full_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $app->email }}</div>
                                    <div class="text-xs text-gray-500">{{ $app->phone }}</div>
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-white">
                                    @if($app->expected_salary)
                                        {{ number_format($app->expected_salary) }} BDT
                                    @else
                                        <span class="text-gray-600">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-400">
                                    {{ $app->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($app->status === 'new')
                                        <span class="text-[10px] font-bold bg-blue-900/30 text-blue-400 px-2 py-0.5 border border-blue-800/50 uppercase">New</span>
                                    @elseif($app->status === 'under_review')
                                        <span class="text-[10px] font-bold bg-yellow-900/30 text-yellow-400 px-2 py-0.5 border border-yellow-800/30 uppercase">Under Review</span>
                                    @elseif($app->status === 'shortlisted')
                                        <span class="text-[10px] font-bold bg-green-900/30 text-green-400 px-2 py-0.5 border border-green-800/50 uppercase">Shortlisted</span>
                                    	@elseif($app->status === 'selected')
                                         <span class="text-[10px] font-bold bg-green-500/10 text-green-400 px-2 py-0.5 border border-green-500/20 uppercase">Selected</span>
                                    @elseif($app->status === 'rejected')
                                        <span class="text-[10px] font-bold bg-red-900/30 text-red-400 px-2 py-0.5 border border-red-800/50 uppercase">Rejected</span>
                                    @else
                                        <span class="text-[10px] font-bold bg-gray-800 text-gray-500 px-2 py-0.5 uppercase">{{ str_replace('_', ' ', $app->status) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right space-x-1 whitespace-nowrap">
                                    <button type="button" onclick="openCvModal('{{ route('admin.applications.view-cv', $app->id) }}', '{{ addslashes($app->full_name) }}', '{{ route('admin.applications.show', $app->id) }}', '{{ route('admin.applications.download-cv', $app->id) }}')" class="inline-flex items-center justify-center w-8 h-8 bg-gray-800 text-gray-400 hover:bg-[#C9A84C] hover:text-black transition-colors" title="View CV">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </button>
                                    @if($app->cover_letter)
                                        <button type="button" data-name="{{ $app->full_name }}" data-cover-letter="{{ $app->cover_letter }}" onclick="openCoverLetterModal(this)" class="inline-flex items-center justify-center w-8 h-8 bg-gray-800 text-gray-400 hover:bg-[#C9A84C] hover:text-black transition-colors" title="View Cover Letter">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        </button>
                                    @else
                                        <button type="button" class="inline-flex items-center justify-center w-8 h-8 bg-gray-800/40 text-gray-700 cursor-not-allowed" title="No Cover Letter" disabled>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        </button>
                                    @endif
                                    <a href="{{ route('admin.applications.download-cv', $app->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-gray-800 text-gray-400 hover:bg-blue-600 hover:text-white transition-colors" title="Download CV">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                    </a>
                                    @if(!$app->trashed())
                                        <button type="button" data-url="{{ route('admin.applications.destroy', $app->id) }}" data-method="DELETE" data-confirm="Soft-delete this application?" class="single-action-btn inline-flex items-center justify-center w-8 h-8 bg-gray-800 text-gray-400 hover:bg-red-600 hover:text-white transition-colors" title="Soft Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    @else
                                        <button type="button" data-url="{{ route('admin.applications.restore', $app->id) }}" data-method="POST" class="single-action-btn inline-flex items-center justify-center w-8 h-8 bg-gray-800 text-green-400 hover:bg-green-600 hover:text-white transition-colors" title="Restore">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18.5" /></svg>
                                        </button>
                                        <button type="button" data-url="{{ route('admin.applications.force-delete', $app->id) }}" data-method="DELETE" data-confirm="PERMANENTLY delete this application?" class="single-action-btn inline-flex items-center justify-center w-8 h-8 bg-gray-800 text-red-500 hover:bg-red-700 hover:text-white transition-colors" title="Permanently Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-8 text-center text-gray-600">No applications received for this job listing yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Metadata Panel -->
    <div class="space-y-6">
        <div class="bg-[#111827] border border-gray-800 p-6 space-y-4 text-xs">
            <h3 class="text-sm font-bold uppercase tracking-wider text-white border-b border-gray-800 pb-3 mb-4">Statistics & Settings</h3>
            
            <div class="space-y-3">
                <div class="flex justify-between border-b border-gray-800 pb-2">
                    <span class="text-gray-500 uppercase font-mono">Job Status</span>
                    <span class="font-bold text-white uppercase">{{ $career->status }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-800 pb-2">
                    <span class="text-gray-500 uppercase font-mono">Location</span>
                    <span class="text-gray-300 font-semibold">{{ $career->location }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-800 pb-2">
                    <span class="text-gray-500 uppercase font-mono">Gender</span>
                    <span class="text-gray-300 font-semibold">{{ $career->gender }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-800 pb-2">
                    <span class="text-gray-500 uppercase font-mono">Vacancy</span>
                    <span class="text-gray-300 font-semibold">{{ $career->vacancy }} open positions</span>
                </div>
                <div class="flex justify-between border-b border-gray-800 pb-2">
                    <span class="text-gray-500 uppercase font-mono">Salary Range</span>
                    <span class="text-gray-300 font-semibold">
                        @if($career->salary_min || $career->salary_max)
                            {{ number_format($career->salary_min) }} - {{ number_format($career->salary_max) }} BDT
                        @else
                            {{ $career->salary_type }}
                        @endif
                    </span>
                </div>
                <div class="flex justify-between border-b border-gray-800 pb-2">
                    <span class="text-gray-500 uppercase font-mono">Deadline</span>
                    <span class="text-gray-300 font-semibold">{{ $career->application_deadline ? $career->application_deadline->format('Y-m-d') : 'No Deadline' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-800 pb-2">
                    <span class="text-gray-500 uppercase font-mono">Total Candidates</span>
                    <a href="{{ route('admin.applications.index', ['career_id' => $career->id]) }}" class="text-[#C9A84C] font-bold hover:underline">{{ $career->applications_count }} applications</a>
                </div>
                <div class="flex justify-between border-b border-gray-800 pb-2">
                    <span class="text-gray-500 uppercase font-mono">Shortlisted</span>
                    <span class="text-green-400 font-bold">{{ $career->shortlisted_count }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-800 pb-2">
                    <span class="text-gray-500 uppercase font-mono">Rejected</span>
                    <span class="text-red-400 font-bold">{{ $career->rejected_count }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-800 pb-2">
                    <span class="text-gray-500 uppercase font-mono">Posted At</span>
                    <span class="text-gray-400 font-semibold">{{ $career->created_at->format('Y-m-d H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 uppercase font-mono">Last Updated</span>
                    <span class="text-gray-400 font-semibold">{{ $career->updated_at->format('Y-m-d H:i') }}</span>
                </div>
            </div>
        </div>

        @if($career->seo_title || $career->seo_description)
            <div class="bg-[#111827] border border-gray-800 p-6 space-y-3 text-xs">
                <h3 class="text-sm font-bold uppercase tracking-wider text-white border-b border-gray-800 pb-3 mb-3">SEO Preview</h3>
                <div>
                    <span class="text-gray-500 block uppercase font-mono mb-1">Meta Title</span>
                    <span class="text-white font-semibold">{{ $career->seo_title }}</span>
                </div>
                <div>
                    <span class="text-gray-500 block uppercase font-mono mb-1">Meta Description</span>
                    <span class="text-gray-300 font-light leading-relaxed">{{ $career->seo_description }}</span>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.document-viewer-modal {
    position: fixed;
    inset: 0;
    z-index: 99999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 24px;
    background: rgba(0, 0, 0, 0.85);
}

.document-viewer-modal:not(.hidden) {
    display: flex;
}

.document-viewer {
    width: min(1400px, 95vw);
    height: 92vh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    background: #111827;
    border: 1px solid #1f2937;
    border-radius: 8px;
}

.viewer-header {
    min-height: 60px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 12px 20px;
    border-bottom: 1px solid #1f2937;
    background: #0c0f15;
}

.viewer-body {
    flex: 1;
    min-height: 0;
    overflow: auto;
    background: #0c0f15;
}

.viewer-body iframe,
.viewer-body embed,
.viewer-body object {
    width: 100%;
    height: 100%;
    min-height: 100%;
    display: block;
    border: 0;
}

.viewer-body img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
}

@media (max-width: 768px) {
    .document-viewer-modal {
        padding: 8px;
    }

    .document-viewer {
        width: 100%;
        height: 96vh;
    }

    .viewer-header {
        align-items: flex-start;
        flex-direction: column;
        gap: 10px;
        padding: 12px 16px;
    }
}
</style>

<!-- CV Preview Modal -->
<div id="cv-modal" class="document-viewer-modal hidden">
    <!-- Overlay background -->
    <div class="absolute inset-0 transition-opacity bg-transparent" onclick="closeCvModal()"></div>

    <div class="document-viewer relative z-10">
        <!-- Header -->
        <div class="viewer-header">
            <div class="flex items-center space-x-3">
                <h3 class="text-sm font-bold uppercase tracking-wider text-white flex items-center">
                    <span class="text-[#C9A84C] mr-2">CV Preview:</span> <span id="cv-modal-title">Candidate</span>
                </h3>
                <a id="cv-modal-details-btn" href="#" class="px-3 py-1 bg-gray-800 text-gray-300 hover:text-white text-[10px] font-semibold uppercase tracking-wider transition-colors border border-gray-700">View Application Form</a>
            </div>

            <div class="flex items-center space-x-2">
                <a id="cv-modal-newtab-btn" href="#" target="_blank" class="px-3 py-1.5 bg-gray-800 text-gray-300 hover:text-white text-[10px] font-semibold uppercase tracking-wider transition-colors border border-gray-700">Open in New Tab</a>
                <a id="cv-modal-download-btn" href="#" class="px-3 py-1.5 bg-gray-850 hover:bg-[#C9A84C] text-[#C9A84C] hover:text-black border border-[#C9A84C]/20 text-[10px] font-semibold uppercase tracking-wider transition-all duration-300">Download</a>
                <button type="button" onclick="closeCvModal()" class="px-3 py-1.5 bg-red-900/20 text-red-400 hover:bg-red-900/40 text-[10px] font-semibold uppercase tracking-wider transition-colors border border-red-800/30">Close</button>
            </div>
        </div>

        <!-- Body -->
        <div class="viewer-body">
            <iframe id="cv-viewer-frame" src=""></iframe>
        </div>
    </div>
</div>

<!-- Cover Letter Preview Modal -->
<div id="cl-modal" class="document-viewer-modal hidden">
    <!-- Overlay background -->
    <div class="absolute inset-0 transition-opacity bg-transparent" onclick="closeCoverLetterModal()"></div>

    <div class="document-viewer relative z-10 !h-auto max-h-[85vh] !w-[650px] max-w-full">
        <!-- Header -->
        <div class="viewer-header">
            <div class="flex items-center space-x-3">
                <h3 class="text-sm font-bold uppercase tracking-wider text-white flex items-center">
                    <span class="text-[#C9A84C] mr-2">Cover Letter:</span> <span id="cl-modal-title">Candidate</span>
                </h3>
            </div>
            <div>
                <button type="button" onclick="closeCoverLetterModal()" class="px-3 py-1.5 bg-red-900/20 text-red-400 hover:bg-red-900/40 text-[10px] font-semibold uppercase tracking-wider transition-colors border border-red-800/30">Close</button>
            </div>
        </div>

        <!-- Body -->
        <div class="viewer-body p-6 text-gray-300 text-sm leading-relaxed whitespace-pre-wrap font-sans min-h-[180px] max-h-[65vh] overflow-y-auto bg-[#0c0f15]">
            <p id="cl-viewer-body" class="italic"></p>
        </div>
    </div>
</div>

<script>
    function openCvModal(url, name, detailsUrl, downloadUrl) {
        document.getElementById('cv-modal-title').innerText = name;
        document.getElementById('cv-modal-details-btn').href = detailsUrl;
        document.getElementById('cv-modal-newtab-btn').href = url;
        document.getElementById('cv-modal-download-btn').href = downloadUrl;
        document.getElementById('cv-viewer-frame').src = url + '#zoom=100';
        document.getElementById('cv-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeCvModal() {
        document.getElementById('cv-modal').classList.add('hidden');
        document.getElementById('cv-viewer-frame').src = '';
        document.body.style.overflow = '';
    }

    function openCoverLetterModal(btn) {
        const name = btn.getAttribute('data-name');
        const text = btn.getAttribute('data-cover-letter');
        document.getElementById('cl-modal-title').innerText = name;
        document.getElementById('cl-viewer-body').innerText = text;
        document.getElementById('cl-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeCoverLetterModal() {
        document.getElementById('cl-modal').classList.add('hidden');
        document.getElementById('cl-viewer-body').innerText = '';
        document.body.style.overflow = '';
    }

    // Close modal on Escape press
    window.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeCvModal();
            closeCoverLetterModal();
        }
    });

    // Single action button handler
    const singleActionForm = document.getElementById('single-action-form');
    document.querySelectorAll('.single-action-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('data-url');
            const method = this.getAttribute('data-method') || 'POST';
            const confirmMsg = this.getAttribute('data-confirm');

            if (confirmMsg && !confirm(confirmMsg)) {
                return;
            }

            singleActionForm.action = url;
            let methodInput = singleActionForm.querySelector('input[name="_method"]');
            if (methodInput) {
                methodInput.value = method;
            }
            singleActionForm.submit();
        });
    });
</script>

<!-- Hidden single-action form to avoid nested form tags -->
<form id="single-action-form" action="" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="_method" value="POST">
</form>
@endsection
