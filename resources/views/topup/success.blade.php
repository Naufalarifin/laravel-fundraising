<!DOCTYPE html>
<html lang="{{ session('locale', 'id') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantu.In - {{ session('locale') == 'en' ? 'Top up Success' : 'Top up Berhasil' }}</title>
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
        
        .dark .text-gray-400 {
            color: #6b7280 !important;
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
        
        /* Success Animation */
        .success-checkmark {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            border-radius: 50%;
            box-sizing: content-box;
            border: 4px solid #4ECDC4;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .success-checkmark i {
            color: #4ECDC4;
            font-size: 40px;
        }
        
        .action-btn {
            background: linear-gradient(135deg, #4ECDC4 0%, #45B7AA 100%);
            border-radius: 12px;
            padding: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(78, 205, 196, 0.3);
        }
        
        .outline-btn {
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            padding: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .outline-btn:hover {
            border-color: #4ECDC4;
            color: #4ECDC4;
        }
        
        .dark .outline-btn {
            border-color: #4B5563;
        }
        
        .dark .outline-btn:hover {
            border-color: #4ECDC4;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen transition-colors duration-300">
    <!-- Header -->
    <div class="bg-white shadow-sm px-4 py-4 flex items-center justify-center relative transition-colors duration-300">
        <h1 class="text-xl font-semibold text-gray-800">{{ session('locale') == 'en' ? 'Top up Success' : 'Top up Berhasil' }}</h1>
    </div>

    <div class="container mx-auto px-4 py-12 max-w-md">
        <div class="bg-white rounded-lg shadow-sm p-8 text-center transition-colors duration-300">
            <!-- Success Icon -->
            <div class="success-checkmark mb-6">
                <i class="fas fa-check"></i>
            </div>
            
            <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ session('locale') == 'en' ? 'Top up Successful!' : 'Top up Berhasil!' }}</h2>
            <p class="text-gray-600 mb-8">{{ session('locale') == 'en' ? 'Your balance has been updated successfully.' : 'Saldo Anda telah berhasil diperbarui.' }}</p>
            
            <!-- Action Buttons -->
            <div class="space-y-4">
                <a href="{{ route('dashboard') }}" class="action-btn w-full block text-white text-center">
                    {{ session('locale') == 'en' ? 'Back to Dashboard' : 'Kembali ke Dashboard' }}
                </a>
                <a href="{{ route('topup') }}" class="outline-btn w-full block text-gray-700 text-center">
                    {{ session('locale') == 'en' ? 'Top up Again' : 'Top up Lagi' }}
                </a>
            </div>
        </div>
    </div>

    <!-- Bantu.In Logo -->
    <div class="fixed bottom-4 left-4">
        <h1 class="text-3xl font-bold text-custom-teal">Bantu.In</h1>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Apply dark mode on page load
            const isDarkMode = localStorage.getItem('darkMode') === 'true';
            if (isDarkMode) {
                document.documentElement.classList.add('dark');
                document.body.classList.add('dark');
            }
        });
    </script>
</body>
</html>
