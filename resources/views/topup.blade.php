<!DOCTYPE html>
<html lang="{{ session('locale', 'id') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantu.In - {{ session('locale') == 'en' ? 'Top up' : 'Top up' }}</title>
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
        
        /* Custom Input Styles */
        .amount-input {
            font-size: 24px;
            font-weight: 600;
            background-color: #F3F4F6;
            border-radius: 8px;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }
        
        .dark .amount-input {
            background-color: #374151;
            color: #f9fafb;
        }
        
        .amount-input:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(78, 205, 196, 0.3);
        }
        
        /* Quick Amount Button Styles */
        .quick-amount {
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .quick-amount:hover {
            border-color: #4ECDC4;
            color: #4ECDC4;
        }
        
        .dark .quick-amount {
            border-color: #4B5563;
        }
        
        .dark .quick-amount:hover {
            border-color: #4ECDC4;
        }
        
        /* Payment Method Styles */
        .payment-method {
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            padding: 16px;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .payment-method:hover {
            border-color: #4ECDC4;
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
        
        .submit-btn:disabled {
            opacity: 0.7;
            transform: none;
            box-shadow: none;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen transition-colors duration-300">
    <!-- Header -->
    <div class="bg-white shadow-sm px-4 py-4 flex items-center justify-center relative transition-colors duration-300">
        <a href="{{ route('dashboard') }}" class="absolute left-4 p-2 hover:bg-gray-100 rounded-full transition-colors">
            <i class="fas fa-arrow-left text-gray-600 text-xl"></i>
        </a>
        <h1 class="text-xl font-semibold text-gray-800">{{ session('locale') == 'en' ? 'Top up' : 'Top up' }}</h1>
    </div>

    <div class="container mx-auto px-4 py-8 max-w-md">
        <!-- Error Messages -->
        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('topup.process') }}" method="POST" id="topupForm">
            @csrf
            
            <!-- Amount Input Section -->
            <div class="mb-8">
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">{{ session('locale') == 'en' ? 'Add' : 'Tambah' }}</label>
                <div class="flex items-center amount-input mb-4">
                    <span class="text-gray-500 mr-2">Rp</span>
                    <input 
                        type="text" 
                        name="amount" 
                        id="amount" 
                        value="{{ old('amount', '200000') }}"
                        class="bg-transparent border-none w-full focus:outline-none"
                        inputmode="numeric"
                    >
                </div>
                
                <!-- Quick Amount Buttons -->
                <div class="grid grid-cols-3 gap-3">
                    @foreach($quickAmounts as $amount)
                    <button 
                        type="button" 
                        class="quick-amount text-gray-700"
                        data-amount="{{ $amount }}"
                    >
                        Rp {{ number_format($amount, 0, ',', '.') }}
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Payment Method Section -->
            <div class="mb-8">
                <h2 class="text-lg font-medium text-gray-800 mb-4">{{ session('locale') == 'en' ? 'Select Payment Method' : 'Pilih Metode Pembayaran' }}</h2>
                
                <div class="space-y-3">
                    @foreach($paymentMethods as $method)
                    <div class="payment-method flex items-center justify-between" data-method="{{ $method['id'] }}">
                        <div class="flex items-center">
                            <img 
                                src="{{ $method['logo'] }}" 
                                alt="{{ $method['name'] }}" 
                                class="h-8 w-auto mr-3 object-contain"
                            >
                            <span class="font-medium text-gray-800">{{ $method['name'] }}</span>
                        </div>
                        <div class="payment-radio {{ $method['id'] == 'qris' ? 'selected' : '' }}"></div>
                        <input 
                            type="radio" 
                            name="payment_method" 
                            value="{{ $method['id'] }}" 
                            class="hidden"
                            {{ $method['id'] == 'qris' ? 'checked' : '' }}
                        >
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="submit-btn w-full text-white text-center"
                id="submitBtn"
            >
                {{ session('locale') == 'en' ? 'Top up now' : 'Top up sekarang' }}
            </button>
        </form>
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
            
            // Format amount input with thousand separator
            const amountInput = document.getElementById('amount');
            
            function formatAmount(value) {
                // Remove non-numeric characters
                value = value.replace(/\D/g, '');
                
                // Format with thousand separator
                if (value === '') return '';
                return new Intl.NumberFormat('id-ID').format(value);
            }
            
            function unformatAmount(value) {
                return value.replace(/\D/g, '');
            }
            
            // Format initial value
            amountInput.value = formatAmount(amountInput.value);
            
            // Format on input
            amountInput.addEventListener('input', function(e) {
                const cursorPosition = this.selectionStart;
                const originalLength = this.value.length;
                
                const unformattedValue = unformatAmount(this.value);
                const formattedValue = formatAmount(unformattedValue);
                
                this.value = formattedValue;
                
                // Adjust cursor position after formatting
                const newLength = this.value.length;
                const newPosition = cursorPosition + (newLength - originalLength);
                this.setSelectionRange(newPosition, newPosition);
            });
            
            // Quick amount buttons
            const quickAmountButtons = document.querySelectorAll('.quick-amount');
            quickAmountButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const amount = this.getAttribute('data-amount');
                    amountInput.value = formatAmount(amount);
                });
            });
            
            // Payment method selection
            const paymentMethods = document.querySelectorAll('.payment-method');
            paymentMethods.forEach(method => {
                method.addEventListener('click', function() {
                    // Remove selected class from all methods
                    paymentMethods.forEach(m => {
                        m.classList.remove('selected');
                        m.querySelector('.payment-radio').classList.remove('selected');
                    });
                    
                    // Add selected class to clicked method
                    this.classList.add('selected');
                    this.querySelector('.payment-radio').classList.add('selected');
                    
                    // Check the radio button
                    const radio = this.querySelector('input[type="radio"]');
                    radio.checked = true;
                });
            });
            
            // Form submission
            const form = document.getElementById('topupForm');
            const submitBtn = document.getElementById('submitBtn');
            
            form.addEventListener('submit', function(e) {
                // Unformat amount before submission
                const amount = unformatAmount(amountInput.value);
                amountInput.value = amount;
                
                // Disable submit button
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>' + 
                    (localStorage.getItem('language') === 'en' ? 'Processing...' : 'Memproses...');
            });
        });
    </script>
</body>
</html>
