@extends('layouts.admin')

@section('title', 'Add New User')
@section('page_title', 'Add Admin User')

@section('admin_content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-wide">Add Admin User</h1>
            <p class="text-xs text-gray-400 mt-1">Create a new administrative account with role-based permissions.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-xl text-xs font-semibold transition-all">
            ← Back to Users
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-[#111827] rounded-2xl border border-gray-800 p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">Full Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full bg-[#0c1017] text-gray-200 text-sm border border-gray-800 rounded-xl px-4 py-3 focus:outline-none focus:border-[#C9A84C]">
                @error('name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">Email Address <span class="text-red-400">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full bg-[#0c1017] text-gray-200 text-sm border border-gray-800 rounded-xl px-4 py-3 focus:outline-none focus:border-[#C9A84C]">
                @error('email')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">Password <span class="text-red-400">*</span></label>
                <input type="password" name="password" id="password" required minlength="8" class="w-full bg-[#0c1017] text-gray-200 text-sm border border-gray-800 rounded-xl px-4 py-3 focus:outline-none focus:border-[#C9A84C]" placeholder="Minimum 8 characters">
                @error('password')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role Selection -->
            <div>
                <label for="role" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">Assign Role <span class="text-red-400">*</span></label>
                <select name="role" id="role" required class="w-full bg-[#0c1017] text-gray-200 text-sm border border-gray-800 rounded-xl px-4 py-3 focus:outline-none focus:border-[#C9A84C]">
                    @foreach($roles as $roleKey => $roleLabel)
                        <option value="{{ $roleKey }}" {{ old('role') === $roleKey ? 'selected' : '' }}>
                            {{ $roleLabel }}
                        </option>
                    @endforeach
                </select>
                <p class="text-[11px] text-gray-500 mt-2">
                    Note: Content Editors only have access to Blog Management. HR Managers only have access to Recruitment modules.
                </p>
                @error('role')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Account Status -->
            <div>
                <label for="status" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">Account Status <span class="text-red-400">*</span></label>
                <select name="status" id="status" required class="w-full bg-[#0c1017] text-gray-200 text-sm border border-gray-800 rounded-xl px-4 py-3 focus:outline-none focus:border-[#C9A84C]">
                    <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active (User can log in)</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive (Access blocked)</option>
                </select>
                @error('status')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="pt-4 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-gray-800 hover:bg-gray-700 text-gray-300 text-xs font-semibold rounded-xl transition-all">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-[#C9A84C] hover:bg-[#b08d3c] text-black text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-lg">Save User</button>
            </div>
        </form>
    </div>
</div>
@endsection
