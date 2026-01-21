<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\Payment;
use App\Models\TopUp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        
        $stats = [
            'total_users' => User::count(),
            'total_transfers' => Transfer::count(),
            'total_payments' => Payment::count(),
            'total_topups' => TopUp::count(),
            'pending_transfers' => Transfer::where('status', 'PENDING')->count(),
            'success_transfers' => Transfer::where('status', 'SUCCESS')->count(),
            'failed_transfers' => Transfer::where('status', 'FAILED')->count(),
        ];

        $recentTransfers = Transfer::with(['user', 'targetUser'])
            ->orderBy('created_date', 'desc')
            ->limit(10)
            ->get();

    
        $failedJobs = DB::table('failed_jobs')
            ->orderBy('failed_at', 'desc')
            ->limit(10)
            ->get();

        $pendingJobs = DB::table('jobs')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', compact('stats', 'recentTransfers', 'failedJobs', 'pendingJobs'));
    }

    public function transferStatus($id)
    {
        $transfer = Transfer::with(['user', 'targetUser'])->find($id);

        if (!$transfer) {
            return response()->json(['message' => 'Transfer not found'], 404);
        }

        return response()->json([
            'transfer_id' => $transfer->transfer_id,
            'status' => $transfer->status,
            'amount' => $transfer->amount,
            'from' => $transfer->user->first_name . ' ' . $transfer->user->last_name,
            'to' => $transfer->targetUser->first_name . ' ' . $transfer->targetUser->last_name,
            'created_date' => $transfer->created_date->format('Y-m-d H:i:s'),
        ]);
    }
}