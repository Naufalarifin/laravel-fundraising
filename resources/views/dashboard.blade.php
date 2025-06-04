<!DOCTYPE html>
<html lang="{{ session('locale', 'id') }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bantu.In - Dashboard</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    .campaign-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .icon-btn:hover {
        transform: scale(1.05);
    }
    .progress-bar {
        background: linear-gradient(90deg, #10B981 0%, #34D399 100%);
    }
    
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
    
    .dark .bg-gray-200 {
        background-color: #4b5563 !important;
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
    
    .dark .campaign-card:hover {
        box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    }
    
    /* Loading Animation Styles */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        transition: opacity 0.3s ease-in-out;
    }

    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #4ECDC4;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .dark .loading-overlay {
        background: rgba(17, 24, 39, 0.9);
    }

    .dark .loading-spinner {
        border: 5px solid #374151;
        border-top: 5px solid #4ECDC4;
    }

    .content-hidden {
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }

    .content-visible {
        opacity: 1;
    }
</style>
</head>
<body class="bg-gray-50 transition-colors duration-300">
<!-- Loading Overlay -->
<div id="loadingOverlay" class="loading-overlay">
    <div class="loading-spinner"></div>
</div>

<!-- Main Content -->
<div id="mainContent" class="content-hidden">
    <!-- Header -->
    <div class="bg-white shadow-sm px-4 py-3 flex items-center justify-between transition-colors duration-300">
        <div class="flex items-center">
            <div class="relative flex-1 max-w-md">
                <input 
                    type="text" 
                    id="searchInput"
                    placeholder="{{ session('locale') == 'en' ? 'Help Others...' : 'Bantu Sesama...' }}" 
                    class="w-full pl-4 pr-10 py-2 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white text-gray-800 transition-colors duration-300"
                    onkeyup="searchCampaigns(this.value)"
                >
                <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                
                <!-- Search Results Dropdown -->
                <div id="searchResults" class="absolute w-full mt-2 bg-white rounded-lg shadow-lg border border-gray-200 hidden z-50">
                    <div class="p-2">
                        <div id="searchResultsList" class="space-y-2">
                            <!-- Results will be populated here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- PROFILE BUTTON -->
        <a href="{{ route('account') }}" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
            <i class="fas fa-user-circle text-gray-600 text-2xl"></i>
        </a>
    </div>

    <div class="container mx-auto px-4 py-6 max-w-6xl">
        <!-- Donation Balance Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6 transition-colors duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">{{ session('locale') == 'en' ? 'Donation balance:' : 'Saldo donasi:' }}</p>
                    <p class="text-2xl font-bold text-gray-800">Rp. {{ number_format($donationBalance, 0, ',', '.') }}</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('topup') }}" class="icon-btn flex flex-col items-center transition-all duration-200">
                        <div class="w-16 h-16 bg-custom-teal rounded-2xl flex items-center justify-center mb-2 hover:bg-custom-teal transition-colors">
                            <i class="fas fa-plus text-white text-2xl"></i>
                        </div>
                        <span class="text-sm text-gray-600 font-medium">Top up</span>
                    </a>
                    <a href="{{ route('history') }}" class="icon-btn flex flex-col items-center transition-all duration-200">
                        <div class="w-16 h-16 bg-custom-teal rounded-2xl flex items-center justify-center mb-2 hover:bg-custom-teal transition-colors">
                            <i class="fas fa-receipt text-white text-2xl"></i>
                        </div>
                        <span class="text-sm text-gray-600 font-medium">{{ session('locale') == 'en' ? 'History' : 'Riwayat' }}</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Category Icons -->
        <div class="flex justify-center space-x-8 mb-8">
            <a href="javascript:void(0)" onclick="toggleCategory('Bencana Alam')" data-category="Bencana Alam" class="category-link icon-btn flex flex-col items-center p-4 hover:bg-gray-100 rounded-lg transition-all duration-200">
                <div class="w-12 h-12 rounded-full overflow-hidden mb-2 border-2 border-gray-200">
                    <img src="images/bencana.png" alt="Bencana" class="w-full h-full object-cover">
                </div>
                <span class="text-xs text-gray-700">{{ session('locale') == 'en' ? 'Disaster' : 'Bencana' }}</span>
            </a>
            <a href="javascript:void(0)" onclick="toggleCategory('Kesehatan')" data-category="Kesehatan" class="category-link icon-btn flex flex-col items-center p-4 hover:bg-gray-100 rounded-lg transition-all duration-200">
                <div class="w-12 h-12 rounded-full overflow-hidden mb-2 border-2 border-red-200">
                    <img src="images/medis.png" alt="Kesehatan" class="w-full h-full object-cover">
                </div>
                <span class="text-xs text-gray-700">{{ session('locale') == 'en' ? 'Medical' : 'Medis' }}</span>
            </a>
            <a href="javascript:void(0)" onclick="toggleCategory('Lingkungan')" data-category="Lingkungan" class="category-link icon-btn flex flex-col items-center p-4 hover:bg-gray-100 rounded-lg transition-all duration-200">
                <div class="w-12 h-12 rounded-full overflow-hidden mb-2 border-2 border-orange-200">
                    <img src="images/kebakaran.png" alt="Lingkungan" class="w-full h-full object-cover">
                </div>
                <span class="text-xs text-gray-700">{{ session('locale') == 'en' ? 'Fire' : 'Kebakaran' }}</span>
            </a>
            <a href="javascript:void(0)" onclick="toggleCategory('Duafa')" data-category="Duafa" class="category-link icon-btn flex flex-col items-center p-4 hover:bg-gray-100 rounded-lg transition-all duration-200">
                <div class="w-12 h-12 rounded-full overflow-hidden mb-2 border-2 border-yellow-200">
                    <img src="images/duafa.png" alt="Duafa" class="w-full h-full object-cover">
                </div>
                <span class="text-xs text-gray-700">{{ session('locale') == 'en' ? 'Duafa' : 'Duafa' }}</span>
            </a>
        </div>

        <!-- Latest Campaign -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ session('locale') == 'en' ? 'Latest Campaign' : 'Kampanye Terbaru' }}</h2>
            <div class="grid md:grid-cols-3 gap-6" id="latestCampaigns">
                @foreach($latestCampaigns as $campaign)
                <a href="{{ route('campaign.detail', $campaign['id']) }}" class="campaign-card bg-white rounded-lg shadow-sm overflow-hidden cursor-pointer transition-all duration-300">
                    <img src="{{ $campaign['image'] }}" alt="{{ $campaign['title'] }}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <p class="text-xs text-gray-500 mb-2">{{ $campaign['category'] }}</p>
                        <h3 class="font-semibold text-gray-800 mb-3 line-clamp-2">{{ $campaign['title'] }}</h3>
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                            <div class="progress-bar h-2 rounded-full" style="width: {{ $campaign['progress'] }}%"></div>
                        </div>
                        <p class="text-xs text-gray-600">{{ session('locale') == 'en' ? 'Collected:' : 'Terkumpul:' }} Rp {{ number_format($campaign['collected'], 0, ',', '.') }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        <!-- Finished Campaign -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ session('locale') == 'en' ? 'Finished Campaign' : 'Kampanye Selesai' }}</h2>
            <div class="grid md:grid-cols-4 gap-6" id="finishedCampaigns">
                @foreach($finishedCampaigns as $campaign)
                <a href="{{ route('campaign.detail', $campaign['id']) }}" class="campaign-card bg-white rounded-lg shadow-sm overflow-hidden cursor-pointer transition-all duration-300">
                    <img src="{{ $campaign['image'] }}" alt="{{ $campaign['title'] }}" class="w-full h-32 object-cover">
                    <div class="p-3">
                        <h4 class="font-medium text-sm text-gray-800 mb-2 line-clamp-2">{{ $campaign['title'] }}</h4>
                        <div class="w-full bg-green-200 rounded-full h-1.5 mb-1">
                            <div class="bg-green-500 h-1.5 rounded-full" style="width: 100%"></div>
                        </div>
                        <p class="text-xs text-gray-600">✓ {{ session('locale') == 'en' ? 'Completed' : 'Selesai' }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Bantu.In Logo -->
    <div class="py-8 pl-4">
        <h1 class="text-3xl font-bold text-custom-teal">Bantu.In</h1>
    </div>

    <!-- Floating Create Campaign Button -->
    <div class="fixed bottom-6 right-6 z-50">
        <a href="{{ route('campaign.create') }}" class="group relative">
            <!-- Simple White Button -->
            <div class="w-14 h-14 bg-white rounded-full shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center border border-gray-100">
                <i class="fas fa-plus text-gray-600 text-lg"></i>
            </div>
            
            <!-- Tooltip -->
            <div class="absolute bottom-full right-0 mb-2 px-3 py-1 bg-gray-800 text-white text-sm rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                {{ session('locale') == 'en' ? 'Create Campaign' : 'Buat Kampanye' }}
                <div class="absolute top-full right-4 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-800"></div>
            </div>
        </a>
    </div>
</div>

<script>
    // Store all campaigns data
    const allCampaigns = @json($latestCampaigns);
    const allFinishedCampaigns = @json($finishedCampaigns);
    let currentCategory = null;

    // Category mapping
    const categoryMapping = {
        'Bencana Alam': 'Bencana Alam',
        'Kesehatan': 'Kesehatan',
        'Lingkungan': 'Lingkungan',
        'Duafa': 'Duafa'
    };

    // Apply dark mode on page load
    document.addEventListener('DOMContentLoaded', function() {
        const isDarkMode = localStorage.getItem('darkMode') === 'true';
        if (isDarkMode) {
            document.documentElement.classList.add('dark');
            document.body.classList.add('dark');
        }

        // Show content and hide loading overlay when page is fully loaded
        window.addEventListener('load', function() {
            const loadingOverlay = document.getElementById('loadingOverlay');
            const mainContent = document.getElementById('mainContent');
            
            // Add a small delay to ensure smooth transition
            setTimeout(() => {
                loadingOverlay.style.opacity = '0';
                mainContent.classList.remove('content-hidden');
                mainContent.classList.add('content-visible');
                
                // Remove loading overlay from DOM after fade out
                setTimeout(() => {
                    loadingOverlay.style.display = 'none';
                }, 300);
            }, 500);
        });
    });

    function searchCampaigns(query) {
        // Filter campaigns based on search query and category
        const filteredCampaigns = allCampaigns.filter(campaign => {
            const matchesSearch = query.length < 3 || 
                campaign.title.toLowerCase().includes(query.toLowerCase());
            const matchesCategory = !currentCategory || 
                campaign.category === categoryMapping[currentCategory];
            return matchesSearch && matchesCategory;
        });

        // Update Latest Campaigns section
        if (filteredCampaigns.length > 0) {
            document.getElementById('latestCampaigns').innerHTML = filteredCampaigns.map(campaign => `
                <a href="{{ url('campaign') }}/${campaign.id}" class="campaign-card bg-white rounded-lg shadow-sm overflow-hidden cursor-pointer transition-all duration-300">
                    <img src="${campaign.image}" alt="${campaign.title}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <p class="text-xs text-gray-500 mb-2">${campaign.category}</p>
                        <h3 class="font-semibold text-gray-800 mb-3 line-clamp-2">${campaign.title}</h3>
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                            <div class="progress-bar h-2 rounded-full" style="width: ${campaign.progress}%"></div>
                        </div>
                        <p class="text-xs text-gray-600">{{ session('locale') == 'en' ? 'Collected:' : 'Terkumpul:' }} Rp ${new Intl.NumberFormat('id-ID').format(campaign.collected)}</p>
                    </div>
                </a>
            `).join('');
        } else {
            document.getElementById('latestCampaigns').innerHTML = `
                <div class="col-span-3 text-center py-8 text-gray-500">
                    {{ session('locale') == 'en' ? 'No campaigns found' : 'Tidak ada kampanye ditemukan' }}
                </div>
            `;
        }

        // Update Finished Campaigns section
        const filteredFinishedCampaigns = allFinishedCampaigns.filter(campaign => {
            const matchesSearch = query.length < 3 || 
                campaign.title.toLowerCase().includes(query.toLowerCase());
            const matchesCategory = !currentCategory || 
                campaign.category === categoryMapping[currentCategory];
            return matchesSearch && matchesCategory;
        });

        if (filteredFinishedCampaigns.length > 0) {
            document.getElementById('finishedCampaigns').innerHTML = filteredFinishedCampaigns.map(campaign => `
                <a href="{{ url('campaign') }}/${campaign.id}" class="campaign-card bg-white rounded-lg shadow-sm overflow-hidden cursor-pointer transition-all duration-300">
                    <img src="${campaign.image}" alt="${campaign.title}" class="w-full h-32 object-cover">
                    <div class="p-3">
                        <h4 class="font-medium text-sm text-gray-800 mb-2 line-clamp-2">${campaign.title}</h4>
                        <div class="w-full bg-green-200 rounded-full h-1.5 mb-1">
                            <div class="bg-green-500 h-1.5 rounded-full" style="width: 100%"></div>
                        </div>
                        <p class="text-xs text-gray-600">✓ {{ session('locale') == 'en' ? 'Completed' : 'Selesai' }}</p>
                    </div>
                </a>
            `).join('');
        } else {
            document.getElementById('finishedCampaigns').innerHTML = '';
        }
    }

    // Category filter functionality
    function toggleCategory(category) {
        const categoryLinks = document.querySelectorAll('.category-link');
        
        if (currentCategory === category) {
            // If clicking the same category, remove the filter
            currentCategory = null;
            categoryLinks.forEach(link => link.classList.remove('bg-custom-teal', 'text-white'));
        } else {
            // Apply new category filter
            currentCategory = category;
            categoryLinks.forEach(link => {
                if (link.dataset.category === category) {
                    link.classList.add('bg-custom-teal', 'text-white');
                } else {
                    link.classList.remove('bg-custom-teal', 'text-white');
                }
            });
        }

        // Trigger search with current query
        const searchInput = document.getElementById('searchInput');
        searchCampaigns(searchInput.value);
    }

    // Close search results when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('#searchInput') && !event.target.closest('#searchResults')) {
            searchResults.classList.add('hidden');
        }
    });
</script>
</body>
</html>
