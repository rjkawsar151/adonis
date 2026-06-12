@extends('layouts.admin')

@section('title', 'Website Settings')
@section('page_title', 'Website & Footer Settings')

@section('admin_content')
@if($errors->any())
    <div class="mb-6 p-4 bg-[#FDF2F5] text-[#CC205C] rounded-2xl text-sm border border-[#CC205C]/20">
        <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form action="{{ url('/admin/settings') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
    @csrf
    @method('PUT')

    <!-- Site Settings -->
    <div class="bg-white border border-[#EEF7F4] rounded-[24px] p-8 shadow-sm space-y-6">
        <h3 class="font-extrabold text-lg text-[#111827] border-b border-[#EEF7F4] pb-4">General Site Settings</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Site Name *</label>
                <input type="text" name="site_name" value="{{ old('site_name', $settings->site_name ?? 'Mayfair Wellness Clinic') }}" required class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">WhatsApp Number</label>
                <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $settings->whatsapp_number ?? '') }}" placeholder="+8801986660000" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Appointment Button Text *</label>
                <input type="text" name="appointment_button_text" value="{{ old('appointment_button_text', $settings->appointment_button_text ?? 'Appointment Now') }}" required class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Appointment Button URL *</label>
                <input type="text" name="appointment_button_url" value="{{ old('appointment_button_url', $settings->appointment_button_url ?? '#booking-form') }}" required class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
            </div>
        </div>

        @if(isset($settings) && $settings->hero_image)
            <div class="flex items-center space-x-4">
                <img src="{{ asset($settings->hero_image) }}" alt="Hero" class="h-20 object-cover border border-[#EEF7F4] rounded-xl p-1">
                <span class="text-xs text-[#6B7280]">Current hero background image.</span>
            </div>
        @endif
        <div>
            <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Hero Section Background Image (1920x600 recommended)</label>
            <input type="file" name="hero_image" accept="image/*" class="block w-full text-sm text-[#6B7280] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-[#EEF7F4] file:text-[#006F5C] hover:file:bg-[#006F5C] hover:file:text-white transition-all">
        </div>

        @if(isset($settings) && $settings->logo)
            <div class="flex items-center space-x-4">
                <img src="{{ asset($settings->logo) }}" alt="Logo" class="h-12 object-contain border border-[#EEF7F4] rounded-xl p-1">
                <span class="text-xs text-[#6B7280]">Current logo. Upload new to replace.</span>
            </div>
        @endif
        <div>
            <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Upload Site Logo</label>
            <input type="file" name="logo" accept="image/*" class="block w-full text-sm text-[#6B7280] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-[#EEF7F4] file:text-[#006F5C] hover:file:bg-[#006F5C] hover:file:text-white transition-all">
        </div>
    </div>

    <!-- SMTP Settings -->
    <div class="bg-white border border-[#EEF7F4] rounded-[24px] p-8 shadow-sm space-y-6">
        <h3 class="font-extrabold text-lg text-[#111827] border-b border-[#EEF7F4] pb-4">SMTP Email Settings</h3>
        <p class="text-xs text-[#6B7280] -mt-4">Configure SMTP to send email notifications when appointments are booked.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">SMTP Host</label>
                <input type="text" name="smtp_host" value="{{ old('smtp_host', $settings->smtp_host ?? '') }}" placeholder="smtp.gmail.com" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">SMTP Port</label>
                <input type="text" name="smtp_port" value="{{ old('smtp_port', $settings->smtp_port ?? '') }}" placeholder="587" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">SMTP Username</label>
                <input type="text" name="smtp_username" value="{{ old('smtp_username', $settings->smtp_username ?? '') }}" placeholder="your@email.com" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">SMTP Password</label>
                <input type="password" name="smtp_password" value="{{ old('smtp_password', $settings->smtp_password ?? '') }}" placeholder="App password" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">SMTP Encryption</label>
                <select name="smtp_encryption" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors appearance-none">
                    <option value="">None</option>
                    <option value="tls" {{ old('smtp_encryption', $settings->smtp_encryption ?? '') == 'tls' ? 'selected' : '' }}>TLS</option>
                    <option value="ssl" {{ old('smtp_encryption', $settings->smtp_encryption ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">From Email Address</label>
                <input type="email" name="smtp_mail_to" value="{{ old('smtp_mail_to', $settings->smtp_mail_to ?? 'info@mayfair.com.bd') }}" placeholder="info@mayfair.com.bd" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Notification Recipients (comma-separated emails)</label>
            <input type="text" name="notification_emails" value="{{ old('notification_emails', $settings->notification_emails ?? '') }}" placeholder="admin@mayfair.com.bd, info@mayfair.com.bd" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
            <p class="text-xs text-[#6B7280] mt-1">These emails will receive notifications when a new appointment is booked.</p>
        </div>
    </div>

    <!-- Footer Settings -->
    <div class="bg-white border border-[#EEF7F4] rounded-[24px] p-8 shadow-sm space-y-6">
        <h3 class="font-extrabold text-lg text-[#111827] border-b border-[#EEF7F4] pb-4">Footer Settings</h3>

        <div>
            <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Footer Description</label>
            <textarea name="description" rows="2" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors">{{ old('description', $footer->description ?? '') }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Office Address</label>
                <input type="text" name="address" value="{{ old('address', $footer->address ?? '') }}" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $footer->phone ?? '') }}" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', $footer->email ?? '') }}" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Copyright Text</label>
                <input type="text" name="copyright_text" value="{{ old('copyright_text', $footer->copyright_text ?? '') }}" class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Facebook URL</label>
                <input type="text" name="facebook_url" value="{{ old('facebook_url', $footer->facebook_url ?? '') }}" placeholder="https://facebook.com/..." class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Instagram URL</label>
                <input type="text" name="instagram_url" value="{{ old('instagram_url', $footer->instagram_url ?? '') }}" placeholder="https://instagram.com/..." class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">LinkedIn URL</label>
                <input type="text" name="linkedin_url" value="{{ old('linkedin_url', $footer->linkedin_url ?? '') }}" placeholder="https://linkedin.com/..." class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Pinterest URL</label>
                <input type="text" name="pinterest_url" value="{{ old('pinterest_url', $footer->pinterest_url ?? '') }}" placeholder="https://pinterest.com/..." class="w-full px-4 py-3 bg-[#F4FAF8] border border-[#EEF7F4] rounded-2xl text-sm focus:outline-none focus:border-[#006F5C] transition-colors">
            </div>
        </div>
    </div>

    <div class="flex space-x-4">
        <button type="submit" class="inline-flex items-center justify-center px-8 py-3.5 text-sm font-bold rounded-full text-white bg-[#006F5C] hover:bg-[#005547] shadow-md transition-all">
            Save All Settings
        </button>
    </div>
</form>
@endsection
