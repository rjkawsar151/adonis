<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Adonis Men's Grooming</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/build/assets/app.css">
    <script type="module" src="/build/assets/app.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Outfit', sans-serif; }
        .login-gradient {
            background: linear-gradient(135deg, #0c0f15 0%, #1a1f2e 50%, #0c0f15 100%);
        }
        .gold-accent { color: #C9A84C; }
        .gold-border { border-color: #C9A84C; }
        .gold-bg { background: #C9A84C; }
        .gold-glow { box-shadow: 0 0 20px rgba(201, 168, 76, 0.15); }
    </style>
</head>
<body class="login-gradient min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-[#111827] border border-[#C9A84C]/30 p-8 sm:p-10 shadow-2xl gold-glow" style="border-radius: 4px;">
        <div class="text-center">
            <div class="mx-auto w-16 h-16 flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 gold-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
            </div>
            <h2 class="text-3xl font-extrabold text-white tracking-wider">ADMIN</h2>
            <p class="mt-2 text-xs text-[#C9A84C] uppercase tracking-[0.2em] font-semibold">Adonis Men's Grooming</p>
        </div>

        @if(session('error'))
            <div class="p-4 bg-red-900/30 text-red-400 text-xs border border-red-800/50 text-center">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="p-4 bg-green-900/30 text-green-400 text-xs border border-green-800/50 text-center">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 bg-red-900/30 text-red-400 text-xs border border-red-800/50">
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
                    <label for="email" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Email Address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required class="appearance-none relative block w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-[#C9A84C] focus:ring-1 focus:ring-[#C9A84C] transition-colors" placeholder="admin@adonis.com.bd" value="{{ old('email') }}">
                </div>
                <div>
                    <label for="password" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none relative block w-full px-4 py-3 bg-[#1a1f2e] border border-gray-700 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-[#C9A84C] focus:ring-1 focus:ring-[#C9A84C] transition-colors" placeholder="••••••••">
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3.5 px-4 border border-[#C9A84C] text-sm font-bold tracking-widest uppercase text-black gold-bg hover:bg-[#b8973f] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#111827] focus:ring-[#C9A84C] shadow-lg hover:shadow-[#C9A84C]/20 transition-all duration-200">
                    Sign In to Admin
                </button>
            </div>
        </form>
        <div class="text-center mt-4">
            <a href="{{ url('/') }}" class="text-xs text-gray-600 hover:text-[#C9A84C] transition-colors uppercase tracking-wider">
                ← Back to Website
            </a>
        </div>
    </div>
</body>
</html>
