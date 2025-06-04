<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Services\FirestoreService;

class AccountController extends Controller
{
    public function index()
    {
        // Get user data from session
        $userData = session('user_data');
        if (!$userData) {
            return redirect()->route('dashboard')->with('error', 'User data not found');
        }

        // Format user data for view
        $user = [
            'name' => $userData['name'] ?? 'User',
            'email' => $userData['email'] ?? '',
            'phone' => $userData['phone'] ?? '',
            'gender' => $userData['gender'] ?? '',
            'avatar' => $userData['avatar'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($userData['name'] ?? 'User')
        ];

        return view('account', compact('user'));
    }

    public function editProfile()
    {
        // Logic untuk mengambil data user untuk edit
        $user = [
            'name' => 'Mohammad Salah',
            'email' => 'mosalah@gmail.com',
            'phone' => '+628445578293',
            'country_code' => '+1',
            'phone_number' => '628445578293',
            'gender' => 'Male',
            'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop&crop=face'
        ];

        // Data untuk dropdown
        $genders = [
            'Male' => session('locale') == 'en' ? 'Male' : 'Laki-laki',
            'Female' => session('locale') == 'en' ? 'Female' : 'Perempuan',
            'Other' => session('locale') == 'en' ? 'Other' : 'Lainnya'
        ];

        $countryCodes = [
            '+1' => '+1 (US)',
            '+62' => '+62 (ID)',
            '+44' => '+44 (UK)',
            '+91' => '+91 (IN)',
            '+86' => '+86 (CN)',
            '+81' => '+81 (JP)',
            '+49' => '+49 (DE)',
            '+33' => '+33 (FR)',
        ];

        return view('account.editProfile', compact('user', 'genders', 'countryCodes'));
    }

    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'country_code' => 'required|string',
            'phone_number' => 'required|string|max:20',
            'gender' => 'required|in:Male,Female,Other',
        ], [
            'name.required' => session('locale') == 'en' ? 'Name is required' : 'Nama harus diisi',
            'email.required' => session('locale') == 'en' ? 'Email is required' : 'Email harus diisi',
            'email.email' => session('locale') == 'en' ? 'Invalid email format' : 'Format email tidak valid',
            'country_code.required' => session('locale') == 'en' ? 'Country code is required' : 'Kode negara harus dipilih',
            'phone_number.required' => session('locale') == 'en' ? 'Phone number is required' : 'Nomor telepon harus diisi',
            'gender.required' => session('locale') == 'en' ? 'Gender is required' : 'Jenis kelamin harus dipilih',
        ]);

        // Logic untuk update data user
        // Biasanya menggunakan Auth::user()->update($data)
        
        // Simulasi update berhasil
        $fullPhone = $request->country_code . $request->phone_number;
        
        // Flash message untuk success
        $successMessage = session('locale') == 'en' ? 'Profile updated successfully!' : 'Profil berhasil diperbarui!';
        return redirect()->route('account')->with('success', $successMessage);
    }

    public function changePassword()
    {
        $userData = session('user_data');
        if (!$userData) {
            return redirect()->route('dashboard')->with('error', 'User data not found');
        }

        // Format user data for view
        $user = [
            'name' => $userData['name'] ?? 'User',
            'email' => $userData['email'] ?? '',
            'phone' => $userData['phone'] ?? '',
            'gender' => $userData['gender'] ?? '',
            'avatar' => $userData['avatar'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($userData['name'] ?? 'User')
        ];

        return view('account.changePassword', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        // Validasi input password
        $request->validate([
            'current_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string|min:8',
        ], [
            'current_password.required' => session('locale') == 'en' ? 'Current password is required' : 'Password saat ini harus diisi',
            'current_password.min' => session('locale') == 'en' ? 'Current password must be at least 8 characters' : 'Password saat ini minimal 8 karakter',
            'new_password.required' => session('locale') == 'en' ? 'New password is required' : 'Password baru harus diisi',
            'new_password.min' => session('locale') == 'en' ? 'New password must be at least 8 characters' : 'Password baru minimal 8 karakter',
            'new_password.confirmed' => session('locale') == 'en' ? 'New password confirmation does not match' : 'Konfirmasi password baru tidak cocok',
            'new_password_confirmation.required' => session('locale') == 'en' ? 'Password confirmation is required' : 'Konfirmasi password baru harus diisi',
            'new_password_confirmation.min' => session('locale') == 'en' ? 'Password confirmation must be at least 8 characters' : 'Konfirmasi password baru minimal 8 karakter',
        ]);

        // Get user data from session
        $userData = session('user_data');
        if (!$userData) {
            return redirect()->route('dashboard')->with('error', 'User data not found');
        }

        try {
            // Get user document from Firestore
            $firestoreService = app(FirestoreService::class);
            $userDoc = $firestoreService->getDocument('users', $userData['uid']);
            
            if (!$userDoc) {
                return back()->withErrors(['current_password' => session('locale') == 'en' ? 'User not found' : 'Pengguna tidak ditemukan']);
            }

            // Verify current password (plain text comparison)
            if ($request->current_password !== $userDoc['password']) {
                return back()->withErrors(['current_password' => session('locale') == 'en' ? 'Current password is incorrect' : 'Password saat ini tidak benar']);
            }

            // Update password in Firestore (plain text)
            $firestoreService->updateDocument('users', $userData['uid'], [
                'password' => $request->new_password
            ]);

            // Update session data
            $userData['password'] = $request->new_password;
            session(['user_data' => $userData]);

            $successMessage = session('locale') == 'en' ? 'Password updated successfully!' : 'Password berhasil diperbarui!';
            return redirect()->route('account')->with('success', $successMessage);

        } catch (\Exception $e) {
            \Log::error('Password update error: ' . $e->getMessage());
            return back()->withErrors(['error' => session('locale') == 'en' ? 'Failed to update password' : 'Gagal memperbarui password']);
        }
    }

    public function setting()
    {
        // Logic untuk menampilkan halaman settings
        $user = [
            'name' => 'Mohammad Salah',
            'email' => 'mosalah@gmail.com',
            'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop&crop=face'
        ];

        // Settings data (biasanya dari database atau session)
        $settings = [
            'language' => session('locale', 'id'),
            'dark_mode' => false,
            'donate_anonymous' => false,
        ];

        return view('account.setting', compact('user', 'settings'));
    }

    public function updateSettings(Request $request)
    {
        // Validasi input settings
        $request->validate([
            'language' => 'required|in:id,en',
            'dark_mode' => 'boolean',
            'donate_anonymous' => 'boolean',
        ]);

        // Logic untuk update settings
        // Biasanya disimpan ke database atau session
        // Auth::user()->settings()->updateOrCreate([], $request->only(['language', 'dark_mode', 'donate_anonymous']));
        
        // Update session language
        session(['locale' => $request->language]);
        
        // Simulasi update berhasil
        $successMessage = session('locale') == 'en' ? 'Settings updated successfully!' : 'Pengaturan berhasil diperbarui!';
        return redirect()->route('account.setting')->with('success', $successMessage);
    }
    
    public function updateLanguage(Request $request)
    {
        // Validasi input
        $request->validate([
            'language' => 'required|in:id,en',
        ]);
        
        // Update session language
        session(['locale' => $request->language]);
        
        // Return success response
        return response()->json(['success' => true]);
    }
    
    public function delete(Request $request)
    {
        // Logic untuk menghapus akun user
        // Biasanya menggunakan:
        // Auth::user()->delete();
        
        // Simulasi delete berhasil
        // Redirect ke halaman login atau home dengan pesan sukses
        $successMessage = session('locale') == 'en' ? 'Account deleted successfully!' : 'Akun berhasil dihapus!';
        return redirect('/')->with('success', $successMessage);
    }

    public function logout(Request $request)
    {
        // Logic untuk logout user
        // Biasanya menggunakan:
        // Auth::logout();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();
        
        // Simulasi logout berhasil
        // Redirect ke halaman login atau home
        $successMessage = session('locale') == 'en' ? 'You have been logged out successfully!' : 'Anda telah berhasil keluar!';
        return redirect('/')->with('success', $successMessage);
    }
}
