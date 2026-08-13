@extends('layouts.admin')

@section('title', 'Offers')
@section('page_title', 'Offers & Packages')

@section('admin_content')
<div class="flex items-center justify-between mb-8">
    <p class="text-sm text-gray-500">Manage promotional offers &amp; packages shown on the public Offers page.</p>
    <a href="{{ url('/admin/offers/create') }}" class="inline-flex items-center px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-black bg-[#C9A84C] hover:bg-[#b8973f] transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Add Offer
    </a>
</div>

@php
    $branchLabels = ['all' => 'All Branches', 'gulshan' => 'Gulshan', 'bashundhara' => 'Bashundhara'];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @forelse($offers as $offer)
        <div class="bg-[#111827] border border-gray-800 p-5 flex flex-col gap-4 hover:border-[#C9A84C]/30 transition-all">
            <div class="flex items-start gap-4">
                @if($offer->image)
                    <div class="h-24 w-28 shrink-0 overflow-hidden bg-gray-900 border border-gray-700">
                        <img src="{{ str_starts_with($offer->image, 'http') ? $offer->image : asset($offer->image) }}" alt="{{ $offer->title }}" class="h-full w-full object-cover">
                    </div>
                @else
                    <div class="h-24 w-28 shrink-0 flex items-center justify-center text-gray-600 text-[10px] font-mono uppercase border border-gray-700 bg-gray-900">
                        No Image
                    </div>
                @endif
                <div class="flex flex-col gap-1.5 min-w-0 flex-1">
                    <h4 class="font-bold text-sm text-white leading-snug">{{ $offer->title }}</h4>
                    @if($offer->subtitle)
                        <p class="text-[10px] font-mono text-[#C9A84C] uppercase tracking-widest">{{ $offer->subtitle }}</p>
                    @endif
                    <div class="flex items-center gap-2 flex-wrap">
                        @if($offer->badge)
                            <span class="px-2 py-0.5 text-[8px] font-mono font-bold uppercase tracking-widest bg-gold-400 bg-[#C9A84C] text-black">{{ $offer->badge }}</span>
                        @endif
                        <span class="px-2 py-0.5 text-[8px] font-mono uppercase tracking-widest border border-[#32BBED]/40 text-[#32BBED]">{{ $branchLabels[$offer->branch] ?? $offer->branch }}</span>
                        @if($offer->discount_percent)
                            <span class="px-2 py-0.5 text-[8px] font-mono font-bold tracking-widest bg-red-500/15 text-red-400 border border-red-500/20">-{{ $offer->discount_percent }}%</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex items-end justify-between gap-2">
                <div>
                    @if($offer->discounted_price)
                        <span class="font-serif text-lg text-[#C9A84C] font-bold">৳{{ number_format($offer->discounted_price) }}</span>
                    @endif
                    @if($offer->original_price)
                        <span class="font-mono text-xs text-gray-500 line-through ml-2">৳{{ number_format($offer->original_price) }}</span>
                    @endif
                </div>
                <span class="text-[10px] font-mono uppercase tracking-widest {{ $offer->is_active ? 'text-green-400' : 'text-gray-600' }}">
                    {{ $offer->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            @if($offer->valid_until)
                <p class="text-[9px] font-mono tracking-widest text-gray-500 uppercase">Valid Until: <span class="text-gray-400">{{ $offer->valid_until }}</span></p>
            @endif

            <div class="flex items-center justify-between pt-3 border-t border-gray-800">
                <span class="text-[10px] text-gray-600">#{{ $offer->sort_order }} · {{ $offer->icon }}</span>
                <div class="flex gap-3">
                    <a href="{{ url('/admin/offers/' . $offer->id . '/edit') }}" class="text-[10px] font-mono text-[#C9A84C] hover:underline">Edit</a>
                    <form action="{{ url('/admin/offers/' . $offer->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this offer?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-[10px] font-mono text-red-400 hover:underline">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-16 text-gray-600 text-sm">No offers yet. Click "Add Offer" to create one.</div>
    @endforelse
</div>
@endsection
