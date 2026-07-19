@extends('layouts.admin')

@section('title', 'Manage Job Applications')
@section('page_title', 'Job Applications Dashboard')

@section('admin_content')
<div class="space-y-6">
    <!-- Filters and Search panel -->
    <div class="bg-[#111827] border border-gray-800 p-6 shadow-sm">
        <form action="{{ route('admin.applications.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Search Applicant</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, phone or ref ID" class="w-full bg-[#0c0f15] border border-gray-800 text-xs text-gray-200 px-3 py-2.5 focus:outline-none focus:border-[#C9A84C]">
                </div>

                <!-- Job Filter -->
                <div>
                    <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Job Opening</label>
                    <select name="career_id" class="w-full bg-[#0c0f15] border border-gray-800 text-xs text-gray-200 px-3 py-2.5 focus:outline-none focus:border-[#C9A84C]">
                        <option value="">All Jobs</option>
                        @foreach($careers as $job)
                            <option value="{{ $job->id }}" {{ request('career_id') == $job->id ? 'selected' : '' }}>{{ $job->title }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Status</label>
                    <select name="status" class="w-full bg-[#0c0f15] border border-gray-800 text-xs text-gray-200 px-3 py-2.5 focus:outline-none focus:border-[#C9A84C]">
                        <option value="">All Statuses</option>
                        <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
                        <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                        <option value="shortlisted" {{ request('status') === 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                        <option value="interview_scheduled" {{ request('status') === 'interview_scheduled' ? 'selected' : '' }}>Interview Scheduled</option>
                        <option value="selected" {{ request('status') === 'selected' ? 'selected' : '' }}>Selected</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="withdrawn" {{ request('status') === 'withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                    </select>
                </div>

                <!-- Department Filter -->
                <div>
                    <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Department</label>
                    <select name="department_id" class="w-full bg-[#0c0f15] border border-gray-800 text-xs text-gray-200 px-3 py-2.5 focus:outline-none focus:border-[#C9A84C]">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Location Filter -->
                <div>
                    <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Location</label>
                    <input type="text" name="location" value="{{ request('location') }}" placeholder="e.g. Gulshan" class="w-full bg-[#0c0f15] border border-gray-800 text-xs text-gray-200 px-3 py-2.5 focus:outline-none focus:border-[#C9A84C]">
                </div>

                <!-- Date Range -->
                <div>
                    <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">From Date</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full bg-[#0c0f15] border border-gray-800 text-xs text-gray-200 px-3 py-2.5 focus:outline-none focus:border-[#C9A84C]">
                </div>
                <div>
                    <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5">To Date</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full bg-[#0c0f15] border border-gray-800 text-xs text-gray-200 px-3 py-2.5 focus:outline-none focus:border-[#C9A84C]">
                </div>
            </div>

            <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-800/60">
                <!-- Sorting options -->
                <div class="flex items-center space-x-2">
                    <span class="text-[10px] font-mono text-gray-500 uppercase tracking-wider">Sort by:</span>
                    <select name="sort" onchange="this.form.submit()" class="bg-[#0c0f15] border border-gray-800 text-xs text-gray-400 px-2 py-1.5 focus:outline-none">
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest Submissions</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest Submissions</option>
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Applicant Name</option>
                    </select>
                </div>
                
                <div class="space-x-2">
                    <a href="{{ route('admin.applications.index') }}" class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-400 text-xs font-bold uppercase tracking-wider transition-colors">Clear Filters</a>
                    <button type="submit" class="px-5 py-2 bg-[#C9A84C] hover:bg-[#b8973f] text-black text-xs font-bold uppercase tracking-wider transition-colors">Apply Filters</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Bulk actions and Table Listings -->
    <form id="bulk-action-form" action="{{ route('admin.applications.bulk-action') }}" method="POST" onsubmit="return confirmBulkAction()">
        @csrf
        
        <div class="mb-4 bg-[#111827] border border-gray-800 p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-2 text-xs">
                <span id="checked-counter" class="font-bold text-[#C9A84C] font-mono bg-[#C9A84C]/10 border border-[#C9A84C]/20 px-2 py-0.5">0</span>
                <span class="text-gray-400">Applications selected</span>
            </div>
            
            <div class="flex items-center space-x-3 w-full sm:w-auto">
                <select name="action" id="bulk-action-select" required class="bg-[#0c0f15] border border-gray-800 text-xs text-gray-200 px-3 py-2 focus:outline-none focus:border-[#C9A84C] w-full sm:w-auto">
                    <option value="">Select Bulk Action</option>
                    <option value="download_cvs">Download Selected CVs (ZIP)</option>
                    <option value="export_csv">Export Selected to CSV</option>
                    <option value="under_review">Mark as Under Review</option>
                    <option value="shortlist">Shortlist Selected</option>
                    <option value="reject">Reject Selected</option>
                    <option value="delete">Soft Delete Selected</option>
                    <option value="restore">Restore Selected</option>
                    <option value="force_delete">PERMANENTLY Delete Selected</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-[#C9A84C] hover:text-black border border-[#C9A84C]/25 text-[#C9A84C] text-xs font-bold uppercase tracking-wider transition-all duration-300 whitespace-nowrap">
                    Execute Bulk
                </button>
            </div>
        </div>

        <div class="bg-[#111827] border border-gray-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#0c0f15] border-b border-gray-800 text-xs font-bold uppercase tracking-wider text-gray-500">
                            <th class="px-6 py-4 w-12 text-center">
                                <input type="checkbox" id="select-all-checkbox" class="h-4 w-4 bg-black border-gray-800 text-[#C9A84C] focus:ring-0 cursor-pointer">
                            </th>
                            <th class="px-6 py-4">Reference</th>
                            <th class="px-6 py-4">Applicant</th>
                            <th class="px-6 py-4">Job Opening</th>
                            <th class="px-6 py-4">Expected Salary</th>
                            <th class="px-6 py-4">Applied Date</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-300">
                        @forelse($applications as $app)
                            <tr class="border-b border-gray-800 hover:bg-gray-800/30 transition-colors {{ $app->trashed() ? 'opacity-50 bg-red-950/5' : '' }}">
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $app->id }}" class="bulk-item-checkbox h-4 w-4 bg-black border-gray-800 text-[#C9A84C] focus:ring-0 cursor-pointer">
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-white">
                                    {{ $app->reference_number }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-white">{{ $app->full_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $app->email }}</div>
                                    <div class="text-xs text-gray-500">{{ $app->phone }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-300">{{ $app->career->title }}</div>
                                    <div class="text-xs text-[#C9A84C] mt-0.5 uppercase tracking-wider font-semibold">{{ $app->career->department ? $app->career->department->name : 'N/A' }}</div>
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
                            <tr><td colspan="8" class="px-6 py-12 text-center text-gray-600">No applications matching the filters.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($applications->hasPages())
                <div class="bg-[#0c0f15] border-t border-gray-800 px-6 py-4">
                    {{ $applications->links() }}
                </div>
            @endif
        </div>
    </form>
</div>

<script>
    // Handle checking checkboxes
    const selectAll = document.getElementById('select-all-checkbox');
    const items = document.getElementsByClassName('bulk-item-checkbox');
    const checkedCounter = document.getElementById('checked-counter');

    selectAll.addEventListener('change', function() {
        for(let i=0; i < items.length; i++) {
            items[i].checked = this.checked;
        }
        updateCounter();
    });

    for(let i=0; i < items.length; i++) {
        items[i].addEventListener('change', function() {
            updateCounter();
        });
    }

    function updateCounter() {
        let count = 0;
        for(let i=0; i < items.length; i++) {
            if(items[i].checked) count++;
        }
        checkedCounter.innerText = count;
    }

    function confirmBulkAction() {
        const action = document.getElementById('bulk-action-select').value;
        const count = checkedCounter.innerText;

        if (count === '0') {
            alert('Please select at least one application first.');
            return false;
        }

        if (['delete', 'force_delete'].includes(action)) {
            return confirm(`Are you sure you want to execute bulk delete on ${count} applications?`);
        }

        return true;
    }

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
@endsection
