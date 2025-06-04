<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Services\FirestoreService;

class TopupController extends Controller
{
    public function index()
    {
        // Data untuk metode pembayaran
        $paymentMethods = [
            [
                'id' => 'bca',
                'name' => 'Bank Central Asia',
                'code' => 'BCA',
                'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/1598px-Bank_Central_Asia.svg.png?20200318082802'
            ],
            [
                'id' => 'mandiri',
                'name' => 'Mandiri',
                'code' => 'MANDIRI',
                'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/200px-Bank_Mandiri_logo_2016.svg.png'
            ],
            [
                'id' => 'bri',
                'name' => 'BRI',
                'code' => 'BRI',
                'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/68/BANK_BRI_logo.svg/200px-BANK_BRI_logo.svg.png'
            ],
            [
                'id' => 'qris',
                'name' => 'QRIS',
                'code' => 'QRIS',
                'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e1/QRIS_logo.svg/768px-QRIS_logo.svg.png?20201215043119'
            ]
        ];

        // Data untuk nominal top-up cepat
        $quickAmounts = [
            10000,
            50000,
            100000,
            150000,
            200000,
            250000
        ];

        return view('topup', compact('paymentMethods', 'quickAmounts'));
    }

    public function process(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:10000|max:10000000',
            'payment_method' => 'required|string',
        ], [
            'amount.required' => session('locale') == 'en' ? 'Amount is required' : 'Nominal harus diisi',
            'amount.numeric' => session('locale') == 'en' ? 'Amount must be a number' : 'Nominal harus berupa angka',
            'amount.min' => session('locale') == 'en' ? 'Minimum amount is Rp 10.000' : 'Minimal top up Rp 10.000',
            'amount.max' => session('locale') == 'en' ? 'Maximum amount is Rp 10.000.000' : 'Maksimal top up Rp 10.000.000',
            'payment_method.required' => session('locale') == 'en' ? 'Payment method is required' : 'Metode pembayaran harus dipilih',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Store amount in session
        session(['topup_amount' => $request->amount]);

        // Redirect ke halaman instruksi pembayaran
        return redirect()->route('topup.instruction', [
            'amount' => $request->amount,
            'method' => $request->payment_method
        ]);
    }

    public function instruction(Request $request)
    {
        $amount = $request->get('amount');
        $selectedMethod = $request->get('method');

        // Validasi parameter
        if (!$amount || !$selectedMethod) {
            return redirect()->route('topup')->with('error', 'Invalid payment data');
        }

        // Data untuk metode pembayaran
        $paymentMethods = [
            [
                'id' => 'bca',
                'name' => 'Bank Central Asia',
                'code' => 'BCA',
                'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/1598px-Bank_Central_Asia.svg.png?20200318082802'
            ],
            [
                'id' => 'mandiri',
                'name' => 'Mandiri',
                'code' => 'MANDIRI',
                'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/200px-Bank_Mandiri_logo_2016.svg.png'
            ],
            [
                'id' => 'bri',
                'name' => 'BRI',
                'code' => 'BRI',
                'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/68/BANK_BRI_logo.svg/200px-BANK_BRI_logo.svg.png'
            ],
            [
                'id' => 'qris',
                'name' => 'QRIS',
                'code' => 'QRIS',
                'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e1/QRIS_logo.svg/768px-QRIS_logo.svg.png?20201215043119'
            ]
        ];

        return view('topup.instruction', compact('amount', 'selectedMethod', 'paymentMethods'));
    }

    public function success()
    {
        // Tampilkan halaman sukses
        return view('topup.success');
    }

    public function confirmPayment(Request $request)
    {
        try {
            // Get amount from POST data
            $amount = $request->input('amount');
            \Log::error('Amount from POST: ' . $amount);
            
            if (!$amount) {
                \Log::error('Amount not found in POST data');
            }

            // Get user data from session
            $userData = session('user_data');
            \Log::error('User data from session: ' . json_encode($userData));
            
            if (!$userData) {
                \Log::error('User data not found in session');
            }

            // Calculate new balance
            $currentBalance = $userData['saldo'] ?? 0;
            $newBalance = $currentBalance + (int)$amount;
            \Log::error('New balance calculation: ' . $newBalance);

            // Get Firestore service
            $firestoreService = app(FirestoreService::class);
            
            // Update user's balance in Firestore
            $firestoreService->updateDocument('users', $userData['uid'], [
                'saldo' => $newBalance
            ]);
            \Log::error('Balance updated in Firestore');

            // Create transaction record
            $firestoreService->addDocument('transactions', [
                'amount' => (int)$amount,
                'category' => 'income',
                'date' => new \Google\Cloud\Core\Timestamp(new \DateTime()),
                'name' => 'Top Up',
                'userId' => $userData['uid']
            ]);
            \Log::error('Transaction record created');

            // Update session data
            $userData['saldo'] = $newBalance;
            session(['user_data' => $userData]);
            \Log::error('Session updated with new balance');

            return redirect()->route('topup.success')->with('success', 'Top up berhasil! Saldo Anda telah diperbarui.');
        } catch (\Exception $e) {
            \Log::error('Top up error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('topup')->with('error', 'Gagal memproses pembayaran. Silakan coba lagi.');
        }
    }
}
