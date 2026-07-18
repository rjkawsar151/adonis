@extends('layouts.admin')

@section('title', 'Manage VIP Memberships')
@section('page_title', 'VIP Club Registrations')

@section('admin_content')
<!-- Filters -->
<div class="bg-[#111827] border border-gray-800 p-6 mb-6">
    <form method="GET" action="{{ url('/admin/memberships') }}" class="flex flex-col sm:flex-row gap-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, phone or email..." class="flex-grow px-4 py-2.5 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors placeholder-gray-600">
        <select name="status" class="px-4 py-2.5 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
            <option value="all" {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>All Statuses</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
            <option value="declined" {{ request('status') === 'declined' ? 'selected' : '' }}>Declined</option>
        </select>
        <button type="submit" class="px-5 py-2.5 bg-[#C9A84C] text-black text-sm font-bold hover:bg-[#b8973f] transition-all">Filter</button>
        <a href="{{ url('/admin/memberships') }}" class="px-5 py-2.5 border border-gray-700 text-gray-400 text-sm font-bold hover:bg-gray-800 transition-all text-center">Clear</a>
    </form>
</div>

<!-- Bulk action form -->
<form id="bulk-action-form" action="{{ route('admin.memberships.bulk-action') }}" method="POST" onsubmit="return confirmBulkAction()">
    @csrf
    
    <div class="mb-4 bg-[#111827] border border-gray-800 p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center space-x-2 text-xs">
            <span id="checked-counter" class="font-bold text-[#C9A84C] font-mono bg-[#C9A84C]/10 border border-[#C9A84C]/20 px-2 py-0.5">0</span>
            <span class="text-gray-400">Membership requests selected</span>
        </div>
        
        <div class="flex items-center space-x-3 w-full sm:w-auto">
            <select name="action" id="bulk-action-select" required class="bg-[#0c0f15] border border-gray-800 text-xs text-gray-200 px-3 py-2 focus:outline-none focus:border-[#C9A84C] w-full sm:w-auto">
                <option value="">Select Bulk Action</option>
                <option value="confirm">Confirm/Approve Selected</option>
                <option value="decline">Decline Selected</option>
                <option value="delete">Delete Selected</option>
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
                        <th class="px-6 py-4">Gentleman</th>
                        <th class="px-6 py-4">Phone</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Requested At</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-300">
                    @if(count($requests) > 0)
                        @foreach($requests as $req)
                            <tr class="border-b border-gray-800 hover:bg-gray-800/30 transition-colors">
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $req->id }}" class="bulk-item-checkbox h-4 w-4 bg-black border-gray-800 text-[#C9A84C] focus:ring-0 cursor-pointer">
                                </td>
                                <td class="px-6 py-4 font-bold text-white">{{ $req->name }}</td>
                                <td class="px-6 py-4 text-gray-400 font-mono">{{ $req->phone }}</td>
                                <td class="px-6 py-4 font-semibold text-gray-400">{{ $req->email }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-orange-900/30 text-orange-400 border border-orange-800/50',
                                            'confirmed' => 'bg-green-900/30 text-green-400 border border-green-800/50',
                                            'declined' => 'bg-red-900/30 text-red-400 border border-red-800/50',
                                        ];
                                        $col = $statusColors[$req->status] ?? 'bg-gray-800 text-gray-400';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-bold uppercase tracking-wider border {{ $col }}">
                                        {{ $req->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-500">{{ $req->created_at->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center space-x-2">
                                        <!-- Quick status confirmation -->
                                        @if($req->status !== 'confirmed')
                                            <button type="button" data-url="{{ route('admin.memberships.status', $req->id) }}" data-method="PUT" data-status="confirmed" title="Confirm/Approve Invitation" class="single-action-btn inline-flex items-center justify-center w-8 h-8 bg-green-950/40 text-green-400 hover:bg-green-600 hover:text-black border border-green-800/50 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        @endif

                                        @if($req->status !== 'declined')
                                            <button type="button" data-url="{{ route('admin.memberships.status', $req->id) }}" data-method="PUT" data-status="declined" title="Decline Invitation" class="single-action-btn inline-flex items-center justify-center w-8 h-8 bg-red-950/40 text-red-400 hover:bg-red-600 hover:text-black border border-red-800/50 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        @endif

                                        <!-- Delete request -->
                                        <button type="button" data-url="{{ route('admin.memberships.destroy', $req->id) }}" data-method="DELETE" data-confirm="Are you sure you want to delete this membership request?" title="Delete Request" class="single-action-btn inline-flex items-center justify-center w-8 h-8 bg-gray-800 text-gray-400 hover:bg-[#C9A84C] hover:text-black border border-gray-700 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-600">No VIP membership requests found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-800 text-gray-400 text-sm">
            {{ $requests->withQueryString()->links() }}
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const selectAll = document.getElementById('select-all-checkbox');
        const items = document.getElementsByClassName('bulk-item-checkbox');
        const checkedCounter = document.getElementById('checked-counter');

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                for (let i = 0; i < items.length; i++) {
                    items[i].checked = selectAll.checked;
                }
                updateCounter();
            });
        }

        for (let i = 0; i < items.length; i++) {
            items[i].addEventListener('change', function() {
                updateCounter();
            });
        }

        function updateCounter() {
            let count = 0;
            for (let i = 0; i < items.length; i++) {
                if (items[i].checked) count++;
            }
            checkedCounter.innerText = count;
        }
    });

    function confirmBulkAction() {
        const selectAction = document.getElementById('bulk-action-select');
        const action = selectAction.value;
        const checkedCounter = document.getElementById('checked-counter');
        const count = checkedCounter.innerText;

        if (!action) {
            alert('Please select a bulk action first.');
            return false;
        }

        if (count === '0') {
            alert('Please select at least one guest profile first.');
            return false;
        }

        if (action === 'delete') {
            return confirm(`Are you sure you want to permanently delete the ${count} selected membership requests?`);
        }

        return confirm(`Are you sure you want to mark the ${count} selected membership requests as ${action}ed?`);
    }
</script>

<!-- Hidden single-action form to avoid nested form tags -->
<form id="single-action-form" action="" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="_method" value="POST">
    <input type="hidden" name="status" id="single-action-status" value="">
</form>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const singleForm = document.getElementById('single-action-form');
        const statusInput = document.getElementById('single-action-status');

        document.querySelectorAll('.single-action-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('data-url');
                const method = this.getAttribute('data-method') || 'POST';
                const status = this.getAttribute('data-status');
                const confirmMsg = this.getAttribute('data-confirm');

                if (confirmMsg && !confirm(confirmMsg)) {
                    return;
                }

                singleForm.action = url;
                
                let methodInput = singleForm.querySelector('input[name="_method"]');
                if (methodInput) {
                    methodInput.value = method;
                }

                if (status) {
                    statusInput.value = status;
                } else {
                    statusInput.value = '';
                }

                singleForm.submit();
            });
        });
    });
</script>
@endsection
