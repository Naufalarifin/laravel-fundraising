<!DOCTYPE html>
<html lang="{{ session('locale', 'id') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantu.In - {{ session('locale') == 'en' ? 'Account' : 'Akun' }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* CUSTOM TEAL COLORS */
        .bg-custom-teal {
            background-color: #4ECDC4 !important;
        }
        .bg-custom-teal:hover {
            background-color: #45B7AA !important;
        }
        .text-custom-teal {
            color: #4ECDC4 !important;
        }
        
        /* DARK MODE STYLES */
        .dark {
            color-scheme: dark;
        }
        
        .dark .bg-gray-50 {
            background-color: #111827 !important;
        }
        
        .dark .bg-white {
            background-color: #1f2937 !important;
        }
        
        .dark .bg-gray-100 {
            background-color: #374151 !important;
        }
        
        .dark .text-gray-800 {
            color: #f9fafb !important;
        }
        
        .dark .text-gray-600 {
            color: #d1d5db !important;
        }
        
        .dark .text-gray-700 {
            color: #e5e7eb !important;
        }
        
        .dark .text-gray-500 {
            color: #9ca3af !important;
        }
        
        .dark .border-gray-200 {
            border-color: #374151 !important;
        }
        
        .dark .border-gray-100 {
            border-color: #4b5563 !important;
        }
        
        .dark .shadow-sm {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.3) !important;
        }
        
        .menu-item:hover {
            background-color: rgba(0, 0, 0, 0.05);
            transform: translateX(5px);
        }
        
        .dark .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        .profile-image {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen transition-colors duration-300">
    <!-- Header -->
    <div class="bg-white shadow-sm px-4 py-4 flex items-center justify-center relative transition-colors duration-300">
        <a href="{{ route('dashboard') }}" class="absolute left-4 p-2 hover:bg-gray-100 rounded-full transition-colors">
            <i class="fas fa-arrow-left text-gray-600 text-xl"></i>
        </a>
        <h1 class="text-xl font-semibold text-gray-800">{{ session('locale') == 'en' ? 'Account' : 'Akun' }}</h1>
    </div>

    <div class="container mx-auto px-4 py-8 max-w-md">
        <!-- Success Message -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
        @endif

        <!-- Profile Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6 transition-colors duration-300">
            <div class="flex items-center space-x-4 mb-4">
                <img src="{{ $user['avatar'] }}" 
                     alt="Profile Picture" 
                     class="profile-image border-4 border-gray-100">
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-gray-800">{{ $user['name'] }}</h2>
                    <p class="text-gray-600 text-sm">{{ $user['email'] }}</p>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex space-x-3">
                <a href="{{ route('topup') }}" class="flex-1 flex items-center justify-center px-4 py-2 bg-custom-teal rounded-lg hover:bg-custom-teal transition-colors">
                    <i class="fas fa-plus text-white mr-2"></i>
                    <span class="text-white text-sm">Top up</span>
                </a>
            </div>
        </div>

        <!-- Menu Items -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden transition-colors duration-300">
            <!-- My Donation -->
            <a href="{{ route('my-donations') }}" class="menu-item flex items-center justify-between p-4 border-b border-gray-100 cursor-pointer transition-all duration-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-heart text-red-500"></i>
                    </div>
                    <span class="text-gray-800 font-medium">{{ session('locale') == 'en' ? 'My donation' : 'Donasi saya' }}</span>
                </div>
                <i class="fas fa-chevron-right text-gray-400"></i>
            </a>

            <!-- Change Password -->
            <a href="{{ route('account.changePassword') }}" class="menu-item flex items-center justify-between p-4 border-b border-gray-100 cursor-pointer transition-all duration-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-key text-blue-500"></i>
                    </div>
                    <span class="text-gray-800 font-medium">{{ session('locale') == 'en' ? 'Change password' : 'Ubah password' }}</span>
                </div>
                <i class="fas fa-chevron-right text-gray-400"></i>
            </a>
        </div>
    </div>

    <!-- Bantu.In Logo -->
    <div class="fixed bottom-4 left-4">
        <h1 class="text-3xl font-bold text-custom-teal">Bantu.In</h1>
    </div>

    <script>
        // Apply dark mode on page load
        document.addEventListener('DOMContentLoaded', function() {
            const isDarkMode = localStorage.getItem('darkMode') === 'true';
            if (isDarkMode) {
                document.documentElement.classList.add('dark');
                document.body.classList.add('dark');
            }
        });
    </script>
</body>
</html>
