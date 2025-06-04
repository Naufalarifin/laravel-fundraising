<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantu.In - Forgot Password</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        .bg-teal-400 {
            background-color: #4ECDC4 !important;
        }
        .bg-teal-500 {
            background-color: #45B7AA !important;
        }
        .text-teal-500 {
            color: #4ECDC4 !important;
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
    </style>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md space-y-8">
        <!-- Logo/Brand -->
        <div class="text-center">
            <h1 class="text-4xl font-bold text-teal-500">Bantu.In</h1>
            <h2 class="mt-4 text-2xl font-semibold text-gray-800">Forgot Password</h2>
            <p class="mt-2 text-gray-600">Enter your email to reset your password</p>
        </div>

        <!-- Forgot Password Form -->
        <form action="#" method="POST" class="space-y-6">
            @csrf
            
            <!-- Email Field -->
            <div class="space-y-2">
                <input
                    type="email"
                    name="email"
                    placeholder="Enter your email"
                    class="w-full h-12 px-4 border border-gray-300 rounded-lg bg-white placeholder:text-gray-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500 focus:outline-none transition-colors"
                    required
                />
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full h-12 bg-teal-400 hover:bg-teal-500 text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2"
            >
                Send Reset Link
            </button>

            <!-- Back to Sign In -->
            <div class="text-center">
                <a href="{{ route('sign-in') }}" class="text-gray-600 hover:underline">
                    ← Back to Sign In
                </a>
            </div>
        </form>
    </div>
</body>
</html>
