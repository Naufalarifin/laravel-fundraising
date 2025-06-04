<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantu.In - Donation made Easy</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
        
        .bg-custom-gradient {
            background: linear-gradient(135deg, #4ECDC4 0%, #45B7AA 100%);
        }
        
        .btn-start {
            background-color: white;
            color: #374151;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn-start:hover {
            background-color: #f9fafb;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body class="min-h-screen bg-custom-gradient flex items-center justify-center px-4 py-8">
    <div class="max-w-md w-full text-center space-y-8">
        <!-- Logo/Title -->
        <h1 class="text-5xl font-bold text-white mb-12">Bantu.In</h1>

        <!-- Hero Image -->
        <div class="flex justify-center mb-8">
            <img 
                src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=320&h=240&fit=crop" 
                alt="Happy children smiling together" 
                class="w-80 h-60 rounded-2xl object-cover shadow-lg"
            />
        </div>

        <!-- Content Section -->
        <div class="space-y-6">
            <h2 class="text-2xl font-semibold text-white">Donation made Easy</h2>
            <p class="text-white text-lg leading-relaxed opacity-95">
                Bantu.in is a platform for philanthropists to make donations to various social programmes and charities around the world.
            </p>
        </div>

        <!-- Call to Action Button -->
        <div class="pt-8">
            <button 
                onclick="startDonating()" 
                class="btn-start px-8 py-3 text-lg font-semibold rounded-full border-none cursor-pointer"
            >
                Start donating
            </button>
        </div>
    </div>

    <script>
        function startDonating() {
            window.location.href = '{{ route('sign-up') }}';
        }
    </script>
</body>
</html>
