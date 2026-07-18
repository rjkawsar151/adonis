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
@endsection
