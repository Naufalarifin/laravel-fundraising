<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Google\Cloud\Core\Timestamp;

class CampaignController extends Controller
{
    private $supabaseUrl;
    private $supabaseKey;

    public function __construct()
    {
        $this->supabaseUrl = env('SUPABASE_URL');
        $this->supabaseKey = env('SUPABASE_KEY');
    }

    public function detail($id)
    {
        try {
            // Get donation data from Firestore with timeout
            $firestore = app('firebase.firestore');
            $donationRef = $firestore->database()->collection('donations')->document($id);
            
            // Add timeout to prevent hanging
            $donation = $donationRef->snapshot(['timeout' => 5000]);

            if (!$donation->exists()) {
                return redirect()->route('dashboard')->with('error', 'Campaign not found');
            }

            $data = $donation->data();
            
            // Calculate days left
            $finishDate = $data['finishDate']->get();
            $now = new \DateTime();
            $daysLeft = $finishDate->diff($now)->days;

            // Get organizer data with timeout
            $organizerRef = $firestore->database()->collection('users')->document($data['uid']);
            $organizer = $organizerRef->snapshot(['timeout' => 5000]);
            $organizerData = $organizer->exists() ? $organizer->data() : null;

            // Format campaign data
            $campaign = [
                'id' => $id,
                'title' => $data['name'],
                'description' => $data['description'],
                'category' => $data['category'],
                'image' => !empty($data['imageUrls']) ? $data['imageUrls'][0] : 'images/default-campaign.jpg',
                'target' => $data['target'],
                'collected' => $data['progress'] ?? 0,
                'progress' => isset($data['progress'], $data['target']) 
                    ? round(($data['progress'] / $data['target']) * 100) 
                    : 0,
                'location' => $data['location'] ?? 'Indonesia',
                'days_left' => $daysLeft,
                'views' => $data['views'] ?? 0,
                'organizer' => [
                    'name' => $organizerData['name'] ?? 'Anonymous',
                    'avatar' => $organizerData['photoURL'] ?? 'images/default-avatar.png',
                    'verified' => $organizerData['verified'] ?? false
                ],
                'story' => $data['description'],
                'updates' => $data['updates'] ?? []
            ];

            // Increment view count with retry mechanism
            $maxRetries = 3;
            $retryCount = 0;
            $success = false;

            while (!$success && $retryCount < $maxRetries) {
                try {
                    $donationRef->update([
                        ['path' => 'views', 'value' => $campaign['views'] + 1]
                    ]);
                    $success = true;
                } catch (\Exception $e) {
                    $retryCount++;
                    if ($retryCount === $maxRetries) {
                        \Log::warning('Failed to increment view count after ' . $maxRetries . ' attempts');
                    }
                    usleep(100000); // Wait 100ms before retry
                }
            }

            return view('campaigns.detail', compact('campaign'));
        } catch (\Exception $e) {
            \Log::error('Error fetching campaign details:', [
                'id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('dashboard')->with('error', 'Failed to load campaign details');
        }
    }

    public function donate($id)
    {
        // Redirect ke halaman donasi dengan ID campaign
        return redirect()->route('donate', ['campaign_id' => $id]);
    }

    public function create()
    {
        $categories = [
            'Dhuafa' => [
                'name' => 'Dhuafa',
                'image' => 'images/duafa.png'
            ],
            'Kesehatan' => [
                'name' => 'Kesehatan',
                'image' => 'images/medis.png'
            ],
            'Lingkungan' => [
                'name' => 'Lingkungan',
                'image' => 'images/kebakaran.png'
            ],
            'Bencana Alam' => [
                'name' => 'Bencana Alam',
                'image' => 'images/bencana.png'
            ]
        ];

        return view('campaigns.create', compact('categories'));
    }

    public function generateDescription(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'prompt' => 'required|string|min:10'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $response = Http::post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=AIzaSyBvB3SxYh-ahWR4aCZoCWjuPDs9TPtbsJU",
                [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => "You are a helpful assistant that generates compelling campaign descriptions for fundraising purposes. The descriptions should be engaging, emotional, and persuasive. Please generate a description based on this prompt: {$request->prompt}"
                                ]
                            ]
                        ]
                    ]
                ]
            );

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'description' => $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Failed to generate description'
                ]);
            }

            return response()->json(['error' => 'Failed to generate description'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function uploadImageToSupabase($file)
    {
        try {
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Create a multipart request
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->supabaseKey,
            ])->attach(
                'file', // The name of the file field
                file_get_contents($file->getRealPath()), // The file contents
                $fileName, // The file name
                ['Content-Type' => $file->getMimeType()] // The headers as an array
            )->post(
                "{$this->supabaseUrl}/storage/v1/object/donation-images/{$fileName}"
            );

            if ($response->successful()) {
                return "{$this->supabaseUrl}/storage/v1/object/public/donation-images/{$fileName}";
            }

            \Log::error('Supabase upload failed:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            throw new \Exception('Failed to upload image to Supabase: ' . $response->body());
        } catch (\Exception $e) {
            \Log::error('Image upload error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function store(Request $request)
    {
        // Log the incoming request data
        \Log::info('Campaign creation request:', $request->all());

        $validator = Validator::make($request->all(), [
            'donation_name' => 'required|string|max:255',
            'target_value' => 'required|string',
            'category' => 'required|string|in:Dhuafa,Kesehatan,Lingkungan,Bencana Alam',
            'description' => 'required|string|min:50',
            'finish_date' => 'required|date|after:today',
            'campaign_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'donation_name.required' => 'Nama donasi harus diisi',
            'donation_name.max' => 'Nama donasi tidak boleh lebih dari 255 karakter',
            'target_value.required' => 'Target nilai harus diisi',
            'category.required' => 'Kategori harus dipilih',
            'category.in' => 'Kategori yang dipilih tidak valid',
            'description.required' => 'Deskripsi harus diisi',
            'description.min' => 'Deskripsi minimal 50 karakter',
            'finish_date.required' => 'Tanggal selesai harus diisi',
            'finish_date.date' => 'Format tanggal tidak valid',
            'finish_date.after' => 'Tanggal selesai harus setelah hari ini',
            'campaign_image.required' => 'Gambar harus diupload',
            'campaign_image.image' => 'File harus berupa gambar',
            'campaign_image.mimes' => 'Gambar harus berformat jpeg, png, jpg, atau gif',
            'campaign_image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB'
        ]);

        if ($validator->fails()) {
            \Log::error('Validation failed:', $validator->errors()->toArray());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Upload image to Supabase with retry mechanism
            $maxRetries = 3;
            $retryCount = 0;
            $imageUrl = null;

            while ($imageUrl === null && $retryCount < $maxRetries) {
                try {
                    $imageUrl = $this->uploadImageToSupabase($request->file('campaign_image'));
                    \Log::info('Image uploaded successfully:', ['url' => $imageUrl]);
                } catch (\Exception $e) {
                    $retryCount++;
                    if ($retryCount === $maxRetries) {
                        throw $e;
                    }
                    usleep(100000); // Wait 100ms before retry
                }
            }

            // Get user data from session
            $userData = session('user_data');
            if (!$userData) {
                \Log::error('User data not found in session');
                return redirect()->back()->with('error', 'User data not found')->withInput();
            }

            // Convert target value from currency format to integer
            $targetValue = (int) str_replace(['.', ','], '', $request->target_value);
            \Log::info('Target value converted:', ['original' => $request->target_value, 'converted' => $targetValue]);

            // Create donation data
            $donationData = [
                'name' => $request->donation_name,
                'target' => $targetValue,
                'category' => $request->category,
                'description' => $request->description,
                'finishDate' => new Timestamp(new \DateTime($request->finish_date)),
                'imageUrls' => [$imageUrl],
                'uid' => $userData['uid'],
                'createdAt' => new Timestamp(new \DateTime()),
                'progress' => 0.0,
                'organization' => $userData['name'],
                'views' => 0
            ];

            \Log::info('Saving donation data to Firestore:', $donationData);

            // Save to Firestore with retry mechanism
            $firestore = app('firebase.firestore');
            $maxRetries = 3;
            $retryCount = 0;
            $result = null;

            while ($result === null && $retryCount < $maxRetries) {
                try {
                    $result = $firestore->database()->collection('donations')->add($donationData);
                    \Log::info('Donation saved successfully:', ['id' => $result->id()]);
                } catch (\Exception $e) {
                    $retryCount++;
                    if ($retryCount === $maxRetries) {
                        throw $e;
                    }
                    usleep(100000); // Wait 100ms before retry
                }
            }
            
            return redirect()->route('dashboard')->with('success', 'Donation campaign created successfully!');
        } catch (\Exception $e) {
            \Log::error('Error creating donation:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Failed to create donation campaign: ' . $e->getMessage())->withInput();
        }
    }
}
