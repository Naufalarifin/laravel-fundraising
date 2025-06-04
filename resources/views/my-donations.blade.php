<!DOCTYPE html>
<html lang="{{ session('locale', 'id') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantu.In - {{ session('locale') == 'en' ? 'My Donations' : 'Donasi Saya' }}</title>
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
    </style>
</head>
<body class="bg-gray-50 min-h-screen transition-colors duration-300">
    <!-- Header -->
    <div class="bg-white shadow-sm px-4 py-4 flex items-center justify-center relative transition-colors duration-300">
        <a href="{{ route('account') }}" class="absolute left-4 p-2 hover:bg-gray-100 rounded-full transition-colors">
            <i class="fas fa-arrow-left text-gray-600 text-xl"></i>
        </a>
        <h1 class="text-xl font-semibold text-gray-800">{{ session('locale') == 'en' ? 'My Donations' : 'Donasi Saya' }}</h1>
    </div>

    <div class="container mx-auto px-4 py-8 max-w-4xl">
        @if(empty($donations))
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-heart text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ session('locale') == 'en' ? 'No Donations Yet' : 'Belum Ada Donasi' }}</h3>
                <p class="text-gray-600 mb-6">{{ session('locale') == 'en' ? 'Start helping others by making your first donation' : 'Mulai bantu sesama dengan melakukan donasi pertama Anda' }}</p>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-custom-teal text-white rounded-lg hover:bg-custom-teal transition-colors">
                    <i class="fas fa-search mr-2"></i>
                    {{ session('locale') == 'en' ? 'Find Campaigns' : 'Cari Kampanye' }}
                </a>
            </div>
        @else
            <!-- Donations List -->
            <div class="grid grid-cols-2 gap-6">
                @foreach($donations as $donation)
                    <div class="bg-white rounded-lg shadow-sm p-4 transition-colors duration-300">
                        <div class="flex flex-col h-full">
                            <div class="w-full h-40 mb-3 rounded-lg overflow-hidden">
                                <img src="{{ $donation['imageUrls'][0] ?? 'images/default-campaign.jpg' }}" 
                                     alt="{{ $donation['name'] }}"
                                     class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800 mb-1 text-base line-clamp-2">{{ $donation['name'] }}</h3>
                                <div class="flex items-center text-sm mt-2">
                                    <span class="text-gray-500 mr-2">{{ session('locale') == 'en' ? 'Amount:' : 'Jumlah:' }}</span>
                                    <span class="font-semibold text-gray-800">Rp {{ number_format($donation['progress'], 0, ',', '.') }}</span>
                                </div>
                                <div class="mt-2">
                                    <span class="text-xs text-gray-500">{{ session('locale') == 'en' ? 'Category:' : 'Kategori:' }}</span>
                                    <span class="text-xs font-medium text-gray-700">{{ $donation['category'] }}</span>
                                </div>
                            </div>
                            <div class="mt-3">
                                @php
                                    $now = new \Google\Cloud\Core\Timestamp(new \DateTime());
                                    $isCompleted = $donation['finishDate']->formatAsString() < $now->formatAsString();
                                @endphp
                                <span class="px-3 py-1 text-xs font-medium rounded-full 
                                    {{ $isCompleted ? 'bg-green-100 text-green-800' : 
                                       'bg-yellow-100 text-yellow-800' }}">
                                    {{ $isCompleted ? 
                                       (session('locale') == 'en' ? 'Completed' : 'Selesai') : 
                                       (session('locale') == 'en' ? 'In Progress' : 'Berjalan') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
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