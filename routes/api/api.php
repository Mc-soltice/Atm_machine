<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthControler;
use App\Http\Controllers\Api\AccountsController;
use App\Http\Controllers\Api\TransactionController;


//****************** Route publiques */
Route::post('/register', [AuthControler::class, 'register']);
Route::get('/connexion', [AuthControler::class, 'login'])->middleware(['checklock']);


//****************** Routes accessibles aux admins et utilisateurs connectés */
Route::middleware(['auth:sanctum'])->group(function () {
    Route::delete('/logout', [AuthControler::class, 'logout']);
});


//****************** Route privees client*/
Route::middleware(['auth:sanctum','ability:make_transaction'])->group(function () 
{

    Route::post('/transfer/{userId}', [TransactionController::class, 'transferFund']);
    Route::post('/accounts/{userId}', [TransactionController::class, 'doTransaction']);
    Route::get('/transactions', [TransactionController::class, 'getUserTransactions']);
    
});


//****************** Route privees Admin*/
Route::group(['prefix' => 'admin'], function () 
{
    Route::middleware(['auth:sanctum','ability:manage_account,manage_user'])->group(function () 
    {
        Route::get('/users', [AuthControler::class, 'getUsers']);
        Route::get('/users/{id}', [AuthControler::class, 'findUser']);
        Route::put('/users/{id}', [AuthControler::class, 'UpdateUser']);
        Route::patch('/user/{id}', [AuthControler::class, 'unlockUser']);
        Route::delete('/users/{id}', [AuthControler::class, 'deleteUser']);
        Route::get('/accounts', [AccountsController::class, 'getAccounts']);
        Route::post('/accounts/{userId}', [TransactionController::class, 'doTransaction']);
        Route::get('/all_transactions', [TransactionController::class, 'getAllTransactions']);

    });
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






