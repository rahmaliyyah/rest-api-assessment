<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\TopUp;
use App\Models\Transfer;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $user = auth()->user();
        $transactions = [];

        $transfers = Transfer::where('user_id', $user->user_id)
            ->where('status', 'SUCCESS')
            ->get()
            ->map(function ($item) {
                return [
                    'transfer_id' => $item->transfer_id,
                    'status' => $item->status,
                    'user_id' => $item->user_id,
                    'transaction_type' => 'DEBIT',
                    'amount' => (float) $item->amount,
                    'remarks' => $item->remarks,
                    'balance_before' => (float) $item->balance_before,
                    'balance_after' => (float) $item->balance_after,
                    'created_date' => $item->created_date->format('Y-m-d H:i:s'),
                ];
            });

        $payments = Payment::where('user_id', $user->user_id)
            ->get()
            ->map(function ($item) {
                return [
                    'payment_id' => $item->payment_id,
                    'status' => 'SUCCESS',
                    'user_id' => $item->user_id,
                    'transaction_type' => 'DEBIT',
                    'amount' => (float) $item->amount,
                    'remarks' => $item->remarks,
                    'balance_before' => (float) $item->balance_before,
                    'balance_after' => (float) $item->balance_after,
                    'created_date' => $item->created_date->format('Y-m-d H:i:s'),
                ];
            });

        $topUps = TopUp::where('user_id', $user->user_id)
            ->get()
            ->map(function ($item) {
                return [
                    'top_up_id' => $item->top_up_id,
                    'status' => 'SUCCESS',
                    'user_id' => $item->user_id,
                    'transaction_type' => 'CREDIT',
                    'amount' => (float) $item->amount_top_up,
                    'remarks' => '',
                    'balance_before' => (float) $item->balance_before,
                    'balance_after' => (float) $item->balance_after,
                    'created_date' => $item->created_date->format('Y-m-d H:i:s'),
                ];
            });

        $transactions = $transfers->merge($payments)->merge($topUps);

        return response()->json([
            'status' => 'SUCCESS',
            'result' => $transactions->values()
        ], 200);
    }
}