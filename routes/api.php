<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TopUpController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\TransferController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\ProfileController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('simple.auth')->group(function () {
    Route::post('/topup', [TopUpController::class, 'topUp']);
    Route::post('/pay', [PaymentController::class, 'pay']);
    Route::post('/transfer', [TransferController::class, 'transfer']);
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::put('/profile', [ProfileController::class, 'update']);
});