<!DOCTYPE html>
<html lang="{{ session('locale', 'id') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantu.In - {{ session('locale') == 'en' ? 'Donation Instruction' : 'Instruksi Donasi' }}</title>
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
        
        /* Payment Method Styles */
        .payment-method {
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            padding: 16px;
            transition: all 0.2s ease;
            margin-bottom: 12px;
        }
        
        .dark .payment-method {
            border-color: #4B5563;
        }
        
        .payment-method.selected {
            border-color: #4ECDC4;
            background-color: rgba(78, 205, 196, 0.05);
        }
        
        .payment-radio {
            width: 20px;
            height: 20px;
            border: 2px solid #D1D5DB;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        
        .payment-radio.selected {
            border-color: #4ECDC4;
        }
        
        .payment-radio.selected::after {
            content: "";
            width: 12px;
            height: 12px;
            background-color: #4ECDC4;
            border-radius: 50%;
        }
        
        /* Submit Button Styles */
        .submit-btn {
            background: linear-gradient(135deg, #4ECDC4 0%, #45B7AA 100%);
            border-radius: 12px;
            padding: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(78, 205, 196, 0.3);
        }
        
        /* Payment Details Styles */
        .payment-details {
            background-color: #F9FAFB;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
        }
        
        .dark .payment-details {
            background-color: #374151;
        }
        
        .qr-code {
            width: 200px;
            height: 200px;
            margin: 0 auto 16px;
            border-radius: 8px;
        }
        
        .bank-details {
            text-align: left;
            background-color: white;
            border-radius: 8px;
            padding: 16px;
            margin-top: 16px;
        }
        
        .dark .bank-details {
            background-color: #1f2937;
        }
        
        .copy-btn {
            background-color: #4ECDC4;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 4px 8px;
            font-size: 12px;
            cursor: pointer;
            margin-left: 8px;
        }
        
        .copy-btn:hover {
            background-color: #45B7AA;
        }
        
        /* Campaign Card */
        .campaign-card {
            background-color: white;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .dark .campaign-card {
            background-color: #1f2937;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen transition-colors duration-300">
    <!-- Header -->
    <div class="bg-white shadow-sm px-4 py-4 flex items-center justify-center relative transition-colors duration-300">
        <a href="{{ route('donate', ['campaign_id' => $campaign['id']]) }}" class="absolute left-4 p-2 hover:bg-gray-100 rounded-full transition-colors">
            <i class="fas fa-arrow-left text-gray-600 text-xl"></i>
        </a>
        <h1 class="text-xl font-semibold text-gray-800">{{ session('locale') == 'en' ? 'Donation' : 'Donasi' }}</h1>
    </div>

    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Campaign Info Card -->
        <div class="campaign-card">
            <div class="flex items-center space-x-4">
                <img src="{{ $campaign['image'] }}" alt="{{ $campaign['title'] }}" class="w-16 h-16 rounded-lg object-cover">
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-800">{{ $campaign['title'] }}</h3>
                    <p class="text-sm text-gray-600">{{ session('locale') == 'en' ? 'Donation Amount:' : 'Jumlah Donasi:' }} <span class="font-semibold text-custom-teal">Rp {{ number_format($amount, 0, ',', '.') }}</span></p>
                </div>
            </div>
        </div>

        <div class="md:flex md:space-x-8">
            <!-- Left Side - Payment Methods -->
            <div class="md:w-1/2 mb-8 md:mb-0">
                <h2 class="text-lg font-medium text-gray-800 mb-4">{{ session('locale') == 'en' ? 'Payment Method' : 'Metode Pembayaran' }}</h2>
                
                <div class="space-y-3">
                    @foreach($paymentMethods as $method)
                    <div class="payment-method flex items-center justify-between {{ $method['id'] == $selectedMethod ? 'selected' : '' }}">
                        <div class="flex items-center">
                            @if($method['logo'])
                            <img 
                                src="{{ $method['logo'] }}" 
                                alt="{{ $method['name'] }}" 
                                class="h-8 w-auto mr-3 object-contain"
                            >
                            @else
                            <div class="w-8 h-8 bg-custom-teal rounded mr-3 flex items-center justify-center">
                                <i class="fas fa-wallet text-white text-xs"></i>
                            </div>
                            @endif
                            <span class="font-medium text-gray-800">{{ $method['name'] }}</span>
                        </div>
                        <div class="payment-radio {{ $method['id'] == $selectedMethod ? 'selected' : '' }}"></div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Side - Payment Details -->
            <div class="md:w-1/2">
                <div class="payment-details">
                    <h3 class="text-lg font-medium text-gray-800 mb-2">{{ session('locale') == 'en' ? 'Payment Details' : 'Detail Pembayaran' }}</h3>
                    <p class="text-2xl font-bold text-gray-800 mb-6">Rp {{ number_format($amount, 0, ',', '.') }}</p>
                    
                    @if($selectedMethod == 'qris')
                        <!-- QRIS Payment -->
                        <img src="/images/qris-sample.jpg" alt="QRIS Code" class="qr-code">
                        <p class="text-gray-600">{{ session('locale') == 'en' ? 'Please scan this QR for donation' : 'Silakan scan QR ini untuk donasi' }}</p>
                    @elseif($selectedMethod == 'balance')
                        <!-- Balance Payment -->
                        <div class="bank-details">
                            <div class="text-center mb-4">
                                <i class="fas fa-wallet text-custom-teal text-4xl mb-2"></i>
                                <h4 class="font-semibold text-gray-800">{{ session('locale') == 'en' ? 'Pay with Bantu.In Balance' : 'Bayar dengan Saldo Bantu.In' }}</h4>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">{{ session('locale') == 'en' ? 'Payment Method:' : 'Metode Pembayaran:' }}</span>
                                    <span class="font-medium text-gray-800">Saldo Bantu.In</span>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">{{ session('locale') == 'en' ? 'Amount:' : 'Jumlah:' }}</span>
                                    <span class="font-medium text-gray-800">Rp {{ number_format($donationData['amount'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                            
                            <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                                <p class="text-sm text-green-800">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    {{ session('locale') == 'en' ? 'Payment will be deducted from your Bantu.In balance automatically.' : 'Pembayaran akan dipotong dari saldo Bantu.In Anda secara otomatis.' }}
                                </p>
                            </div>
                        </div>
                    @else
                        <!-- Bank Transfer Payment -->
                        <div class="bank-details">
                            <h4 class="font-semibold text-gray-800 mb-3">{{ session('locale') == 'en' ? 'Transfer to:' : 'Transfer ke:' }}</h4>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">{{ session('locale') == 'en' ? 'Bank Name:' : 'Nama Bank:' }}</span>
                                    <span class="font-medium text-gray-800">{{ $selectedMethod['name'] }}</span>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">{{ session('locale') == 'en' ? 'Account Number:' : 'Nomor Rekening:' }}</span>
                                    <div class="flex items-center">
                                        <span class="font-medium text-gray-800" id="accountNumber">{{ $selectedMethod['account'] }}</span>
                                        <button class="copy-btn" onclick="copyToClipboard('{{ $selectedMethod['account'] }}')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">{{ session('locale') == 'en' ? 'Account Holder:' : 'Nama Penerima:' }}</span>
                                    <span class="font-medium text-gray-800">{{ $selectedMethod['holder'] }}</span>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">{{ session('locale') == 'en' ? 'Amount:' : 'Jumlah:' }}</span>
                                    <div class="flex items-center">
                                        <span class="font-medium text-gray-800" id="transferAmount">Rp {{ number_format($donationData['amount'], 0, ',', '.') }}</span>
                                        <button class="copy-btn" onclick="copyToClipboard('{{ $donationData['amount'] }}')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-sm text-yellow-800">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    {{ session('locale') == 'en' ? 'Please transfer the exact amount to ensure automatic verification.' : 'Harap transfer dengan jumlah yang tepat untuk verifikasi otomatis.' }}
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex space-x-4">
            <a href="{{ route('donate', ['campaign_id' => $campaign['id']]) }}" class="flex-1 border border-gray-300 text-gray-700 text-center py-4 px-6 rounded-xl font-semibold hover:bg-gray-50 transition-colors">
                {{ session('locale') == 'en' ? 'Change Method' : 'Ubah Metode' }}
            </a>
            <form action="{{ route('donate.confirm') }}" method="POST" class="flex-1" id="confirmForm">
                @csrf
                <input type="hidden" name="amount" value="{{ $amount }}">
                <button type="submit" class="submit-btn w-full text-white text-center" id="confirmButton">
                    @if($selectedMethod == 'qris')
                        {{ session('locale') == 'en' ? 'I have scanned QR' : 'Saya sudah scan QR' }}
                    @else
                        {{ session('locale') == 'en' ? 'I have transferred' : 'Saya sudah transfer' }}
                    @endif
                </button>
            </form>
        </div>
    </div>

    <!-- Bantu.In Logo -->
    <div class="fixed bottom-4 left-4">
        <h1 class="text-3xl font-bold text-custom-teal">Bantu.In</h1>
    </div>

    <script>
        // Get locale from PHP
        const currentLocale = "{{ session('locale', 'id') }}";
        
        document.addEventListener('DOMContentLoaded', function() {
            // Apply dark mode on page load
            const isDarkMode = localStorage.getItem('darkMode') === 'true';
            if (isDarkMode) {
                document.documentElement.classList.add('dark');
                document.body.classList.add('dark');
            }

            // Handle form submission
            const confirmForm = document.getElementById('confirmForm');
            const confirmButton = document.getElementById('confirmButton');

            confirmForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Disable button and show loading state
                confirmButton.disabled = true;
                confirmButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>' + 
                    (currentLocale === 'en' ? 'Processing...' : 'Memproses...');

                // Submit the form
                this.submit();
            });
        });

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                showToast(currentLocale === 'en' ? 'Copied to clipboard!' : 'Disalin ke clipboard!');
            }, function(err) {
                console.error('Could not copy text: ', err);
            });
        }

        function showToast(message) {
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
            toast.textContent = message;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);
            
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);
        }
    </script>
</body>
</html>
