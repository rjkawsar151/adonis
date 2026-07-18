@extends('layouts.admin')

@section('title', 'Barbers')
@section('page_title', 'Barbers Management')

@section('admin_content')
<div class="flex items-center justify-between mb-8">
    <p class="text-sm text-gray-500">Manage master barbers / stylists.</p>
    <a href="{{ url('/admin/barbers/create') }}" class="inline-flex items-center px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-black bg-[#C9A84C] hover:bg-[#b8973f] transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Add Stylist
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @forelse($barbers as $barber)
        <div class="bg-[#111827] border border-gray-800 p-5 flex gap-5 hover:border-[#C9A84C]/30 transition-all">
            <div class="h-24 w-20 shrink-0 overflow-hidden bg-gray-900 border border-gray-700">
                @if($barber->portraitUrl)
                    <img src="{{ $barber->portraitUrl }}" alt="{{ $barber->name }}" class="h-full w-full object-cover grayscale">
                @else
                    <div class="h-full w-full flex items-center justify-center text-gray-600 text-xs">No Image</div>
                @endif
            </div>
            <div class="flex flex-col justify-between flex-1 min-w-0">
                <div>
                    <div class="flex items-start justify-between gap-1.5">
                        <h4 class="font-bold text-sm text-white truncate">{{ $barber->name }}</h4>
                        <span class="text-[10px] text-yellow-500 font-mono">★ {{ $barber->rating }}</span>
                    </div>
                    <p class="text-[11px] font-mono text-[#C9A84C] uppercase truncate mt-0.5">{{ $barber->specialty }}</p>
                    <p class="text-[11px] text-gray-500 mt-1 line-clamp-2">{{ $barber->bio }}</p>
                </div>
                <div class="flex items-center justify-between mt-3">
                    <span class="text-[10px] text-gray-600">{{ $barber->experienceYears }} years exp.</span>
                    <div class="flex gap-2">
                        <a href="{{ url('/admin/barbers/' . $barber->id . '/edit') }}" class="text-[10px] font-mono text-[#C9A84C] hover:underline">Edit</a>
                        <form action="{{ url('/admin/barbers/' . $barber->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this barber?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-[10px] font-mono text-red-400 hover:underline">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-16 text-gray-600 text-sm">No barbers yet.</div>
    @endforelse
</div>
@endsection
