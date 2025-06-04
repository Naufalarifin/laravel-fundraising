<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirestoreService;

class MyDonationsController extends Controller
{
    protected $firestoreService;

    public function __construct(FirestoreService $firestoreService)
    {
        $this->firestoreService = $firestoreService;
    }

    public function index()
    {
        // Get user data from session
        $userData = session('user_data');
        if (!$userData) {
            return redirect()->route('dashboard')->with('error', 'User data not found');
        }

        try {
            // Get all donations from Firestore and convert to array
            $donationsCollection = $this->firestoreService->getCollection('donations');
            $donations = [];
            
            foreach ($donationsCollection->documents() as $doc) {
                $data = $doc->data();
                $donations[] = [
                    'id' => $doc->id(),
                    'name' => $data['name'] ?? 'Untitled Campaign',
                    'category' => $data['category'] ?? 'Uncategorized',
                    'progress' => $data['progress'] ?? 0,
                    'target' => $data['target'] ?? 0,
                    'createdAt' => $data['createdAt'] ?? new \Google\Cloud\Core\Timestamp(new \DateTime()),
                    'finishDate' => $data['finishDate'] ?? new \Google\Cloud\Core\Timestamp(new \DateTime()),
                    'imageUrls' => $data['imageUrls'] ?? [],
                    'uid' => $data['uid'] ?? ''
                ];
            }

            \Log::info('All donations:', ['donations' => $donations]);
            \Log::info('User UID:', ['uid' => $userData['uid']]);
            
            // Filter donations by user ID
            $myDonations = array_filter($donations, function($donation) use ($userData) {
                return $donation['uid'] === $userData['uid'];
            });

            \Log::info('Filtered donations:', ['myDonations' => $myDonations]);

            return view('my-donations', ['donations' => $myDonations]);
        } catch (\Exception $e) {
            \Log::error('Error fetching donations: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Failed to load donations');
        }
    }
} 