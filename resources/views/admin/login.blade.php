<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Mayfair Wellness Clinic</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2 { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-[#F4FAF8] min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white border border-[#EEF7F4] p-8 sm:p-10 rounded-[32px] shadow-xl">
        <div class="text-center">
            <!-- Medical Cross SVG icon -->
            <div class="mx-auto w-12 h-12 rounded-full bg-[#006F5C] flex items-center justify-center text-white shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-[#111827]">Admin Login</h2>
            <p class="mt-2 text-xs text-[#6B7280]">Mayfair Men's Health Service Portal</p>
        </div>

        @if(session('error'))
            <div class="p-4 bg-[#FDF2F5] text-[#CC205C] rounded-2xl text-xs border border-[#CC205C]/20 text-center">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="p-4 bg-[#EEF7F4] text-[#006F5C] rounded-2xl text-xs border border-[#006F5C]/20 text-center">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 bg-[#FDF2F5] text-[#CC205C] rounded-2xl text-xs border border-[#CC205C]/20">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ url('/admin/login') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Email Address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required class="appearance-none rounded-full relative block w-full px-4 py-3 border border-[#EEF7F4] bg-[#F4FAF8] text-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#006F5C] focus:border-[#006F5C] transition-colors" placeholder="admin@mayfair.com.bd" value="{{ old('email') }}">
                </div>
                <div>
                    <label for="password" class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none rounded-full relative block w-full px-4 py-3 border border-[#EEF7F4] bg-[#F4FAF8] text-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#006F5C] focus:border-[#006F5C] transition-colors" placeholder="••••••••">
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-full text-white bg-[#006F5C] hover:bg-[#005547] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#006F5C] shadow-md hover:shadow-lg transition-all duration-200">
                    Sign In to Portal
                </button>
            </div>
        </form>
    </div>
</body>
</html>
