<!DOCTYPE html>
<html lang="{{ session('locale', 'id') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantu.In - {{ session('locale') == 'en' ? 'Transaction History' : 'Riwayat Transaksi' }}</title>
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
        
        /* Search Input Styles */
        .search-input {
            background-color: #F3F4F6;
            border-radius: 8px;
            padding: 12px 16px 12px 40px;
            transition: all 0.3s ease;
        }
        
        .dark .search-input {
            background-color: #374151;
            color: #f9fafb;
        }
        
        .search-input:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(78, 205, 196, 0.3);
        }
        
        /* Transaction Item Styles */
        .transaction-item {
            padding: 16px 0;
            border-bottom: 1px solid #F3F4F6;
            transition: all 0.2s ease;
        }
        
        .dark .transaction-item {
            border-bottom: 1px solid #374151;
        }
        
        .transaction-item:last-child {
            border-bottom: none;
        }
        
        .transaction-item:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        .dark .transaction-item:hover {
            background-color: rgba(255, 255, 255, 0.02);
        }
        
        /* Transaction Type Icons */
        .transaction-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }
        
        .transaction-icon.income {
            background-color: #10B981;
            color: white;
        }
        
        .transaction-icon.expense {
            background-color: #EF4444;
            color: white;
        }
        
        /* Amount Styles */
        .amount-income {
            color: #10B981;
        }
        
        .amount-expense {
            color: #EF4444;
        }
        
        /* Month Section Styles */
        .month-section {
            margin-bottom: 32px;
        }
        
        .month-header {
            font-size: 18px;
            font-weight: 600;
            color: #6B7280;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 1px solid #E5E7EB;
        }
        
        .dark .month-header {
            color: #9CA3AF;
            border-bottom: 1px solid #374151;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen transition-colors duration-300">
    <!-- Header -->
    <div class="bg-white shadow-sm px-4 py-4 flex items-center justify-center relative transition-colors duration-300">
        <a href="{{ route('dashboard') }}" class="absolute left-4 p-2 hover:bg-gray-100 rounded-full transition-colors">
            <i class="fas fa-arrow-left text-gray-600 text-xl"></i>
        </a>
        <h1 class="text-xl font-semibold text-gray-800">{{ session('locale') == 'en' ? 'Transaction' : 'Transaksi' }}</h1>
    </div>

    <div class="container mx-auto px-4 py-6 max-w-4xl">
        <!-- Search Bar -->
        <div class="mb-6 relative">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input 
                    type="text" 
                    placeholder="{{ session('locale') == 'en' ? 'Search transaction' : 'Cari transaksi' }}"
                    class="search-input w-full pr-12"
                    id="searchInput"
                >
                <button class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-filter"></i>
                </button>
            </div>
        </div>

        <!-- Transaction History -->
        <div class="grid md:grid-cols-2 gap-8">
            @foreach($transactions as $month => $monthTransactions)
            <div class="month-section">
                <div class="month-header">{{ $month }}</div>
                <div class="bg-white rounded-lg shadow-sm p-4 transition-colors duration-300">
                    @foreach($monthTransactions as $transaction)
                    <div class="transaction-item flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="transaction-icon {{ $transaction['type'] === 'topup' ? 'income' : 'expense' }}">
                                {{ $transaction['type'] === 'topup' ? '+' : '-' }}
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">{{ $transaction['title'] }}</div>
                                <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($transaction['date'])->setTimezone('Asia/Jakarta')->format('d M Y') }}</div>
                            </div>
                        </div>
                        <div class="{{ $transaction['type'] === 'topup' ? 'amount-income' : 'amount-expense' }} font-semibold">
                            {{ $transaction['type'] === 'topup' ? '+' : '-' }}Rp {{ number_format(abs($transaction['amount']), 0, ',', '.') }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
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
            
            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const transactionItems = document.querySelectorAll('.transaction-item');
            let searchTimeout;
            
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const searchTerm = this.value.toLowerCase();
                
                if (searchTerm.length < 2) {
                    // Show all items if search term is too short
                    transactionItems.forEach(item => {
                        item.style.display = 'flex';
                    });
                    return;
                }
                
                // Add loading state
                searchInput.classList.add('opacity-50');
                
                searchTimeout = setTimeout(() => {
                    // Search in transaction titles
                    transactionItems.forEach(item => {
                        const title = item.querySelector('.font-medium').textContent.toLowerCase();
                        if (title.includes(searchTerm)) {
                            item.style.display = 'flex';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                    
                    // Remove loading state
                    searchInput.classList.remove('opacity-50');
                }, 300); // Debounce search for 300ms
            });
        });
    </script>
</body>
</html>
