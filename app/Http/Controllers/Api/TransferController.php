<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessTransfer;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransferController extends Controller
{
    public function transfer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'target_user' => 'required|string|exists:users,user_id',
            'amount' => 'required|numeric|min:1',
            'remarks' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }

        $user = Auth::guard('api')->user();

        if ($user->balance < $request->amount) {
            return response()->json([
                'message' => 'Balance is not enough'
            ], 400);
        }

        if ($user->user_id === $request->target_user) {
            return response()->json([
                'message' => 'Cannot transfer to yourself'
            ], 400);
        }

        $targetUser = User::find($request->target_user);
        if (!$targetUser) {
            return response()->json([
                'message' => 'Target user not found'
            ], 400);
        }

        try {
            $transfer = Transfer::create([
                'user_id' => $user->user_id,
                'target_user' => $request->target_user,
                'amount' => $request->amount,
                'remarks' => $request->remarks,
                'balance_before' => $user->balance,
                'balance_after' => $user->balance - $request->amount,
                'status' => 'PENDING',
            ]);

            // Dispatch job to background
            ProcessTransfer::dispatch($transfer->transfer_id);

            return response()->json([
                'status' => 'SUCCESS',
                'result' => [
                    'transfer_id' => $transfer->transfer_id,
                    'amount' => (float) $transfer->amount,
                    'remarks' => $transfer->remarks,
                    'balance_before' => (float) $transfer->balance_before,
                    'balance_after' => (float) $transfer->balance_after,
                    'created_date' => $transfer->created_date->format('Y-m-d H:i:s'),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Transfer failed'
            ], 500);
        }
    }
}