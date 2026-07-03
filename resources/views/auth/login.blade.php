<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Staf Login - RS Wava Husada</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-hospital {
            background-image: url('{{ asset('asset/bg-login.png') }}');
            /* Menggunakan cover dan fixed agar tidak terlalu nge-zoom dan lebih proporsional */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-hospital font-sans">
    
    <!-- Login Container -->
    <div class="bg-white rounded-[24px] shadow-2xl p-8 w-full max-w-sm mx-4 relative z-10">
        
        <!-- Logo -->
        <div class="flex justify-center mb-4">
            <img src="{{ asset('asset/logo-rs-wava-husada.png') }}" alt="Logo Rumah Sakit Wava Husada" class="h-32 object-contain">
        </div>

        <!-- Title -->
        <div class="text-center mb-8">
            <h2 class="text-[#2c3e50] font-semibold text-[17px] tracking-wide">PORTAL STAF LOGIN</h2>
            <h3 class="text-[#2c3e50] font-semibold text-[17px] tracking-wide">RUMAH SAKIT WAVA HUSADA</h3>
        </div>

        <!-- Notifikasi Error Login Sementara -->
        @if(session('error'))
            <div class="mb-4 p-2.5 bg-red-100 border border-red-400 text-red-700 text-xs rounded-lg text-center">
                {{ session('error') }}
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf
            
            <!-- Input 1: Nama Pengguna atau NIK -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <!-- User Icon Outline -->
                    <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <input type="text" name="username" value="admin123" required
                    class="block w-full pl-12 pr-4 py-3 text-sm text-gray-800 bg-transparent border border-gray-500 rounded-full focus:outline-none focus:border-[#F07A3A] focus:ring-1 focus:ring-[#F07A3A] transition-colors"
                    placeholder="Nama Pengguna atau NIK">
            </div>

            <!-- Input 2: Kata Sandi -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <!-- Lock Icon Outline -->
                    <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <input type="password" name="password" id="password" value="admin123" required
                    class="block w-full pl-12 pr-12 py-3 text-sm text-gray-800 bg-transparent border border-gray-500 rounded-full focus:outline-none focus:border-[#F07A3A] focus:ring-1 focus:ring-[#F07A3A] transition-colors"
                    placeholder="Kata Sandi">
                
                <!-- Toggle Password Icon -->
                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
                    <svg id="eye-slash-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>
                    </svg>
                    <svg id="eye-icon" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-full shadow-md text-sm font-semibold text-white bg-[#F07A3A] hover:bg-[#e06929] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#F07A3A] transition-colors tracking-wide">
                    MASUK
                </button>
            </div>
        </form>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeSlashIcon = document.getElementById('eye-slash-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeSlashIcon.classList.add('hidden');
                eyeIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeSlashIcon.classList.remove('hidden');
                eyeIcon.classList.add('hidden');
            }
        }
    </script>
</body>
</html>