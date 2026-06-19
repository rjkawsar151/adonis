@extends('layouts.admin')

@section('title', 'Website Settings')
@section('page_title', 'Website & Footer Settings')

@section('admin_content')
@if($errors->any())
    <div class="mb-6 p-4 bg-red-900/30 text-red-400 text-sm border border-red-800/50">
        <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form action="{{ url('/admin/settings') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
    @csrf
    @method('PUT')

    <!-- Site Settings -->
    <div class="bg-[#111827] border border-gray-800 p-8 space-y-6">
        <h3 class="font-extrabold text-lg text-white border-b border-gray-800 pb-4">General Site Settings</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Site Name *</label>
                <input type="text" name="site_name" value="{{ old('site_name', $settings->site_name ?? 'Adonis Men\'s Grooming') }}" required class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">WhatsApp Number</label>
                <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $settings->whatsapp_number ?? '') }}" placeholder="+8801919700800" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Appointment Button Text *</label>
                <input type="text" name="appointment_button_text" value="{{ old('appointment_button_text', $settings->appointment_button_text ?? 'Book Now') }}" required class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Appointment Button URL *</label>
                <input type="text" name="appointment_button_url" value="{{ old('appointment_button_url', $settings->appointment_button_url ?? '#booking-section') }}" required class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
            </div>
        </div>

        @if(isset($settings) && $settings->hero_image)
            <div class="flex items-center space-x-4">
                <img src="{{ asset($settings->hero_image) }}" alt="Hero" class="h-20 object-cover border border-gray-800">
                <span class="text-xs text-gray-500">Current hero background image.</span>
            </div>
        @endif
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Hero Section Background Image</label>
            <input type="file" name="hero_image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-bold file:bg-gray-800 file:text-[#C9A84C] hover:file:bg-[#C9A84C] hover:file:text-black transition-all">
        </div>

        @if(isset($settings) && $settings->logo)
            <div class="flex items-center space-x-4">
                <img src="{{ asset($settings->logo) }}" alt="Logo" class="h-12 object-contain border border-gray-800">
                <span class="text-xs text-gray-500">Current logo. Upload new to replace.</span>
            </div>
        @endif
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Upload Site Logo</label>
            <input type="file" name="logo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-bold file:bg-gray-800 file:text-[#C9A84C] hover:file:bg-[#C9A84C] hover:file:text-black transition-all">
        </div>
    </div>

    <!-- SMTP Settings -->
    <div class="bg-[#111827] border border-gray-800 p-8 space-y-6">
        <h3 class="font-extrabold text-lg text-white border-b border-gray-800 pb-4">SMTP Email Settings</h3>
        <p class="text-xs text-gray-500 -mt-4">Configure SMTP to send email notifications when appointments are booked.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">SMTP Host</label>
                <input type="text" name="smtp_host" value="{{ old('smtp_host', $settings->smtp_host ?? '') }}" placeholder="smtp.gmail.com" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">SMTP Port</label>
                <input type="text" name="smtp_port" value="{{ old('smtp_port', $settings->smtp_port ?? '') }}" placeholder="587" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">SMTP Username</label>
                <input type="text" name="smtp_username" value="{{ old('smtp_username', $settings->smtp_username ?? '') }}" placeholder="your@email.com" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">SMTP Password</label>
                <input type="password" name="smtp_password" value="{{ old('smtp_password', $settings->smtp_password ?? '') }}" placeholder="App password" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">SMTP Encryption</label>
                <select name="smtp_encryption" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
                    <option value="">None</option>
                    <option value="tls" {{ old('smtp_encryption', $settings->smtp_encryption ?? '') == 'tls' ? 'selected' : '' }}>TLS</option>
                    <option value="ssl" {{ old('smtp_encryption', $settings->smtp_encryption ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">From Email Address</label>
                <input type="email" name="smtp_mail_to" value="{{ old('smtp_mail_to', $settings->smtp_mail_to ?? 'info@adonis.com.bd') }}" placeholder="info@adonis.com.bd" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Notification Recipients (comma-separated emails)</label>
            <input type="text" name="notification_emails" value="{{ old('notification_emails', $settings->notification_emails ?? '') }}" placeholder="admin@adonis.com.bd, info@adonis.com.bd" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
            <p class="text-xs text-gray-500 mt-1">These emails will receive notifications when a new appointment is booked.</p>
        </div>

        <div>
            <button type="button" id="test-smtp-btn" class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-black bg-[#C9A84C] hover:bg-[#b8973f] transition-colors">
                Send Test Email
            </button>
            <span id="test-smtp-result" class="text-xs ml-4"></span>
        </div>
    </div>

    <script>
    document.getElementById('test-smtp-btn')?.addEventListener('click', async function() {
        const btn = this;
        const result = document.getElementById('test-smtp-result');
        btn.disabled = true;
        btn.textContent = 'Sending...';
        result.textContent = '';
        result.className = 'text-xs ml-4';
        try {
            const res = await fetch('/api/smtp/test', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' } });
            const data = await res.json();
            result.textContent = data.success ? '✓ Test email sent!' : '✗ ' + (data.message || 'Failed');
            result.className = 'text-xs ml-4 ' + (data.success ? 'text-green-400' : 'text-red-400');
        } catch(e) {
            result.textContent = '✗ Connection error';
            result.className = 'text-xs ml-4 text-red-400';
        }
        btn.disabled = false;
        btn.textContent = 'Send Test Email';
    });
    </script>

    <!-- Footer Settings -->
    <div class="bg-[#111827] border border-gray-800 p-8 space-y-6">
        <h3 class="font-extrabold text-lg text-white border-b border-gray-800 pb-4">Footer Settings</h3>

        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Footer Description</label>
            <textarea name="description" rows="2" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">{{ old('description', $footer->description ?? '') }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Office Address</label>
                <input type="text" name="address" value="{{ old('address', $footer->address ?? '') }}" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $footer->phone ?? '') }}" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', $footer->email ?? '') }}" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Copyright Text</label>
                <input type="text" name="copyright_text" value="{{ old('copyright_text', $footer->copyright_text ?? '') }}" class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Facebook URL</label>
                <input type="text" name="facebook_url" value="{{ old('facebook_url', $footer->facebook_url ?? '') }}" placeholder="https://facebook.com/..." class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Instagram URL</label>
                <input type="text" name="instagram_url" value="{{ old('instagram_url', $footer->instagram_url ?? '') }}" placeholder="https://instagram.com/..." class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">LinkedIn URL</label>
                <input type="text" name="linkedin_url" value="{{ old('linkedin_url', $footer->linkedin_url ?? '') }}" placeholder="https://linkedin.com/..." class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Pinterest URL</label>
                <input type="text" name="pinterest_url" value="{{ old('pinterest_url', $footer->pinterest_url ?? '') }}" placeholder="https://pinterest.com/..." class="w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C] transition-colors">
            </div>
        </div>
    </div>

    <div class="flex space-x-4">
        <button type="submit" class="inline-flex items-center justify-center px-8 py-3.5 text-sm font-bold tracking-widest uppercase text-black bg-[#C9A84C] hover:bg-[#b8973f] shadow-md transition-all">
            Save All Settings
        </button>
    </div>
</form>
@endsection
