<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirestoreService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $firestoreService;

    public function __construct(FirestoreService $firestoreService)
    {
        $this->firestoreService = $firestoreService;
    }

    public function index()
    {
        try {
            // Get current user data from session or Firestore
            $user = Auth::user();
            $userData = session('user_data');
            
            // Only fetch from Firestore if we don't have user data in session
            if (!$userData && $user) {
                $firestoreUser = $this->firestoreService->getUserByEmail($user->email);
                if ($firestoreUser && isset($firestoreUser['data'])) {
                    $userData = $firestoreUser['data'];
                    session(['user_data' => $userData]);
                }
            }
            
            $donationBalance = $userData['saldo'] ?? 0;

            // Get latest campaigns from donations collection
            $donationsRef = $this->firestoreService->getCollection('donations');
            $latestCampaigns = [];
            $finishedCampaigns = [];

            // Get latest active campaigns (where finishDate is in the future)
            $now = new \Google\Cloud\Core\Timestamp(new \DateTime());
            
            try {
                $latestCampaignsQuery = $donationsRef->where('finishDate', '>', $now)
                    ->orderBy('createdAt', 'desc');
                
                foreach ($latestCampaignsQuery->documents() as $doc) {
                    $data = $doc->data();
                    $latestCampaigns[] = [
                        'id' => $doc->id(),
                        'title' => $data['name'] ?? 'Untitled Campaign',
                        'category' => $data['category'] ?? 'Uncategorized',
                        'image' => !empty($data['imageUrls']) ? $data['imageUrls'][0] : 'images/default-campaign.jpg',
                        'collected' => $data['progress'] ?? 0,
                        'progress' => isset($data['progress'], $data['target']) 
                            ? min(($data['progress'] / $data['target']) * 100, 100) 
                            : 0
                    ];
                }

                // Get finished campaigns (where finishDate is in the past)
                $finishedCampaignsQuery = $donationsRef->where('finishDate', '<=', $now)
                    ->orderBy('createdAt', 'desc');
                
                foreach ($finishedCampaignsQuery->documents() as $doc) {
                    $data = $doc->data();
                    $finishedCampaigns[] = [
                        'id' => $doc->id(),
                        'title' => $data['name'] ?? 'Untitled Campaign',
                        'image' => !empty($data['imageUrls']) ? $data['imageUrls'][0] : 'images/default-campaign.jpg'
                    ];
                }
            } catch (\Exception $e) {
                \Log::error('Error fetching campaigns: ' . $e->getMessage());
                
                // If index is not ready, fallback to simple query
                if (strpos($e->getMessage(), 'requires an index') !== false) {
                    \Log::info('Falling back to simple query without ordering');
                    
                    // Fallback query without ordering
                    $latestCampaignsQuery = $donationsRef->where('finishDate', '>', $now);
                    
                    foreach ($latestCampaignsQuery->documents() as $doc) {
                        $data = $doc->data();
                        $latestCampaigns[] = [
                            'id' => $doc->id(),
                            'title' => $data['name'] ?? 'Untitled Campaign',
                            'category' => $data['category'] ?? 'Uncategorized',
                            'image' => !empty($data['imageUrls']) ? $data['imageUrls'][0] : 'images/default-campaign.jpg',
                            'collected' => $data['progress'] ?? 0,
                            'progress' => isset($data['progress'], $data['target']) 
                                ? min(($data['progress'] / $data['target']) * 100, 100) 
                                : 0
                        ];
                    }

                    // Fallback for finished campaigns
                    $finishedCampaignsQuery = $donationsRef->where('finishDate', '<=', $now);
                    
                    foreach ($finishedCampaignsQuery->documents() as $doc) {
                        $data = $doc->data();
                        $finishedCampaigns[] = [
                            'id' => $doc->id(),
                            'title' => $data['name'] ?? 'Untitled Campaign',
                            'image' => !empty($data['imageUrls']) ? $data['imageUrls'][0] : 'images/default-campaign.jpg'
                        ];
                    }
                } else {
                    throw $e;
                }
            }

            return view('dashboard', compact('donationBalance', 'latestCampaigns', 'finishedCampaigns'));
            
        } catch (\Exception $e) {
            \Log::error('Dashboard error: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Failed to load dashboard');
        }
    }
}
