<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
       
        $user = $request->auth_user;

        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'address' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }

        try {
            $updateData = [];
            
            if ($request->has('first_name')) {
                $updateData['first_name'] = $request->first_name;
            }
            
            if ($request->has('last_name')) {
                $updateData['last_name'] = $request->last_name;
            }
            
            if ($request->has('address')) {
                $updateData['address'] = $request->address;
            }

            User::where('user_id', $user->user_id)->update($updateData);
            
            $updatedUser = User::find($user->user_id);

            return response()->json([
                'status' => 'SUCCESS',
                'result' => [
                    'user_id' => $updatedUser->user_id,
                    'first_name' => $updatedUser->first_name,
                    'last_name' => $updatedUser->last_name,
                    'address' => $updatedUser->address,
                    'updated_date' => $updatedUser->updated_date ? $updatedUser->updated_date->format('Y-m-d H:i:s') : null,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Update profile failed'
            ], 500);
        }
    }
}