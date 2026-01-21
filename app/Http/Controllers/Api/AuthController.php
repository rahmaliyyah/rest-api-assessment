<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|unique:users,phone_number',
            'address' => 'required|string',
            'pin' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }

        // Check if phone number already exists
        if (User::where('phone_number', $request->phone_number)->exists()) {
            return response()->json([
                'message' => 'Phone Number already registered'
            ], 400);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'pin' => Hash::make($request->pin),
            'balance' => 0,
        ]);

        return response()->json([
            'status' => 'SUCCESS',
            'result' => [
                'user_id' => $user->user_id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'phone_number' => $user->phone_number,
                'address' => $user->address,
                'created_date' => $user->created_date->format('Y-m-d H:i:s'),
            ]
        ], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string',
            'pin' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }

        $user = User::where('phone_number', $request->phone_number)->first();

        if (!$user || !Hash::check($request->pin, $user->pin)) {
            return response()->json([
                'message' => "Phone number and pin doesn't match."
            ], 400);
        }

        $token = Auth::guard('api')->login($user);

        return response()->json([
            'status' => 'SUCCESS',
            'result' => [
                'access_token' => $token,
                'refresh_token' => $token,
            ]
        ], 200);
    }
}