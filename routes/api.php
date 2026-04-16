<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\RecurringTransactionController;
use App\Http\Controllers\SavingController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/me', [AuthController::class, 'update']);

    // Categories
    Route::apiResource('categories', CategoryController::class);
    Route::patch('categories/{id}/archive', [CategoryController::class, 'archive']);

    // Transactions
    Route::apiResource('transactions', TransactionController::class);

    // Incomes
    Route::get('/incomes', [IncomeController::class, 'index']);
    Route::post('/incomes', [IncomeController::class, 'store']);
    Route::delete('/incomes/{id}', [IncomeController::class, 'destroy']);

    // Savings
    Route::apiResource('savings', SavingController::class);
    Route::post('/savings/{id}/deposit', [SavingController::class, 'deposit']);
    Route::post('/savings/{id}/withdraw', [SavingController::class, 'withdraw']);
    Route::get('/savings/{id}/history', [SavingController::class, 'history']);

    // Recurring Transactions
    Route::apiResource('recurring-transactions', RecurringTransactionController::class);

    // Summary
    Route::get('/summary', [SummaryController::class, 'index']);
});
