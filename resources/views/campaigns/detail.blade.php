<!DOCTYPE html>
<html lang="{{ session('locale', 'id') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantu.In - {{ $campaign['title'] }}</title>
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
        
        /* Progress Bar */
        .progress-bar {
            background: linear-gradient(90deg, #10B981 0%, #34D399 100%);
            height: 8px;
            border-radius: 4px;
            transition: width 0.3s ease;
        }
        
        /* Donate Button */
        .donate-btn {
            background: linear-gradient(135deg, #4ECDC4 0%, #45B7AA 100%);
            border-radius: 12px;
            padding: 16px 32px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(78, 205, 196, 0.3);
        }
        
        .donate-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(78, 205, 196, 0.4);
        }
        
        /* Campaign Image */
        .campaign-image {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }
        
        /* Stats Card */
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }
        
        .dark .stats-card {
            background: #1f2937;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        /* Organizer Card */
        .organizer-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #E5E7EB;
        }
        
        .dark .organizer-card {
            background: #1f2937;
            border-color: #374151;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }
        
        /* Tab Styles */
        .tab-button {
            padding: 12px 24px;
            border-bottom: 2px solid transparent;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .tab-button.active {
            border-bottom-color: #4ECDC4;
            color: #4ECDC4;
        }
        
        .tab-button:hover {
            color: #4ECDC4;
        }
        
        /* Update Card */
        .update-card {
            background: white;
            border-radius: 8px;
            padding: 16px;
            border-left: 4px solid #4ECDC4;
            margin-bottom: 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .dark .update-card {
            background: #1f2937;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen transition-colors duration-300">
    <!-- Header -->
    <div class="bg-white shadow-sm px-4 py-4 flex items-center justify-between transition-colors duration-300">
        <div class="flex items-center">
            <a href="{{ route('dashboard') }}" class="p-2 hover:bg-gray-100 rounded-full transition-colors mr-2">
                <i class="fas fa-arrow-left text-gray-600 text-xl"></i>
            </a>
            <h1 class="text-xl font-semibold text-gray-800">{{ session('locale') == 'en' ? 'Details' : 'Detail' }}</h1>
        </div>
        <button class="p-2 hover:bg-gray-100 rounded-full transition-colors">
            <i class="fas fa-heart text-gray-400 text-xl hover:text-red-500"></i>
        </button>
    </div>

    <div class="container mx-auto px-4 py-6 max-w-4xl">
        <!-- Campaign Image -->
        <div class="campaign-image mb-6">
            <img src="{{ $campaign['image'] }}" alt="{{ $campaign['title'] }}" class="w-full h-64 md:h-80 object-cover">
        </div>

        <!-- Campaign Title -->
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">{{ $campaign['title'] }}</h1>

        <!-- Campaign Stats -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <div class="flex items-center text-gray-500">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    <span>{{ $campaign['location'] }}</span>
                </div>
                <div class="flex items-center text-gray-500">
                    <i class="fas fa-eye mr-2"></i>
                    <span>{{ number_format($campaign['views']) }}</span>
                </div>
            </div>
            <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <i class="fas fa-share-alt text-gray-500"></i>
            </button>
        </div>

        <!-- Progress Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6 transition-colors duration-300">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">{{ session('locale') == 'en' ? 'Target' : 'Target' }}</p>
                    <p class="text-2xl font-bold text-custom-teal">Rp {{ number_format($campaign['target'], 0, ',', '.') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500 mb-1">{{ $campaign['days_left'] }} {{ session('locale') == 'en' ? 'days to go' : 'hari lagi' }}</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $campaign['progress'] }}%</p>
                </div>
            </div>
            
            <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                <div class="progress-bar rounded-full" style="width: {{ $campaign['progress'] }}%"></div>
            </div>
            
            <p class="text-sm text-gray-600">
                {{ session('locale') == 'en' ? 'Collected:' : 'Terkumpul:' }} 
                <span class="font-semibold">Rp {{ number_format($campaign['collected'], 0, ',', '.') }}</span>
            </p>
        </div>

        <!-- Organizer Section -->
        <div class="organizer-card mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <img src="{{ $campaign['organizer']['avatar'] }}" alt="{{ $campaign['organizer']['name'] }}" class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <div class="flex items-center space-x-2">
                            <h3 class="font-semibold text-gray-800">{{ $campaign['organizer']['name'] }}</h3>
                            @if($campaign['organizer']['verified'])
                            <i class="fas fa-check-circle text-blue-500 text-sm"></i>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500">{{ session('locale') == 'en' ? 'Verified Account' : 'Akun Terverifikasi' }}</p>
                    </div>
                </div>
                <button class="donate-btn text-white" onclick="donate()">
                    {{ session('locale') == 'en' ? 'Donate Now' : 'Donasi Sekarang' }}
                </button>
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="bg-white rounded-lg shadow-sm mb-6 transition-colors duration-300">
            <!-- Tab Buttons -->
            <div class="flex border-b border-gray-200">
                <button class="tab-button active" onclick="switchTab('story')">
                    {{ session('locale') == 'en' ? 'Story' : 'Cerita' }}
                </button>
                <button class="tab-button" onclick="switchTab('updates')">
                    {{ session('locale') == 'en' ? 'Updates' : 'Update' }}
                </button>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Story Tab -->
                <div id="storyTab" class="tab-content">
                    <p class="text-gray-700 whitespace-pre-line">{{ $campaign['story'] }}</p>
                </div>

                <!-- Updates Tab -->
                <div id="updatesTab" class="tab-content hidden">
                    @if(count($campaign['updates']) > 0)
                        @foreach($campaign['updates'] as $update)
                            <div class="update-card">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-semibold text-gray-800">{{ $update['title'] }}</h4>
                                    <span class="text-sm text-gray-500">{{ $update['date'] }}</span>
                                </div>
                                <p class="text-gray-600">{{ $update['content'] }}</p>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-center py-4">
                            {{ session('locale') == 'en' ? 'No updates yet' : 'Belum ada update' }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Fixed Donate Button (Mobile) -->
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 md:hidden">
            <button class="donate-btn w-full text-white text-center" onclick="donate()">
                {{ session('locale') == 'en' ? 'Donate Now' : 'Donasi Sekarang' }}
            </button>
        </div>
    </div>

    <!-- Bantu.In Logo -->
    <div class="fixed bottom-4 left-4 hidden md:block">
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

        function donate() {
            // Redirect to donation page
            window.location.href = '{{ route('donate', $campaign['id']) }}';
        }

        function switchTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active');
            });
            
            // Show selected tab content
            document.getElementById(tabName + 'Tab').classList.remove('hidden');
            
            // Add active class to selected tab button
            event.target.classList.add('active');
        }

        // Add click event to donate buttons
        document.querySelectorAll('.donate-btn').forEach(btn => {
            btn.addEventListener('click', donate);
        });
    </script>
</body>
</html>
