<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DonateController extends Controller
{
    public function index(Request $request, $campaignId)
    {
        if (!$campaignId) {
            return redirect()->route('dashboard')->with('error', 'Campaign not found');
        }

        try {
            // Get Firestore instance
            $firestore = app('firebase.firestore');
            $db = $firestore->database();

            // Get campaign data from Firestore
            $donationRef = $db->collection('donations')->document($campaignId);
            $donation = $donationRef->snapshot();

            if (!$donation->exists()) {
                return redirect()->route('dashboard')->with('error', 'Campaign not found');
            }

            $donationData = $donation->data();
            
            // Format campaign data
            $campaign = [
                'id' => $campaignId,
                'title' => $donationData['name'] ?? 'Untitled Campaign',
                'image' => $donationData['imageUrls'][0] ?? 'https://via.placeholder.com/800x600',
                'target' => $donationData['target'] ?? 0,
                'collected' => $donationData['progress'] ?? 0,
            ];

            // Get user data from session
            $userData = session('user_data');
            if (!$userData) {
                return redirect()->route('dashboard')->with('error', 'User data not found');
            }

            // Get user balance from session
            $userBalance = $userData['saldo'] ?? 0;

            // Quick amount options
            $quickAmounts = [10000, 50000, 100000, 150000, 200000, 250000];

            // Payment methods
            $paymentMethods = [
                [
                    'id' => 'balance',
                    'name' => 'Bantu.In Balance',
                    'code' => 'BALANCE',
                    'logo' => '<h1 class="text-2xl font-bold text-custom-teal">Bantu.In</h1>'
                ],
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

            return view('donate', compact('campaign', 'quickAmounts', 'paymentMethods', 'userBalance'));
        } catch (\Exception $e) {
            \Log::error('Error fetching campaign details:', [
                'id' => $campaignId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('dashboard')->with('error', 'Failed to load campaign details');
        }
    }

    public function process(Request $request)
    {
        \Log::info('Processing donation request:', [
            'request_data' => $request->all(),
            'session_data' => session()->all()
        ]);

        // Validasi input
        $validator = Validator::make($request->all(), [
            'campaign_id' => 'required|string',
            'amount' => 'required|numeric|min:10000',
            'payment_method' => 'required|string',
        ], [
            'campaign_id.required' => 'Campaign ID is required',
            'amount.required' => session('locale') == 'en' ? 'Amount is required' : 'Nominal harus diisi',
            'amount.numeric' => session('locale') == 'en' ? 'Amount must be a number' : 'Nominal harus berupa angka',
            'amount.min' => session('locale') == 'en' ? 'Minimum donation is Rp 10.000' : 'Minimal donasi Rp 10.000',
            'payment_method.required' => session('locale') == 'en' ? 'Payment method is required' : 'Metode pembayaran harus dipilih',
        ]);

        if ($validator->fails()) {
            \Log::error('Donation validation failed:', [
                'errors' => $validator->errors()->toArray(),
                'request_data' => $request->all()
            ]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Store donation data in session for instruction page
            session([
                'donation_data' => [
                    'campaign_id' => $request->campaign_id,
                    'amount' => $request->amount,
                    'payment_method' => $request->payment_method,
                    'type' => 'donation'
                ]
            ]);

            \Log::info('Donation data stored in session:', [
                'donation_data' => session('donation_data')
            ]);

            return redirect()->route('donate.instruction');
        } catch (\Exception $e) {
            \Log::error('Error processing donation:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return redirect()->route('dashboard')->with('error', 'Failed to process donation');
        }
    }

    public function confirmPayment(Request $request)
    {
        // Get donation data from session
        $donationData = session('donation_data');
        
        if (!$donationData) {
            return redirect()->route('dashboard')->with('error', 'Invalid donation data');
        }

        try {
            // Get Firestore instance
            $firestore = app('firebase.firestore');
            $db = $firestore->database();

            // Get user data from session
            $userData = session('user_data');
            if (!$userData) {
                return redirect()->route('dashboard')->with('error', 'User data not found');
            }

            // Get campaign data
            $donationRef = $db->collection('donations')->document($donationData['campaign_id']);
            $donation = $donationRef->snapshot();

            if (!$donation->exists()) {
                return redirect()->route('dashboard')->with('error', 'Campaign not found');
            }

            $campaignData = $donation->data();
            $currentProgress = $campaignData['progress'] ?? 0;
            $newProgress = $currentProgress + $donationData['amount'];

            // If payment method is balance, check and update user balance
            if ($donationData['payment_method'] === 'balance') {
                $userRef = $db->collection('users')->document($userData['uid']);
                $userDoc = $userRef->snapshot();
                
                if (!$userDoc->exists()) {
                    return redirect()->route('dashboard')->with('error', 'User not found');
                }

                $userData = $userDoc->data();
                $currentBalance = $userData['saldo'] ?? 0;

                if ($currentBalance < $donationData['amount']) {
                    return redirect()->back()->with('error', session('locale') == 'en' ? 'Insufficient balance' : 'Saldo tidak mencukupi');
                }

                // Update user balance
                $newBalance = $currentBalance - $donationData['amount'];
                $userRef->update([
                    ['path' => 'saldo', 'value' => $newBalance]
                ]);

                // Update session user data
                $userData['saldo'] = $newBalance;
                session(['user_data' => $userData]);
            }

            // Update campaign progress
            $donationRef->update([
                ['path' => 'progress', 'value' => $newProgress]
            ]);

            // Create transaction record
            $transactionRef = $db->collection('transactions')->add([
                'userId' => $userData['uid'],
                'campaignId' => $donationData['campaign_id'],
                'amount' => $donationData['amount'],
                'type' => 'donation',
                'status' => 'success',
                'paymentMethod' => $donationData['payment_method'],
                'createdAt' => \Google\Cloud\Core\Timestamp::fromDateTime(new \DateTime())
            ]);

            // Clear donation data from session
            session()->forget('donation_data');

            return redirect()->route('donate.success');
        } catch (\Exception $e) {
            \Log::error('Error processing donation:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('dashboard')->with('error', 'Failed to process donation');
        }
    }

    public function instruction(Request $request)
    {
        \Log::info('Entering instruction method', [
            'request_data' => $request->all(),
            'session_data' => session()->all()
        ]);

        // Get donation data from session
        $donationData = session('donation_data');
        
        if (!$donationData) {
            \Log::error('No donation data found in session', [
                'session_data' => session()->all()
            ]);
            return redirect()->route('dashboard')->with('error', 'Invalid donation data');
        }

        \Log::info('Found donation data in session', [
            'donation_data' => $donationData
        ]);

        try {
            // Get Firestore instance
            $firestore = app('firebase.firestore');
            $db = $firestore->database();

            // Get campaign data
            $donationRef = $db->collection('donations')->document($donationData['campaign_id']);
            $donation = $donationRef->snapshot();

            \Log::info('Fetched campaign data from Firestore', [
                'campaign_id' => $donationData['campaign_id'],
                'exists' => $donation->exists(),
                'data' => $donation->exists() ? $donation->data() : null
            ]);

            if (!$donation->exists()) {
                \Log::error('Campaign not found in Firestore', [
                    'campaign_id' => $donationData['campaign_id']
                ]);
                return redirect()->route('dashboard')->with('error', 'Campaign not found');
            }

            $donationData['campaign'] = [
                'id' => $donationData['campaign_id'],
                'title' => $donation->data()['name'] ?? 'Untitled Campaign',
                'image' => $donation->data()['imageUrls'][0] ?? 'https://via.placeholder.com/800x600',
            ];

            // Payment methods data
            $paymentMethods = [
                'bca' => [
                    'name' => 'Bank Central Asia',
                    'account' => '1234567890',
                    'holder' => 'PT Bantu Indonesia'
                ],
                'mandiri' => [
                    'name' => 'Bank Mandiri',
                    'account' => '9876543210',
                    'holder' => 'PT Bantu Indonesia'
                ],
                'bri' => [
                    'name' => 'Bank Rakyat Indonesia',
                    'account' => '5555666677',
                    'holder' => 'PT Bantu Indonesia'
                ]
            ];

            // Get selected payment method details
            $selectedMethod = $paymentMethods[$donationData['payment_method']] ?? null;

            \Log::info('Preparing to render instruction view', [
                'payment_method' => $donationData['payment_method'],
                'selected_method' => $selectedMethod,
                'campaign_data' => $donationData['campaign']
            ]);

            return view('donate.instruction', compact('donationData', 'selectedMethod'));
        } catch (\Exception $e) {
            \Log::error('Error loading instruction page:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'donation_data' => $donationData ?? null,
                'session_data' => session()->all()
            ]);
            return redirect()->route('dashboard')->with('error', 'Failed to load instruction page');
        }
    }

    public function success()
    {
        return view('donate.success');
    }
}
