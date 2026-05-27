<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings Key</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/RS-Wava-Husada.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .input-box {
            box-shadow: 4px 4px 0px 0px rgba(0,0,0,0.1);
        }
        /* Ensure sidebar and other elements are hidden if they exist in DOM */
        #sidebar, .sidebar-nav, header, nav:not(.auth-nav) {
            display: none !important;
        }
    </style>
</head>
<body class="bg-white min-h-screen flex flex-col items-center justify-center relative z-[9999]">
    @include('partials.watermark')
    <!-- Force hide everything else -->
    <style>
        aside, nav, header, footer, .sidebar-nav, #sidebar { display: none !important; }
    </style>
    <div class="fixed inset-0 bg-white -z-10"></div>
    <div class="w-full max-w-[320px] text-center">
        <h1 class="text-xl mb-4 font-normal text-black">Input Setting Key</h1>
        
        <form action="{{ route('settings.auth') }}" method="POST">
            @csrf
            <input type="password" name="key" autofocus required
                class="w-full h-12 px-4 border border-black focus:outline-none input-box text-center tracking-[0.5em]"
                autocomplete="off">
            
            @if(session('error'))
                <p class="text-red-500 text-xs mt-3">{{ session('error') }}</p>
            @endif

            <button type="submit" class="hidden">Submit</button>
        </form>

        <a href="/" class="block mt-10 text-[10px] text-slate-300 hover:text-slate-500 uppercase tracking-widest transition-colors">
            Back to Dashboard
        </a>
    </div>
</body>
</html>
