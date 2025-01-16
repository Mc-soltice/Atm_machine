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
Route::group(['prefix' => 'user'], function () 
{
    Route::middleware(['auth:sanctum','ability:make_transaction'])->group(function () 
    {

        Route::post('/update', [AuthControler::class, 'UpdatePin']);
        Route::post('/transfer', [TransactionController::class, 'transferFund']);
        Route::get('/transcript', [TransactionController::class, 'getAccountTranscript']);
        Route::get('/transactions', [TransactionController::class, 'getUserTransactions']);
        Route::post('/accounts/{userId}', [TransactionController::class, 'doTransaction']);
        
    });
});


//****************** Route privees Admin*/
Route::group(['prefix' => 'admin'], function () 
{
    Route::middleware(['auth:sanctum','ability:manage_account,manage_user'])->group(function () 
    {
        Route::get('/users', [AuthControler::class, 'getUsers']);
        Route::get('/users/{id}', [AuthControler::class, 'findUser']);
        Route::patch('/update_user/{id}', [AuthControler::class, 'UpdateUser']);
        Route::patch('/unlock_user/{id}', [AuthControler::class, 'unlockUser']);
        Route::delete('/delete_user/{id}', [AuthControler::class, 'deleteUser']);
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






