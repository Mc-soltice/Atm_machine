<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthControler;
use App\Http\Controllers\Api\AccountsController;
use App\Http\Controllers\Api\TransactionController;


//****************** Route publiques */
Route::get('/connection', [AuthControler::class, 'login']);
Route::post('/register', [AuthControler::class, 'register']);


//****************** Routes accessibles aux admins et utilisateurs connectés */
Route::middleware(['auth:sanctum'])->group(function () {
    Route::delete('/logout', [AuthControler::class, 'logout']);
});


//****************** Route privees client*/
Route::middleware(['auth:sanctum','ability:make_transaction'])->group(function () {

    Route::post('/transfer/{userId}', [TransactionController::class, 'transferFund']);
    Route::get('/transactions', [TransactionController::class, 'getAllTransactions']);
    Route::post('/accounts/{userId}', [TransactionController::class, 'doTransaction']);

});


//****************** Route privees Admin*/
Route::middleware(['auth:sanctum','ability:manage_account,manage_user'])->group(function () {
    
    Route::get('/users/{id}', [AuthControler::class, 'findUser']);
    Route::get('/users', [AuthControler::class, 'getUsers']);
    Route::delete('/users/{id}', [AuthControler::class, 'deleteUser']);
    Route::put('/users/{id}', [AuthControler::class, 'UpdateUser']);
    Route::get('/accounts', [AccountsController::class, 'getAccounts']);

});

Route::middleware(['auth:sanctum'])->get('/debug', function () {
    return [
        'user' => auth()->user(),
        'abilities' => auth()->user()->currentAccessToken()->abilities ?? []
    ];
});
Route::middleware(['auth:sanctum'])->get('/debug-auth', function () {
    return auth()->user();
});






