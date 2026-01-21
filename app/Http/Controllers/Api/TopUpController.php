<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TopUp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TopUpController extends Controller
{
    public function topUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }

    
        $user = $request->auth_user;

        try {
            DB::beginTransaction();

            $balanceBefore = $user->balance;
            $balanceAfter = $balanceBefore + $request->amount;

            $topUp = TopUp::create([
                'user_id' => $user->user_id,
                'amount_top_up' => $request->amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
            ]);

            User::where('user_id', $user->user_id)->update(['balance' => $balanceAfter]);

            DB::commit();

            return response()->json([
                'status' => 'SUCCESS',
                'result' => [
                    'top_up_id' => $topUp->top_up_id,
                    'amount_top_up' => (float) $topUp->amount_top_up,
                    'balance_before' => (float) $topUp->balance_before,
                    'balance_after' => (float) $topUp->balance_after,
                    'created_date' => optional($topUp->created_date)->format('Y-m-d H:i:s'),

                ]
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Top up failed'
            ], 500);
        }
    }
}