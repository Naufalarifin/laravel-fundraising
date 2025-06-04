<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Bantu.In - Sign Up</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .bg-teal-400 {
            background-color: #4ECDC4 !important;
        }
        .bg-teal-500 {
            background-color: #45B7AA !important;
        }
        .text-teal-400 {
            color: #4ECDC4 !important;
        }
        .text-teal-500 {
            color: #4ECDC4 !important;
        }
        .border-teal-400 {
            border-color: #4ECDC4 !important;
        }
        .border-teal-500 {
            border-color: #4ECDC4 !important;
        }
        .ring-teal-500 {
            --tw-ring-color: rgba(78, 205, 196, 0.5) !important;
        }
        .focus\:border-teal-500:focus {
            border-color: #4ECDC4 !important;
        }
        .focus\:ring-teal-500:focus {
            --tw-ring-color: rgba(78, 205, 196, 0.5) !important;
        }
        .hover\:bg-teal-500:hover {
            background-color: #45B7AA !important;
        }
        .hover\:text-teal-500:hover {
            color: #45B7AA !important;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Error Messages -->
        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('sign-up.post') }}" method="POST" class="bg-white rounded-lg shadow-sm p-8">
            @csrf
            <!-- Logo/Brand -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-teal-500">Bantu.In</h1>
            </div>

            <div class="space-y-6">
                <!-- Name Field -->
                <div class="space-y-2">
                    <input
                        type="text"
                        name="name"
                        placeholder="Mohammad Salah"
                        value="{{ old('name') }}"
                        class="w-full h-12 px-4 border border-gray-300 rounded-lg bg-white placeholder:text-gray-600 focus:border-teal-500 focus:ring-2 focus:ring-teal-500 focus:outline-none transition-colors"
                        required
                    />
                </div>

                <!-- Email Field -->
                <div class="space-y-2">
                    <input
                        type="email"
                        name="email"
                        placeholder="mosalah@gmail.com"
                        value="{{ old('email') }}"
                        class="w-full h-12 px-4 border border-gray-300 rounded-lg bg-white placeholder:text-gray-600 focus:border-teal-500 focus:ring-2 focus:ring-teal-500 focus:outline-none transition-colors"
                        required
                    />
                </div>

                <!-- Password Field -->
                <div class="space-y-2">
                    <div class="relative">
                        <input
                            type="password"
                            name="password"
                            placeholder="••••••••"
                            class="w-full h-12 px-4 pr-12 border border-gray-300 rounded-lg bg-white placeholder:text-gray-600 focus:border-teal-500 focus:ring-2 focus:ring-teal-500 focus:outline-none transition-colors"
                            required
                        />
                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                        >
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Terms Checkbox -->
                <div class="flex items-center space-x-3">
                    <input
                        type="checkbox"
                        id="terms"
                        name="agree_to_terms"
                        value="1"
                        class="w-4 h-4 text-teal-500 border-gray-300 rounded focus:ring-teal-500"
                        required
                    />
                    <label for="terms" class="text-sm text-gray-600">
                        I agree to the
                        <a href="{{ route('terms') }}" class="text-teal-400 hover:underline">
                            Terms of Service
                        </a>
                        and
                        <a href="{{ route('privacy') }}" class="text-teal-400 hover:underline">
                            Privacy Policy
                        </a>
                    </label>
                </div>

                <!-- Sign Up Button -->
                <button
                    type="submit"
                    class="w-full h-12 bg-teal-400 hover:bg-teal-500 text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2"
                >
                    Sign Up
                </button>

                <!-- Sign In Link -->
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account?
                        <a href="{{ route('sign-in') }}" class="text-gray-800 font-medium hover:underline transition-colors">
                            Sign in
                        </a>
                    </p>
                </div>
            </div>
        </form>

        <!-- Bottom Logo -->
        <div class="absolute bottom-8 left-8">
            <h1 class="text-2xl font-bold text-teal-500">Bantu.In</h1>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.querySelector('input[name="password"]');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
