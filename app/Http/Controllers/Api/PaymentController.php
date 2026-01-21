<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function pay(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'remarks' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }

        $user = auth()->user();

        if ($user->balance < $request->amount) {
            return response()->json([
                'message' => 'Balance is not enough'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $balanceBefore = $user->balance;
            $balanceAfter = $balanceBefore - $request->amount;

            $payment = Payment::create([
                'user_id' => $user->user_id,
                'amount' => $request->amount,
                'remarks' => $request->remarks,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
            ]);

            $user->update(['balance' => $balanceAfter]);

            DB::commit();

            return response()->json([
                'status' => 'SUCCESS',
                'result' => [
                    'payment_id' => $payment->payment_id,
                    'amount' => (float) $payment->amount,
                    'remarks' => $payment->remarks,
                    'balance_before' => (float) $payment->balance_before,
                    'balance_after' => (float) $payment->balance_after,
                    'created_date' => $payment->created_date->format('Y-m-d H:i:s'),
                ]
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Payment failed'
            ], 500);
        }
    }
}