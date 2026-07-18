@extends('layouts.admin')

@section('title', 'Manage Job Listings')
@section('page_title', 'Job Postings Directory')

@section('admin_content')
<div class="mb-6 flex justify-between items-center">
    <p class="text-sm text-gray-500">Add, edit, or close careers at Adonis Men's Grooming Lounges.</p>
    <a href="{{ route('admin.careers.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-black bg-[#C9A84C] hover:bg-[#b8973f] shadow-md transition-all duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
        Add New Job
    </a>
</div>

<div class="bg-[#111827] border border-gray-800 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#0c0f15] border-b border-gray-800 text-xs font-bold uppercase tracking-wider text-gray-500">
                    <th class="px-6 py-4">Title</th>
                    <th class="px-6 py-4">Department & Type</th>
                    <th class="px-6 py-4">Vacancy & Location</th>
                    <th class="px-6 py-4">Stats</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-300">
                @forelse($careers as $job)
                    <tr class="border-b border-gray-800 hover:bg-gray-800/30 transition-colors {{ $job->trashed() ? 'opacity-50 bg-red-950/5' : '' }}">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-white">{{ $job->title }}</div>
                            <div class="text-xs text-gray-500 font-mono mt-0.5">/career/{{ $job->slug }}</div>
                            @if($job->is_featured)
                                <span class="text-[9px] bg-yellow-400/10 text-yellow-400 border border-yellow-400/20 px-1.5 py-0.5 uppercase tracking-wider font-bold mt-1 inline-block">Featured</span>
                            @endif
                            @if($job->trashed())
                                <span class="text-[9px] bg-red-900/30 text-red-400 border border-red-800/50 px-1.5 py-0.5 uppercase tracking-wider font-bold mt-1 inline-block">Deleted</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div>{{ $job->department ? $job->department->name : 'N/A' }}</div>
                            <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider mt-0.5">{{ $job->employmentType ? $job->employmentType->name : 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div>{{ $job->location }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">Vacancy: {{ $job->vacancy }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.applications.index', ['career_id' => $job->id]) }}" class="text-xs font-mono bg-gray-800 px-2.5 py-1.5 border border-gray-700/50 text-gray-300 hover:text-white hover:bg-gray-700 block text-center max-w-[150px]">
                                Applied: {{ $job->applications_count }}
                                <div class="text-[9px] text-gray-500 mt-0.5 font-sans">
                                    Shortlist: {{ $job->shortlisted_count }} | Reject: {{ $job->rejected_count }}
                                </div>
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            @if($job->status === 'active')
                                <span class="text-xs font-bold bg-green-900/30 text-green-400 px-2.5 py-1 border border-green-800/50 uppercase">Active</span>
                            @elseif($job->status === 'closed')
                                <span class="text-xs font-bold bg-red-900/30 text-red-400 px-2.5 py-1 border border-red-800/50 uppercase">Closed</span>
                            @elseif($job->status === 'inactive')
                                <span class="text-xs font-bold bg-gray-800 text-gray-400 px-2.5 py-1 uppercase">Inactive</span>
                            @else
                                <span class="text-xs font-bold bg-yellow-900/30 text-yellow-400 px-2.5 py-1 border border-yellow-800/30 uppercase">Draft</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-1.5">
                            @if(!$job->trashed())
                                <a href="{{ route('admin.careers.show', $job->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-gray-800 text-gray-400 hover:bg-[#C9A84C] hover:text-black transition-colors" title="View Details">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </a>
                                <a href="{{ route('admin.careers.edit', $job->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-gray-800 text-gray-400 hover:bg-blue-600 hover:text-white transition-colors" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </a>
                                <form action="{{ route('admin.careers.destroy', $job->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Soft-delete this job posting? (Applications will remain intact)')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-gray-800 text-gray-400 hover:bg-red-600 hover:text-white transition-colors" title="Soft Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.careers.restore', $job->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-gray-800 text-green-400 hover:bg-green-600 hover:text-white transition-colors" title="Restore">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18.5" /></svg>
                                    </button>
                                </form>
                                <form action="{{ route('admin.careers.force-delete', $job->id) }}" method="POST" class="inline-block" onsubmit="return confirm('PERMANENTLY delete this job posting? This cannot be undone!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-gray-800 text-red-500 hover:bg-red-700 hover:text-white transition-colors" title="Permanently Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-600">No job postings found. Click "Add New Job" to create one.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
