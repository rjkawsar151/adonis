@extends('layouts.admin')

@section('title', 'Admin Users')
@section('page_title', 'Admin Users Management')

@section('admin_content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-wide">Admin Users</h1>
            <p class="text-xs text-gray-400 mt-1">Manage user accounts and role-based access permissions.</p>
        </div>
        <div>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2.5 bg-[#C9A84C] hover:bg-[#b08d3c] text-black font-semibold text-xs tracking-wider uppercase rounded-xl transition-all shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Add New User
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-xl text-xs">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-red-500/10 border border-red-500/30 text-red-400 rounded-xl text-xs">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filters & Search -->
    <div class="bg-[#111827] p-5 rounded-2xl border border-gray-800">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4">
            <div class="sm:col-span-6 md:col-span-5">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="w-full bg-[#0c1017] text-gray-200 text-xs border border-gray-800 rounded-xl px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
            </div>
            <div class="sm:col-span-4 md:col-span-4">
                <select name="role" class="w-full bg-[#0c1017] text-gray-200 text-xs border border-gray-800 rounded-xl px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
                    <option value="">All Roles</option>
                    <option value="super_admin" {{ request('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="hr" {{ request('role') === 'hr' ? 'selected' : '' }}>HR Manager</option>
                    <option value="content_editor" {{ request('role') === 'content_editor' ? 'selected' : '' }}>Content Editor</option>
                    <option value="reception" {{ request('role') === 'reception' ? 'selected' : '' }}>Receptionist</option>
                </select>
            </div>
            <div class="sm:col-span-2 md:col-span-3 flex items-center gap-2">
                <button type="submit" class="px-5 py-2.5 bg-gray-800 hover:bg-gray-700 text-white text-xs font-semibold rounded-xl transition-all">Filter</button>
                @if(request('search') || request('role'))
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 bg-gray-900 hover:bg-gray-800 text-gray-400 text-xs rounded-xl transition-all">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-[#111827] rounded-2xl border border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-300">
                <thead class="bg-[#0c1017] text-gray-400 uppercase text-[10px] tracking-wider border-b border-gray-800">
                    <tr>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Created Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/60">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-800/40 transition-colors">
                            <td class="px-6 py-4 font-semibold text-white">
                                {{ $user->name }}
                                @if(Auth::id() === $user->id)
                                    <span class="ml-2 text-[9px] bg-gold-400/20 text-[#C9A84C] px-2 py-0.5 rounded-full border border-[#C9A84C]/30">You</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-400 font-mono">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @if($user->role === 'super_admin')
                                    <span class="px-2.5 py-1 bg-purple-500/10 text-purple-400 border border-purple-500/30 rounded-lg text-[10px] font-bold uppercase tracking-wider">Super Admin</span>
                                @elseif($user->role === 'admin')
                                    <span class="px-2.5 py-1 bg-blue-500/10 text-blue-400 border border-blue-500/30 rounded-lg text-[10px] font-bold uppercase tracking-wider">Admin</span>
                                @elseif($user->role === 'hr')
                                    <span class="px-2.5 py-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 rounded-lg text-[10px] font-bold uppercase tracking-wider">HR Manager</span>
                                @elseif($user->role === 'content_editor')
                                    <span class="px-2.5 py-1 bg-amber-500/10 text-amber-400 border border-amber-500/30 rounded-lg text-[10px] font-bold uppercase tracking-wider">Content Editor</span>
                                @elseif($user->role === 'reception')
                                    <span class="px-2.5 py-1 bg-pink-500/10 text-pink-400 border border-pink-500/30 rounded-lg text-[10px] font-bold uppercase tracking-wider">Receptionist</span>
                                @else
                                    <span class="px-2.5 py-1 bg-gray-500/10 text-gray-400 border border-gray-500/30 rounded-lg text-[10px] font-bold uppercase tracking-wider">{{ $user->role }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($user->status === 'active')
                                    <span class="inline-flex items-center text-emerald-400 text-[10px] font-semibold"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5"></span> Active</span>
                                @else
                                    <span class="inline-flex items-center text-red-400 text-[10px] font-semibold"><span class="w-1.5 h-1.5 rounded-full bg-red-400 mr-1.5"></span> Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-400 font-mono text-[11px]">
                                {{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="px-3 py-1.5 bg-gray-800 hover:bg-gray-700 text-gray-200 hover:text-white rounded-lg text-xs font-medium transition-colors">Edit</a>
                                
                                @if(Auth::id() !== $user->id && (!$user->isSuperAdmin() || Auth::user()->isSuperAdmin()))
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete user {{ $user->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/20 rounded-lg text-xs font-medium transition-colors">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 font-mono">
                                No admin users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-800">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
