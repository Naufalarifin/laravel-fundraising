<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Services\FirestoreService;

class AuthController extends Controller
{
    protected $firestore;

    public function __construct(FirestoreService $firestore)
    {
        $this->firestore = $firestore;
    }

    public function showSignIn()
    {
        return view('auth.sign-in');
    }

    public function showSignUp()
    {
        return view('auth.sign-up');
    }

    public function signIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $result = $this->firestore->getUserByEmail($request->email);

        if (!$result) {
            return redirect()->back()->withErrors(['email' => 'User not found'])->withInput();
        }

        $userData = $result['data'];

        if ($request->password !== $userData['password']) {
            return redirect()->back()->withErrors(['password' => 'Invalid password'])->withInput();
        }

        // Create a temporary user object for authentication
        $user = new class($userData, $result['id']) extends Authenticatable {
            public $id;
            public $name;
            public $email;
            public $data;

            public function __construct($data, $id)
            {
                $this->id = $id;
                $this->name = $data['name'] ?? '';
                $this->email = $data['email'];
                $this->data = $data;
            }

            public function getAuthIdentifierName()
            {
                return 'id';
            }

            public function getAuthIdentifier()
            {
                return $this->id;
            }

            public function getAuthPassword()
            {
                return $this->password;
            }
        };

        Auth::login($user);
        
        // Store user data in session
        session(['user_data' => $userData]);

        return redirect()->route('dashboard')->with('success', 'Welcome back!');
    }

    public function signUp(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'agree_to_terms' => 'required|accepted',
        ], [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters',
            'agree_to_terms.accepted' => 'You must agree to the Terms of Service and Privacy Policy',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check if email already exists
        $existingUser = $this->firestore->getUserByEmail($request->email);
        if ($existingUser) {
            return redirect()->back()->withErrors(['email' => 'This email is already registered'])->withInput();
        }

        // Prepare user data
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'saldo' => 0,
            'createdAt' => new \Google\Cloud\Core\Timestamp(new \DateTime())
        ];

        try {
            // Add user to Firestore
            $result = $this->firestore->addDocument('users', $userData);

            // Redirect to login page with success message
            return redirect()->route('sign-in')->with('success', 'Registration successful! Please sign in to continue.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to create account. Please try again.'])->withInput();
        }
    }

    public function logout()
    {
        // Logic untuk logout
        Auth::logout();
        return redirect()->route('sign-in')->with('success', 'You have been logged out successfully');
    }
}
