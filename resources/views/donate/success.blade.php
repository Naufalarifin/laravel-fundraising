<!DOCTYPE html>
<html lang="{{ session('locale', 'id') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantu.In - {{ session('locale') == 'en' ? 'Donation Success' : 'Donasi Berhasil' }}</title>
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
    </style>
</head>
<body class="bg-gray-50 min-h-screen transition-colors duration-300">
    <div class="container mx-auto px-4 py-12 max-w-md">
        <div class="bg-white rounded-lg shadow-sm p-8 text-center transition-colors duration-300">
            <!-- Success Icon -->
            <div class="success-checkmark mb-6">
                <i class="fas fa-check"></i>
            </div>
            
            <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ session('locale') == 'en' ? 'Donation Successful!' : 'Donasi Berhasil!' }}</h2>
            <p class="text-gray-600 mb-8">{{ session('locale') == 'en' ? 'Thank you for your generous donation.' : 'Terima kasih atas donasi Anda yang mulia.' }}</p>
            
            <!-- Action Button -->
            <a href="{{ route('dashboard') }}" class="action-btn w-full block text-white text-center">
                {{ session('locale') == 'en' ? 'Back to Dashboard' : 'Kembali ke Dashboard' }}
            </a>
        </div>
    </div>

    <!-- Bantu.In Logo -->
    <div class="fixed bottom-4 left-4">
        <h1 class="text-3xl font-bold text-custom-teal">Bantu.In</h1>
    </div>
</body>
</html>
