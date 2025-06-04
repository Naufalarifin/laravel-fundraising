<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantu.In - Edit Profile</title>
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
        
        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .form-input {
            transition: all 0.3s ease;
        }
        
        .form-input:focus {
            border-color: #4ECDC4;
            box-shadow: 0 0 0 3px rgba(78, 205, 196, 0.1);
        }
        
        .save-btn {
            background: linear-gradient(135deg, #4ECDC4 0%, #45B7AA 100%);
        }
        
        .save-btn:hover {
            background: linear-gradient(135deg, #45B7AA 0%, #3DA89C 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(78, 205, 196, 0.3);
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
        
        .dark .border-gray-300 {
            border-color: #4b5563 !important;
        }
        
        .dark .border-gray-200 {
            border-color: #374151 !important;
        }
        
        .dark .shadow-sm {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.3) !important;
        }
        
        .dark .form-input {
            background-color: #374151 !important;
            color: #f9fafb !important;
        }
        
        .dark .form-input:focus {
            background-color: #4b5563 !important;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen transition-colors duration-300">
    <!-- Header -->
    <div class="bg-white shadow-sm px-4 py-4 flex items-center justify-center relative transition-colors duration-300">
        <a href="{{ route('account') }}" class="absolute left-4 p-2 hover:bg-gray-100 rounded-full transition-colors">
            <i class="fas fa-arrow-left text-gray-600 text-xl"></i>
        </a>
        <h1 class="text-xl font-semibold text-gray-800">Edit Profile</h1>
    </div>

    <div class="container mx-auto px-4 py-8 max-w-md">
        <!-- Error Messages -->
        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Profile Form -->
        <form action="{{ route('account.update') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Profile Picture -->
            <div class="flex justify-center mb-8">
                <div class="relative">
                    <img src="{{ $user['avatar'] }}" 
                         alt="Profile Picture" 
                         class="profile-image border-4 border-white shadow-lg">
                    <button type="button" class="absolute bottom-0 right-0 bg-custom-teal text-white p-2 rounded-full hover:bg-custom-teal transition-colors">
                        <i class="fas fa-camera text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Name Field -->
            <div class="space-y-2">
                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', $user['name']) }}"
                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none bg-white transition-colors duration-300"
                    placeholder="Enter your full name"
                    required
                >
            </div>

            <!-- Email Field -->
            <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <div class="relative">
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email', $user['email']) }}"
                        class="form-input w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:outline-none bg-white transition-colors duration-300"
                        placeholder="Enter your email"
                        required
                    >
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Phone Number Field -->
            <div class="space-y-2">
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                <div class="flex space-x-2">
                    <select 
                        name="country_code" 
                        class="form-input px-3 py-3 border border-gray-300 rounded-xl focus:outline-none bg-white transition-colors duration-300"
                        required
                    >
                        @foreach($countryCodes as $code => $label)
                        <option value="{{ $code }}" {{ old('country_code', $user['country_code']) == $code ? 'selected' : '' }}>
                            {{ $code }}
                        </option>
                        @endforeach
                    </select>
                    <input 
                        type="tel" 
                        id="phone_number" 
                        name="phone_number" 
                        value="{{ old('phone_number', $user['phone_number']) }}"
                        class="form-input flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:outline-none bg-white transition-colors duration-300"
                        placeholder="628445578293"
                        required
                    >
                </div>
            </div>

            <!-- Gender Field -->
            <div class="space-y-2">
                <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                <div class="relative">
                    <select 
                        id="gender" 
                        name="gender" 
                        class="form-input w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:outline-none bg-white appearance-none transition-colors duration-300"
                        required
                    >
                        @foreach($genders as $value => $label)
                        <option value="{{ $value }}" {{ old('gender', $user['gender']) == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="pt-6">
                <button 
                    type="submit" 
                    class="save-btn w-full text-white font-semibold py-4 px-6 rounded-xl transition-all duration-300"
                >
                    <i class="fas fa-save mr-2"></i>
                    Save Changes
                </button>
            </div>
        </form>
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
            
            const form = document.querySelector('form');
            const inputs = document.querySelectorAll('.form-input');
            
            // Add focus effects
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-blue-200');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-blue-200');
                });
            });
            
            // Form submission
            form.addEventListener('submit', function(e) {
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
                submitBtn.disabled = true;
            });
            
            // Phone number formatting
            const phoneInput = document.getElementById('phone_number');
            phoneInput.addEventListener('input', function(e) {
                // Remove non-numeric characters
                let value = e.target.value.replace(/\D/g, '');
                e.target.value = value;
            });
        });
    </script>
</body>
</html>
