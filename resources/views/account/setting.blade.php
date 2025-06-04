<!DOCTYPE html>
<html lang="{{ session('locale', 'id') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantu.In - {{ session('locale') == 'en' ? 'Settings' : 'Pengaturan' }}</title>
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
        
        /* Toggle Switch Styles */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }
        
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 24px;
        }
        
        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        
        input:checked + .toggle-slider {
            background-color: #4ECDC4;
        }
        
        input:checked + .toggle-slider:before {
            transform: translateX(26px);
        }
        
        .dark .toggle-slider {
            background-color: #4b5563;
        }
        
        .dark input:checked + .toggle-slider {
            background-color: #4ECDC4;
        }
        
        .menu-item:hover {
            background-color: rgba(0, 0, 0, 0.05);
            transform: translateX(2px);
        }
        
        .dark .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        /* Language Selection Styles */
        .language-panel {
            position: fixed;
            top: 0;
            right: -100%;
            width: 50%;
            height: 100%;
            background-color: white;
            z-index: 50;
            transition: all 0.3s ease;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }
        
        .language-panel.active {
            right: 0;
        }
        
        .dark .language-panel {
            background-color: #1f2937;
        }
        
        .radio-container {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            cursor: pointer;
        }
        
        .dark .radio-container {
            border-bottom: 1px solid #374151;
        }
        
        .radio-container:last-child {
            border-bottom: none;
        }
        
        .radio-button {
            position: relative;
            width: 24px;
            height: 24px;
            border: 2px solid #d1d5db;
            border-radius: 50%;
            margin-right: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .radio-button.selected {
            border-color: #4ECDC4;
        }
        
        .radio-button.selected::after {
            content: "";
            width: 12px;
            height: 12px;
            background-color: #4ECDC4;
            border-radius: 50%;
        }
        
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 40;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .dark .overlay {
            background-color: rgba(0, 0, 0, 0.7);
        }
        
        .settings-container {
            transition: all 0.3s ease;
        }
        
        .settings-container.shifted {
            transform: translateX(-25%);
        }
        
        /* Delete Account Modal Styles */
        .delete-account-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.9);
            width: 90%;
            max-width: 500px;
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            z-index: 60;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .delete-account-modal.active {
            opacity: 1;
            visibility: visible;
            transform: translate(-50%, -50%) scale(1);
        }
        
        .dark .delete-account-modal {
            background-color: #1f2937;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
        }
        
        .modal-header {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            position: relative;
        }
        
        .dark .modal-header {
            border-bottom: 1px solid #374151;
        }
        
        .modal-body {
            padding: 2rem 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .modal-footer {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .profile-pic {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
        }
        
        .btn-cancel {
            background-color: #4ECDC4;
            color: white;
            padding: 0.75rem;
            border-radius: 12px;
            font-weight: 600;
            text-align: center;
            transition: all 0.2s ease;
        }
        
        .btn-cancel:hover {
            background-color: #45B7AA;
        }
        
        .btn-delete {
            border: 1px solid #e5e7eb;
            color: #4B5563;
            padding: 0.75rem;
            border-radius: 12px;
            font-weight: 600;
            text-align: center;
            transition: all 0.2s ease;
        }
        
        .btn-delete:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }
        
        .dark .btn-delete {
            border: 1px solid #374151;
            color: #D1D5DB;
        }
        
        .dark .btn-delete:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        /* Logout Modal Styles */
        .logout-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.9);
            width: 85%;
            max-width: 350px;
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            z-index: 60;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .logout-modal.active {
            opacity: 1;
            visibility: visible;
            transform: translate(-50%, -50%) scale(1);
        }

        .dark .logout-modal {
            background-color: #1f2937;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
        }

        .logout-modal-body {
            padding: 2rem 1.5rem;
            text-align: center;
        }

        .logout-modal-footer {
            padding: 0 1.5rem 1.5rem;
            display: flex;
            gap: 0.75rem;
        }

        .btn-logout-cancel {
            flex: 1;
            background-color: #4ECDC4;
            color: white;
            padding: 0.75rem;
            border-radius: 12px;
            font-weight: 600;
            text-align: center;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .btn-logout-cancel:hover {
            background-color: #45B7AA;
        }

        .btn-logout-confirm {
            flex: 1;
            border: 1px solid #e5e7eb;
            color: #4B5563;
            background-color: white;
            padding: 0.75rem;
            border-radius: 12px;
            font-weight: 600;
            text-align: center;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-logout-confirm:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .dark .btn-logout-confirm {
            border: 1px solid #374151;
            color: #D1D5DB;
            background-color: #1f2937;
        }

        .dark .btn-logout-confirm:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen transition-colors duration-300">
    <!-- Header -->
    <div class="bg-white shadow-sm px-4 py-4 flex items-center justify-center relative transition-colors duration-300">
        <a href="{{ route('account') }}" class="absolute left-4 p-2 hover:bg-gray-100 rounded-full transition-colors">
            <i class="fas fa-arrow-left text-gray-600 text-xl"></i>
        </a>
        <h1 class="text-xl font-semibold text-gray-800" id="pageTitle">{{ session('locale') == 'en' ? 'Settings' : 'Pengaturan' }}</h1>
    </div>

    <div class="container mx-auto px-4 py-8 max-w-md settings-container" id="settingsContainer">
        <!-- Success Message -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
        @endif

        <!-- Settings Menu -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden transition-colors duration-300">
            <!-- Language -->
            <div class="menu-item flex items-center justify-between p-4 border-b border-gray-100 cursor-pointer transition-all duration-200" id="languageMenuItem">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-globe text-blue-500"></i>
                    </div>
                    <span class="text-gray-800 font-medium" id="languageText">{{ session('locale') == 'en' ? 'Language' : 'Bahasa' }}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500" id="currentLanguage">{{ session('locale') == 'en' ? 'English' : 'Indonesia' }}</span>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </div>
            </div>

            <!-- Dark Mode -->
            <div class="menu-item flex items-center justify-between p-4 border-b border-gray-100 transition-all duration-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-moon text-purple-500"></i>
                    </div>
                    <span class="text-gray-800 font-medium" id="darkModeText">{{ session('locale') == 'en' ? 'Dark mode' : 'Mode gelap' }}</span>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" id="darkModeToggle">
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <!-- Donate as Anonymous -->
            <div class="menu-item flex items-center justify-between p-4 border-b border-gray-100 transition-all duration-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-secret text-green-500"></i>
                    </div>
                    <span class="text-gray-800 font-medium" id="anonymousText">{{ session('locale') == 'en' ? 'Donate as anonymous' : 'Donasi sebagai anonim' }}</span>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" id="anonymousToggle">
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <!-- Delete Account -->
            <div class="menu-item flex items-center justify-between p-4 border-b border-gray-100 cursor-pointer transition-all duration-200" id="deleteAccountItem">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-trash text-red-500"></i>
                    </div>
                    <span class="text-gray-800 font-medium" id="deleteAccountText">{{ session('locale') == 'en' ? 'Delete Account' : 'Hapus Akun' }}</span>
                </div>
                <i class="fas fa-chevron-right text-gray-400"></i>
            </div>

            <!-- Log Out -->
            <div class="menu-item flex items-center justify-between p-4 cursor-pointer transition-all duration-200" id="logoutItem">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-sign-out-alt text-orange-500"></i>
                    </div>
                    <span class="text-gray-800 font-medium" id="logoutText">{{ session('locale') == 'en' ? 'Log out' : 'Keluar' }}</span>
                </div>
                <i class="fas fa-external-link-alt text-gray-400"></i>
            </div>
        </div>
    </div>

    <!-- Language Selection Panel -->
    <div class="language-panel transition-colors duration-300" id="languagePanel">
        <div class="p-4 border-b border-gray-200 flex items-center">
            <button id="closeLanguagePanel" class="p-2 hover:bg-gray-100 rounded-full transition-colors mr-4">
                <i class="fas fa-arrow-left text-gray-600 text-xl"></i>
            </button>
            <h2 class="text-lg font-semibold text-gray-800" id="selectLanguageTitle">{{ session('locale') == 'en' ? 'Select language' : 'Pilih bahasa' }}</h2>
        </div>
        
        <div class="py-4">
            <div class="radio-container" data-lang="id">
                <div class="radio-button {{ session('locale') == 'id' ? 'selected' : '' }}"></div>
                <span class="text-gray-800">Indonesia</span>
            </div>
            <div class="radio-container" data-lang="en">
                <div class="radio-button {{ session('locale') == 'en' ? 'selected' : '' }}"></div>
                <span class="text-gray-800">English</span>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div class="delete-account-modal transition-colors duration-300" id="deleteAccountModal">
        <div class="modal-header">
            <button id="closeDeleteModal" class="p-2 hover:bg-gray-100 rounded-full transition-colors mr-4">
                <i class="fas fa-arrow-left text-gray-600 text-xl"></i>
            </button>
            <h2 class="text-lg font-semibold text-gray-800" id="deleteAccountTitle">{{ session('locale') == 'en' ? 'Delete Account' : 'Hapus Akun' }}</h2>
        </div>
        
        <div class="modal-body">
            <img src="{{ $user['avatar'] }}" alt="Profile Picture" class="profile-pic">
            <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $user['name'] }}</h3>
            <h4 class="text-lg font-medium text-gray-800 mb-3" id="deleteConfirmTitle">{{ session('locale') == 'en' ? 'Delete Your Account?' : 'Hapus Akun Anda?' }}</h4>
            <p class="text-gray-600 text-center max-w-xs" id="deleteConfirmText">
                {{ session('locale') == 'en' ? 'Your profile will be lose all your data by deleting your account. This action cannot be undone.' : 'Profil Anda akan kehilangan semua data dengan menghapus akun Anda. Tindakan ini tidak dapat dibatalkan.' }}
            </p>
        </div>
        
        <div class="modal-footer">
            <button id="cancelDelete" class="btn-cancel">
                {{ session('locale') == 'en' ? 'Cancel' : 'Batal' }}
            </button>
            <form action="{{ route('account.delete') }}" method="POST">
                @csrf
                <button type="submit" class="btn-delete w-full">
                    {{ session('locale') == 'en' ? 'Delete my account' : 'Hapus akun saya' }}
                </button>
            </form>
        </div>
    </div>

    <!-- Logout Modal -->
    <div class="logout-modal transition-colors duration-300" id="logoutModal">
        <div class="logout-modal-body">
            <h3 class="text-lg font-medium text-gray-800 mb-2" id="logoutConfirmTitle">
                {{ session('locale') == 'en' ? 'Are you sure you want to leave the app?' : 'Apakah Anda yakin ingin keluar dari aplikasi?' }}
            </h3>
        </div>
        
        <div class="logout-modal-footer">
            <button id="cancelLogout" class="btn-logout-cancel">
                {{ session('locale') == 'en' ? 'Cancel' : 'Batal' }}
            </button>
            <form action="{{ route('logout') }}" method="POST" style="flex: 1;">
                @csrf
                <button type="submit" class="btn-logout-confirm w-full">
                    {{ session('locale') == 'en' ? 'Log out' : 'Keluar' }}
                </button>
            </form>
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Bantu.In Logo -->
    <div class="fixed bottom-4 left-4">
        <h1 class="text-3xl font-bold text-custom-teal">Bantu.In</h1>
    </div>

    <script>
        // Language and Dark Mode Management
        class AppManager {
            constructor() {
                this.darkModeToggle = document.getElementById('darkModeToggle');
                this.anonymousToggle = document.getElementById('anonymousToggle');
                this.languageMenuItem = document.getElementById('languageMenuItem');
                this.languagePanel = document.getElementById('languagePanel');
                this.closeLanguagePanel = document.getElementById('closeLanguagePanel');
                this.overlay = document.getElementById('overlay');
                this.settingsContainer = document.getElementById('settingsContainer');
                this.radioContainers = document.querySelectorAll('.radio-container');
                this.deleteAccountItem = document.getElementById('deleteAccountItem');
                this.logoutItem = document.getElementById('logoutItem');
                this.deleteAccountModal = document.getElementById('deleteAccountModal');
                this.closeDeleteModal = document.getElementById('closeDeleteModal');
                this.cancelDelete = document.getElementById('cancelDelete');
                this.logoutModal = document.getElementById('logoutModal');
                this.cancelLogout = document.getElementById('cancelLogout');
                
                this.translations = {
                    'id': {
                        'pageTitle': 'Pengaturan',
                        'languageText': 'Bahasa',
                        'darkModeText': 'Mode gelap',
                        'anonymousText': 'Donasi sebagai anonim',
                        'deleteAccountText': 'Hapus Akun',
                        'logoutText': 'Keluar',
                        'selectLanguageTitle': 'Pilih bahasa',
                        'darkModeEnabled': 'Mode gelap diaktifkan',
                        'darkModeDisabled': 'Mode gelap dinonaktifkan',
                        'anonymousEnabled': 'Donasi anonim diaktifkan',
                        'anonymousDisabled': 'Donasi anonim dinonaktifkan',
                        'deleteConfirm': 'Apakah Anda yakin ingin menghapus akun Anda? Tindakan ini tidak dapat dibatalkan.',
                        'logoutConfirm': 'Apakah Anda yakin ingin keluar?',
                        'currentLanguage': 'Indonesia',
                        'deleteAccountTitle': 'Hapus Akun',
                        'deleteConfirmTitle': 'Hapus Akun Anda?',
                        'deleteConfirmText': 'Profil Anda akan kehilangan semua data dengan menghapus akun Anda. Tindakan ini tidak dapat dibatalkan.',
                        'cancelButton': 'Batal',
                        'deleteButton': 'Hapus akun saya',
                        'logoutConfirmTitle': 'Apakah Anda yakin ingin keluar dari aplikasi?',
                        'logoutCancelButton': 'Batal',
                        'logoutConfirmButton': 'Keluar'
                    },
                    'en': {
                        'pageTitle': 'Settings',
                        'languageText': 'Language',
                        'darkModeText': 'Dark mode',
                        'anonymousText': 'Donate as anonymous',
                        'deleteAccountText': 'Delete Account',
                        'logoutText': 'Log out',
                        'selectLanguageTitle': 'Select language',
                        'darkModeEnabled': 'Dark mode enabled',
                        'darkModeDisabled': 'Dark mode disabled',
                        'anonymousEnabled': 'Anonymous donation enabled',
                        'anonymousDisabled': 'Anonymous donation disabled',
                        'deleteConfirm': 'Are you sure you want to delete your account? This action cannot be undone.',
                        'logoutConfirm': 'Are you sure you want to log out?',
                        'currentLanguage': 'English',
                        'deleteAccountTitle': 'Delete Account',
                        'deleteConfirmTitle': 'Delete Your Account?',
                        'deleteConfirmText': 'Your profile will be lose all your data by deleting your account. This action cannot be undone.',
                        'cancelButton': 'Cancel',
                        'deleteButton': 'Delete my account',
                        'logoutConfirmTitle': 'Are you sure you want to leave the app?',
                        'logoutCancelButton': 'Cancel',
                        'logoutConfirmButton': 'Log out'
                    }
                };
                
                this.currentLanguage = '{{ session('locale', 'id') }}';
                this.activePanel = null;
                this.init();
            }

            init() {
                // Load saved preferences
                this.loadPreferences();
                
                // Add event listeners
                this.darkModeToggle.addEventListener('change', () => {
                    this.toggleDarkMode();
                });
                
                this.anonymousToggle.addEventListener('change', () => {
                    this.toggleAnonymous();
                });
                
                this.languageMenuItem.addEventListener('click', () => {
                    this.openLanguagePanel();
                });
                
                this.closeLanguagePanel.addEventListener('click', () => {
                    this.closePanel();
                });
                
                this.overlay.addEventListener('click', () => {
                    this.closePanel();
                });
                
                this.radioContainers.forEach(container => {
                    container.addEventListener('click', () => {
                        const lang = container.getAttribute('data-lang');
                        this.setLanguage(lang);
                    });
                });
                
                this.deleteAccountItem.addEventListener('click', () => {
                    this.openDeleteAccountModal();
                });
                
                this.closeDeleteModal.addEventListener('click', () => {
                    this.closePanel();
                });
                
                this.cancelDelete.addEventListener('click', () => {
                    this.closePanel();
                });
                
                this.cancelLogout.addEventListener('click', () => {
                    this.closePanel();
                });

                this.logoutItem.addEventListener('click', () => {
                    this.openLogoutModal();
                });
            }

            loadPreferences() {
                // Load dark mode preference
                const isDarkMode = localStorage.getItem('darkMode') === 'true';
                this.darkModeToggle.checked = isDarkMode;
                this.applyDarkMode(isDarkMode);
                
                // Load anonymous preference
                const isAnonymous = localStorage.getItem('donateAnonymous') === 'true';
                this.anonymousToggle.checked = isAnonymous;
                
                // Load language preference
                this.applyLanguage(this.currentLanguage);
            }

            toggleDarkMode() {
                const isDarkMode = this.darkModeToggle.checked;
                this.applyDarkMode(isDarkMode);
                localStorage.setItem('darkMode', isDarkMode);
                
                // Show feedback
                const message = isDarkMode ? 
                    this.translations[this.currentLanguage].darkModeEnabled : 
                    this.translations[this.currentLanguage].darkModeDisabled;
                this.showToast(message);
            }

            applyDarkMode(isDarkMode) {
                if (isDarkMode) {
                    document.documentElement.classList.add('dark');
                    document.body.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    document.body.classList.remove('dark');
                }
            }

            toggleAnonymous() {
                const isAnonymous = this.anonymousToggle.checked;
                localStorage.setItem('donateAnonymous', isAnonymous);
                
                // Show feedback
                const message = isAnonymous ? 
                    this.translations[this.currentLanguage].anonymousEnabled : 
                    this.translations[this.currentLanguage].anonymousDisabled;
                this.showToast(message);
            }
            
            openLanguagePanel() {
                this.closePanel(); // Close any open panel first
                this.activePanel = this.languagePanel;
                this.languagePanel.classList.add('active');
                this.overlay.classList.add('active');
                this.settingsContainer.classList.add('shifted');
            }
            
            openDeleteAccountModal() {
                this.closePanel(); // Close any open panel first
                this.activePanel = this.deleteAccountModal;
                this.deleteAccountModal.classList.add('active');
                this.overlay.classList.add('active');
            }

            openLogoutModal() {
                this.closePanel(); // Close any open panel first
                this.activePanel = this.logoutModal;
                this.logoutModal.classList.add('active');
                this.overlay.classList.add('active');
            }
            
            closePanel() {
                if (this.activePanel) {
                    this.activePanel.classList.remove('active');
                }
                this.overlay.classList.remove('active');
                this.settingsContainer.classList.remove('shifted');
                this.activePanel = null;
            }
            
            setLanguage(lang) {
                // Update selected radio button
                this.radioContainers.forEach(container => {
                    const radioButton = container.querySelector('.radio-button');
                    if (container.getAttribute('data-lang') === lang) {
                        radioButton.classList.add('selected');
                    } else {
                        radioButton.classList.remove('selected');
                    }
                });
                
                // Save language preference
                this.currentLanguage = lang;
                localStorage.setItem('language', lang);
                
                // Apply language changes
                this.applyLanguage(lang);
                
                // Send AJAX request to update session
                fetch('/account/updateLanguage', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ language: lang })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload page to apply language changes server-side
                        window.location.reload();
                    }
                });
            }
            
            applyLanguage(lang) {
                // Update UI text based on selected language
                document.getElementById('pageTitle').textContent = this.translations[lang].pageTitle;
                document.getElementById('languageText').textContent = this.translations[lang].languageText;
                document.getElementById('darkModeText').textContent = this.translations[lang].darkModeText;
                document.getElementById('anonymousText').textContent = this.translations[lang].anonymousText;
                document.getElementById('deleteAccountText').textContent = this.translations[lang].deleteAccountText;
                document.getElementById('logoutText').textContent = this.translations[lang].logoutText;
                document.getElementById('selectLanguageTitle').textContent = this.translations[lang].selectLanguageTitle;
                document.getElementById('currentLanguage').textContent = this.translations[lang].currentLanguage;
                document.getElementById('deleteAccountTitle').textContent = this.translations[lang].deleteAccountTitle;
                document.getElementById('deleteConfirmTitle').textContent = this.translations[lang].deleteConfirmTitle;
                document.getElementById('deleteConfirmText').textContent = this.translations[lang].deleteConfirmText;
                document.getElementById('cancelDelete').textContent = this.translations[lang].cancelButton;
                document.getElementById('logoutConfirmTitle').textContent = this.translations[lang].logoutConfirmTitle;
                document.getElementById('cancelLogout').textContent = this.translations[lang].logoutCancelButton;
                
                // Update delete button text
                const deleteButton = document.querySelector('.btn-delete');
                if (deleteButton) {
                    deleteButton.textContent = this.translations[lang].deleteButton;
                }

                // Update logout button text
                const logoutButton = document.querySelector('.btn-logout-confirm');
                if (logoutButton) {
                    logoutButton.textContent = this.translations[lang].logoutConfirmButton;
                }
            }
            

            showToast(message) {
                // Create toast notification
                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 bg-gray-800 text-white px-4 py-2 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
                toast.textContent = message;
                
                document.body.appendChild(toast);
                
                // Show toast
                setTimeout(() => {
                    toast.classList.remove('translate-x-full');
                }, 100);
                
                // Hide toast
                setTimeout(() => {
                    toast.classList.add('translate-x-full');
                    setTimeout(() => {
                        document.body.removeChild(toast);
                    }, 300);
                }, 3000);
            }
        }

        // Initialize App Manager
        document.addEventListener('DOMContentLoaded', function() {
            new AppManager();
        });
    </script>
</body>
</html>
