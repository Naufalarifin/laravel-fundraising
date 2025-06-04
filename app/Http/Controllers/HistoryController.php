<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirestoreService;
use Carbon\Carbon;

class HistoryController extends Controller
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

        // Get transactions from Firestore
        $transactions = $this->firestoreService->getCollection('transactions')
            ->where('userId', '=', $userData['uid'])
            ->documents();

        // Group transactions by month
        $groupedTransactions = [];
        foreach ($transactions as $transaction) {
            $data = $transaction->data();
            $date = Carbon::parse($data['date'])->setTimezone('Asia/Jakarta');
            $monthKey = $date->format('F Y');
            
            if (!isset($groupedTransactions[$monthKey])) {
                $groupedTransactions[$monthKey] = [];
            }

            $groupedTransactions[$monthKey][] = [
                'id' => $transaction->id(),
                'type' => $data['category'] === 'income' ? 'topup' : 'donation',
                'title' => $data['name'],
                'amount' => $data['category'] === 'income' ? $data['amount'] : -$data['amount'],
                'date' => $date->format('Y-m-d'),
                'status' => 'completed'
            ];
        }

        // Sort months in descending order
        krsort($groupedTransactions);
        
        // Sort transactions within each month by date (newest first)
        foreach ($groupedTransactions as &$monthTransactions) {
            usort($monthTransactions, function($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });
        }

        return view('history', ['transactions' => $groupedTransactions]);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $userData = session('user_data');
        
        if (!$userData) {
            return response()->json(['results' => []]);
        }

        // Get transactions from Firestore
        $transactions = $this->firestoreService->getCollection('transactions')
            ->where('userId', '=', $userData['uid'])
            ->orderBy('date', 'desc')
            ->documents();

        $results = [];
        foreach ($transactions as $transaction) {
            $data = $transaction->data();
            // Search in transaction name
            if (stripos($data['name'], $query) !== false) {
                $date = Carbon::parse($data['date']);
                $results[] = [
                    'id' => $transaction->id(),
                    'type' => $data['category'] === 'income' ? 'topup' : 'donation',
                    'title' => $data['name'],
                    'amount' => $data['category'] === 'income' ? $data['amount'] : -$data['amount'],
                    'date' => $date->format('Y-m-d'),
                    'status' => 'completed'
                ];
            }
        }

        return response()->json(['results' => $results]);
    }
}
