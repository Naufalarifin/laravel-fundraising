<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantu.In - Change Password</title>
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
        
        .save-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .password-strength {
            height: 4px;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        
        .strength-weak { background-color: #ef4444; }
        .strength-medium { background-color: #f59e0b; }
        .strength-strong { background-color: #10b981; }
        
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
        <h1 class="text-xl font-semibold text-gray-800">Change Password</h1>
    </div>

    <div class="container mx-auto px-4 py-8 max-w-md">
        <!-- Error Messages -->
        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span class="font-semibold">Terjadi kesalahan:</span>
            </div>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- User Info Card -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6 flex items-center space-x-3 transition-colors duration-300">
            <img src="{{ $user['avatar'] }}" 
                 alt="Profile Picture" 
                 class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
            <div>
                <h3 class="font-semibold text-gray-800">{{ $user['name'] }}</h3>
                <p class="text-sm text-gray-600">{{ $user['email'] }}</p>
            </div>
        </div>

        <!-- Change Password Form -->
        <div class="bg-white rounded-lg shadow-sm p-6 transition-colors duration-300">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-key text-blue-500"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Update Password</h2>
                    <p class="text-sm text-gray-600">Pastikan password baru Anda aman</p>
                </div>
            </div>

            <form action="{{ route('account.updatePassword') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Current Password -->
                <div class="space-y-2">
                    <label for="current_password" class="block text-sm font-medium text-gray-700">
                        Password Saat Ini
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="current_password" 
                            name="current_password" 
                            class="form-input w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:outline-none bg-white transition-colors duration-300"
                            placeholder="Masukkan password saat ini"
                            required
                        >
                        <button type="button" class="absolute inset-y-0 right-0 pr-4 flex items-center toggle-password" data-target="current_password">
                            <i class="fas fa-eye text-gray-400"></i>
                        </button>
                    </div>
                </div>

                <!-- New Password -->
                <div class="space-y-2">
                    <label for="new_password" class="block text-sm font-medium text-gray-700">
                        Password Baru
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="new_password" 
                            name="new_password" 
                            class="form-input w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:outline-none bg-white transition-colors duration-300"
                            placeholder="Masukkan password baru"
                            required
                        >
                        <button type="button" class="absolute inset-y-0 right-0 pr-4 flex items-center toggle-password" data-target="new_password">
                            <i class="fas fa-eye text-gray-400"></i>
                        </button>
                    </div>
                    <!-- Password Strength Indicator -->
                    <div class="space-y-1">
                        <div class="password-strength w-full bg-gray-200" id="passwordStrength"></div>
                        <p class="text-xs text-gray-500" id="passwordStrengthText">Minimal 8 karakter</p>
                    </div>
                </div>

                <!-- Confirm New Password -->
                <div class="space-y-2">
                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">
                        Konfirmasi Password Baru
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="new_password_confirmation" 
                            name="new_password_confirmation" 
                            class="form-input w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:outline-none bg-white transition-colors duration-300"
                            placeholder="Konfirmasi password baru"
                            required
                        >
                        <button type="button" class="absolute inset-y-0 right-0 pr-4 flex items-center toggle-password" data-target="new_password_confirmation">
                            <i class="fas fa-eye text-gray-400"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500" id="passwordMatch"></p>
                </div>

                <!-- Password Requirements -->
                <div class="bg-gray-50 rounded-lg p-4 transition-colors duration-300">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Syarat Password:</h4>
                    <ul class="text-xs text-gray-600 space-y-1">
                        <li class="flex items-center" id="req-length">
                            <i class="fas fa-circle text-gray-300 mr-2 text-xs"></i>
                            Minimal 8 karakter
                        </li>
                        <li class="flex items-center" id="req-uppercase">
                            <i class="fas fa-circle text-gray-300 mr-2 text-xs"></i>
                            Mengandung huruf besar
                        </li>
                        <li class="flex items-center" id="req-lowercase">
                            <i class="fas fa-circle text-gray-300 mr-2 text-xs"></i>
                            Mengandung huruf kecil
                        </li>
                        <li class="flex items-center" id="req-number">
                            <i class="fas fa-circle text-gray-300 mr-2 text-xs"></i>
                            Mengandung angka
                        </li>
                    </ul>
                </div>

                <!-- Update Button -->
                <div class="pt-4">
                    <button 
                        type="submit" 
                        class="save-btn w-full text-white font-semibold py-4 px-6 rounded-xl transition-all duration-300"
                        id="updateBtn"
                    >
                        <i class="fas fa-shield-alt mr-2"></i>
                        Update Password
                    </button>
                </div>
            </form>
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
            
            const currentPasswordInput = document.getElementById('current_password');
            const newPasswordInput = document.getElementById('new_password');
            const confirmPasswordInput = document.getElementById('new_password_confirmation');
            const passwordStrength = document.getElementById('passwordStrength');
            const passwordStrengthText = document.getElementById('passwordStrengthText');
            const passwordMatch = document.getElementById('passwordMatch');
            const updateBtn = document.getElementById('updateBtn');

            // Initially disable button
            updateBtn.disabled = true;

            // Toggle password visibility
            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const targetInput = document.getElementById(targetId);
                    const icon = this.querySelector('i');
                    
                    if (targetInput.type === 'password') {
                        targetInput.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        targetInput.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });

            // Add event listeners
            currentPasswordInput.addEventListener('input', validateForm);
            newPasswordInput.addEventListener('input', function() {
                const password = this.value;
                const strength = checkPasswordStrength(password);
                updatePasswordStrength(strength);
                checkPasswordRequirements(password);
                validateForm();
            });
            confirmPasswordInput.addEventListener('input', function() {
                checkPasswordMatch();
                validateForm();
            });

            function checkPasswordStrength(password) {
                let score = 0;
                if (password.length >= 8) score++;
                if (/[a-z]/.test(password)) score++;
                if (/[A-Z]/.test(password)) score++;
                if (/[0-9]/.test(password)) score++;
                if (/[^A-Za-z0-9]/.test(password)) score++;
                
                return score;
            }

            function updatePasswordStrength(strength) {
                passwordStrength.className = 'password-strength w-full';
                
                if (strength < 2) {
                    passwordStrength.classList.add('strength-weak');
                    passwordStrengthText.textContent = 'Password lemah';
                    passwordStrengthText.className = 'text-xs text-red-500';
                } else if (strength < 4) {
                    passwordStrength.classList.add('strength-medium');
                    passwordStrengthText.textContent = 'Password sedang';
                    passwordStrengthText.className = 'text-xs text-yellow-500';
                } else {
                    passwordStrength.classList.add('strength-strong');
                    passwordStrengthText.textContent = 'Password kuat';
                    passwordStrengthText.className = 'text-xs text-green-500';
                }
            }

            function checkPasswordRequirements(password) {
                const requirements = [
                    { id: 'req-length', test: password.length >= 8 },
                    { id: 'req-uppercase', test: /[A-Z]/.test(password) },
                    { id: 'req-lowercase', test: /[a-z]/.test(password) },
                    { id: 'req-number', test: /[0-9]/.test(password) }
                ];

                requirements.forEach(req => {
                    const element = document.getElementById(req.id);
                    const icon = element.querySelector('i');
                    
                    if (req.test) {
                        icon.className = 'fas fa-check-circle text-green-500 mr-2 text-xs';
                        element.classList.add('text-green-600');
                        element.classList.remove('text-gray-600');
                    } else {
                        icon.className = 'fas fa-circle text-gray-300 mr-2 text-xs';
                        element.classList.remove('text-green-600');
                        element.classList.add('text-gray-600');
                    }
                });
            }

            function checkPasswordMatch() {
                const newPassword = newPasswordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                
                if (confirmPassword === '') {
                    passwordMatch.textContent = '';
                    return false;
                }
                
                if (newPassword === confirmPassword) {
                    passwordMatch.textContent = '✓ Password cocok';
                    passwordMatch.className = 'text-xs text-green-500';
                    return true;
                } else {
                    passwordMatch.textContent = '✗ Password tidak cocok';
                    passwordMatch.className = 'text-xs text-red-500';
                    return false;
                }
            }

            function validateForm() {
                const currentPassword = currentPasswordInput.value.trim();
                const newPassword = newPasswordInput.value.trim();
                const confirmPassword = confirmPasswordInput.value.trim();
                
                // Check all conditions
                const currentPasswordValid = currentPassword.length >= 8;
                const newPasswordValid = newPassword.length >= 8;
                const passwordsMatch = newPassword === confirmPassword && confirmPassword !== '';
                const passwordStrong = checkPasswordStrength(newPassword) >= 2;
                
                const isValid = currentPasswordValid && newPasswordValid && passwordsMatch && passwordStrong;
                
                updateBtn.disabled = !isValid;
                
                if (isValid) {
                    updateBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    updateBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }

            // Form submission
            document.querySelector('form').addEventListener('submit', function(e) {
                if (updateBtn.disabled) {
                    e.preventDefault();
                    return;
                }
                updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';
                updateBtn.disabled = true;
            });
        });
    </script>
</body>
</html>
